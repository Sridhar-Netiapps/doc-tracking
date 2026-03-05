(function ($) {
    'use strict';

    let cachedPublicKeyPem = null;
    let cachedRsaKey = null;

    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content') || '';
    }

    function base64ToUint8Array(base64) {
        const binary = atob(base64);
        const bytes = new Uint8Array(binary.length);
        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }
        return bytes;
    }

    function uint8ArrayToBase64(bytes) {
        let binary = '';
        for (let i = 0; i < bytes.length; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return btoa(binary);
    }

    function pemToDerBytes(pem) {
        const clean = pem.replace(/-----(BEGIN|END) PUBLIC KEY-----|\s/g, '');
        return base64ToUint8Array(clean);
    }

    async function getPublicKeyPem() {
        if (cachedPublicKeyPem) {
            return cachedPublicKeyPem;
        }

        const data = await $.ajax({
            url: '/api/secure-reveal-key',
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        cachedPublicKeyPem = data.public_key;
        return cachedPublicKeyPem;
    }

    async function getRsaPublicKey() {
        if (cachedRsaKey) {
            return cachedRsaKey;
        }

        const pem = await getPublicKeyPem();
        const publicKeyBytes = pemToDerBytes(pem);
        cachedRsaKey = await window.crypto.subtle.importKey(
            'spki',
            publicKeyBytes,
            { name: 'RSA-OAEP', hash: 'SHA-256' },
            false,
            ['encrypt']
        );

        return cachedRsaKey;
    }

    function formToObject(form) {
        const data = {};
        const formData = new FormData(form);

        for (const [key, value] of formData.entries()) {
            if (value instanceof File) {
                continue;
            }

            if (Object.prototype.hasOwnProperty.call(data, key)) {
                if (!Array.isArray(data[key])) {
                    data[key] = [data[key]];
                }
                data[key].push(value);
            } else {
                data[key] = value;
            }
        }

        return { data, formData };
    }

    async function encryptObjectToSecureReq(payload) {
        const rsaKey = await getRsaPublicKey();
        const iv = window.crypto.getRandomValues(new Uint8Array(12));
        const rawData = new TextEncoder().encode(JSON.stringify(payload));

        const aesKey = await window.crypto.subtle.generateKey(
            { name: 'AES-GCM', length: 256 },
            true,
            ['encrypt']
        );

        const encryptedContent = await window.crypto.subtle.encrypt(
            { name: 'AES-GCM', iv: iv },
            aesKey,
            rawData
        );

        const exportedAesKey = new Uint8Array(await window.crypto.subtle.exportKey('raw', aesKey));
        const encryptedKey = await window.crypto.subtle.encrypt(
            { name: 'RSA-OAEP' },
            rsaKey,
            exportedAesKey
        );

        return btoa(JSON.stringify({
            k: uint8ArrayToBase64(new Uint8Array(encryptedKey)),
            i: uint8ArrayToBase64(iv),
            d: uint8ArrayToBase64(new Uint8Array(encryptedContent))
        }));
    }

    async function sendSecureJson(url, payload, method, options) {
        const requestOptions = options || {};
        const secureReq = await encryptObjectToSecureReq(payload);
        const headers = {
            'Content-Type': 'application/json',
            'Accept': requestOptions.accept || 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        };

        if (!requestOptions.skipAjaxHeader) {
            headers['X-Requested-With'] = 'XMLHttpRequest';
        }

        const response = await fetch(url, {
            method: (method || 'POST').toUpperCase(),
            credentials: 'same-origin',
            headers: headers,
            body: JSON.stringify({
                secure_req: secureReq
            })
        });

        return response;
    }

    async function submitSecureForm(form) {
        if (!window.crypto || !window.crypto.subtle) {
            form.submit();
            return;
        }

        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }

        try {
            const serialized = formToObject(form);
            const methodOverride = serialized.formData.get('_method');
            const method = methodOverride ? String(methodOverride).toUpperCase() : (form.getAttribute('method') || 'POST').toUpperCase();
            const action = form.getAttribute('action') || window.location.href;

            const response = await sendSecureJson(action, serialized.data, method, {
                accept: 'text/html,application/xhtml+xml',
                skipAjaxHeader: true
            });

            if (response.redirected) {
                window.location.assign(response.url);
                return;
            }

            const contentType = response.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                const body = await response.json();
                if (!response.ok) {
                    throw new Error(body.message || 'Secure submission failed');
                }

                if (body.redirect_url) {
                    window.location.assign(body.redirect_url);
                    return;
                }

                window.location.reload();
                return;
            }

            if (!response.ok) {
                throw new Error('Secure submission failed');
            }

            window.location.assign(response.url || window.location.href);
        } catch (error) {
            console.error(error);
            if (submitButton) {
                submitButton.disabled = false;
            }
            alert('Unable to submit secure form. Please try again.');
        }
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        if (!form.classList.contains('secure-ale-form')) {
            return;
        }

        event.preventDefault();
        submitSecureForm(form);
    });

    window.SecureAle = {
        encryptObjectToSecureReq: encryptObjectToSecureReq,
        sendSecureJson: sendSecureJson,
        submitSecureForm: submitSecureForm
    };
})(jQuery);
