(function ($) {
    'use strict';

    let cachedPublicKey = null;

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
        if (cachedPublicKey) {
            return cachedPublicKey;
        }

        const data = await $.ajax({
            url: '/api/secure-reveal-key',
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        cachedPublicKey = data.public_key;
        return cachedPublicKey;
    }

    async function revealNode(node) {
        const $node = $(node);
        if ($node.data('revealed') === 1 || $node.attr('data-revealed') === '1') {
            return;
        }

        const token = $node.data('token') || '';

        if (!token) {
            return;
        }

        const previousText = $node.text();
        $node.text('Decrypting...');

        try {
            const publicKeyPem = await getPublicKeyPem();
            const publicKeyBytes = pemToDerBytes(publicKeyPem);

            const rsaKey = await window.crypto.subtle.importKey(
                'spki',
                publicKeyBytes,
                { name: 'RSA-OAEP', hash: 'SHA-256' },
                false,
                ['encrypt']
            );

            const aesKey = await window.crypto.subtle.generateKey(
                { name: 'AES-GCM', length: 256 },
                true,
                ['encrypt', 'decrypt']
            );

            const rawAesKey = new Uint8Array(await window.crypto.subtle.exportKey('raw', aesKey));
            const wrappedKeyBuffer = await window.crypto.subtle.encrypt(
                { name: 'RSA-OAEP' },
                rsaKey,
                rawAesKey
            );

            const requestIv = window.crypto.getRandomValues(new Uint8Array(12));
            const requestData = new TextEncoder().encode(JSON.stringify({
                token: token
            }));

            const encryptedRequestBuffer = await window.crypto.subtle.encrypt(
                { name: 'AES-GCM', iv: requestIv },
                aesKey,
                requestData
            );

            const secureBlob = btoa(JSON.stringify({
                k: uint8ArrayToBase64(new Uint8Array(wrappedKeyBuffer)),
                i: uint8ArrayToBase64(requestIv),
                d: uint8ArrayToBase64(new Uint8Array(encryptedRequestBuffer))
            }));

            const encrypted = await $.ajax({
                url: '/api/secure-reveal',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: JSON.stringify({
                    secure_req: secureBlob
                })
            });

            if (!encrypted.secure_res) {
                throw new Error('Secure reveal failed');
            }
            const responseBinary = base64ToUint8Array(encrypted.secure_res);
            if (responseBinary.length <= 12) {
                throw new Error('Invalid response');
            }
            const iv = responseBinary.slice(0, 12);
            const combined = responseBinary.slice(12);

            const decryptedBuffer = await window.crypto.subtle.decrypt(
                { name: 'AES-GCM', iv: iv },
                aesKey,
                combined
            );

            const plaintext = new TextDecoder().decode(decryptedBuffer);
            $node.text(plaintext);
            $node.attr('data-revealed', '1');
        } catch (error) {
            console.error(error);
            $node.text(previousText);
        }
    }

    // document.addEventListener('DOMContentLoaded', async function () {
    //     const nodes = document.querySelectorAll('.secure-data-node[data-token]');
    //     for (const node of nodes) {
    //         await revealNode(node);
    //     }
    // });

    async function revealChunk(nodes, rsaKey) {
        const aesKey = await window.crypto.subtle.generateKey(
            { name: 'AES-GCM', length: 256 },
            true,
            ['encrypt', 'decrypt']
        );

        const rawAesKey = new Uint8Array(await window.crypto.subtle.exportKey('raw', aesKey));
        const wrappedKeyBuffer = await window.crypto.subtle.encrypt(
            { name: 'RSA-OAEP' },
            rsaKey,
            rawAesKey
        );

        const tokens = nodes.map(function (node, idx) {
            return { idx: idx, token: $(node).data('token') || '' };
        });

        const requestIv = window.crypto.getRandomValues(new Uint8Array(12));
        const requestData = new TextEncoder().encode(JSON.stringify({ tokens: tokens }));
        const encryptedRequestBuffer = await window.crypto.subtle.encrypt(
            { name: 'AES-GCM', iv: requestIv },
            aesKey,
            requestData
        );

        const secureBlob = btoa(JSON.stringify({
            k: uint8ArrayToBase64(new Uint8Array(wrappedKeyBuffer)),
            i: uint8ArrayToBase64(requestIv),
            d: uint8ArrayToBase64(new Uint8Array(encryptedRequestBuffer))
        }));

        let response;
        try {
            response = await $.ajax({
                url: '/api/secure-reveal-batch',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: JSON.stringify({ secure_req: secureBlob })
            });
        } catch (e) {
            console.error('Batch reveal failed', e);
            return;
        }

        if (!response || !Array.isArray(response.results)) {
            return;
        }

        for (var r = 0; r < response.results.length; r++) {
            var result = response.results[r];
            if (result.error || !result.secure_res) {
                continue;
            }
            var node = nodes[result.idx];
            if (!node) {
                continue;
            }
            try {
                var responseBinary = base64ToUint8Array(result.secure_res);
                if (responseBinary.length <= 12) {
                    continue;
                }
                var decryptedBuffer = await window.crypto.subtle.decrypt(
                    { name: 'AES-GCM', iv: responseBinary.slice(0, 12) },
                    aesKey,
                    responseBinary.slice(12)
                );
                $(node).text(new TextDecoder().decode(decryptedBuffer)).attr('data-revealed', '1');
            } catch (e) {
                console.error('Field decrypt failed', e);
            }
        }
    }

    async function revealBatch(nodes) {
        var pending = nodes.filter(function (n) {
            return $(n).data('revealed') !== 1 &&
                $(n).attr('data-revealed') !== '1' &&
                $(n).data('token');
        });
        if (pending.length === 0) {
            return;
        }

        var publicKeyPem = await getPublicKeyPem();
        var publicKeyBytes = pemToDerBytes(publicKeyPem);
        var rsaKey = await window.crypto.subtle.importKey(
            'spki',
            publicKeyBytes,
            { name: 'RSA-OAEP', hash: 'SHA-256' },
            false,
            ['encrypt']
        );

        // Send up to 300 tokens per request
        var BATCH_SIZE = 300;
        for (var i = 0; i < pending.length; i += BATCH_SIZE) {
            await revealChunk(pending.slice(i, i + BATCH_SIZE), rsaKey);
        }
    }

    document.addEventListener('DOMContentLoaded', async function () {
        // Reveal active tab only on load
        var activePane = document.querySelector('.tab-pane.active');
        var initialNodes = activePane
            ? Array.from(activePane.querySelectorAll('.secure-data-node[data-token]'))
            : Array.from(document.querySelectorAll('.secure-data-node[data-token]'));
        await revealBatch(initialNodes);

        // Reveal each tab's nodes when user switches to it
        $(document).on('shown.bs.tab', async function (e) {
            var targetSelector = $(e.target).attr('data-bs-target') || $(e.target).attr('href');
            if (!targetSelector) {
                return;
            }
            var pane = document.querySelector(targetSelector);
            if (!pane) {
                return;
            }
            await revealBatch(Array.from(pane.querySelectorAll('.secure-data-node[data-token]')));
        });
    });
})(jQuery);
