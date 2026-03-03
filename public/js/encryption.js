$(document).ready(function () {
    $("#password").on("paste", function(event) {
        event.preventDefault();
    });
    let rsaPublicKey = null;
    $('#login-form').validate({
        rules: {
            username: {
                required: true,
                alphanumeric: true,
                sanitize:true
            },
            password: {
                required: true,
                minlength: 8,
                sanitize:true
            }
        },
        messages: {
            username: {
                required: "Please enter your Employee ID",
                alphanumeric: "Employee ID must be letters and numbers only"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 8 characters"
            }
        },
        errorElement: 'span',
        errorClass: 'invalid-feedback',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });
    $('input').on('input', function () {
        $(this).val($(this).val().trim());
    });

    $('#login-form').on('submit', async function(e) {
        e.preventDefault();
        const form = this;

        // 1. Get the Public Key from your Laravel endpoint
        const response = await fetch('/get-key');
        const { public_key } = await response.json();

        // 2. Prepare PII Data
        const rawData = new TextEncoder().encode(JSON.stringify({
            username: $('#username').val(),
            password: $('#password').val(),
            ts: Date.now()
        }));

        // 3. Generate a random AES-GCM Key (Better than CBC for VAPT)
        const aesKey = await window.crypto.subtle.generateKey(
            { name: "AES-GCM", length: 256 }, true, ["encrypt"]
        );
        const iv = window.crypto.getRandomValues(new Uint8Array(12));

        // 4. Encrypt Data with AES
        const encryptedContent = await window.crypto.subtle.encrypt(
            { name: "AES-GCM", iv: iv }, aesKey, rawData
        );

        // 5. Encrypt AES Key with RSA (Native)
        const exportedAesKey = await window.crypto.subtle.exportKey("raw", aesKey);
        const pemContents = public_key.replace(/-----(BEGIN|END) PUBLIC KEY-----|\s/g, "");
        const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));
        const rsaKey = await window.crypto.subtle.importKey(
            "spki", binaryDer, { name: "RSA-OAEP", hash: "SHA-256" }, false, ["encrypt"]
        );
        const encryptedKey = await window.crypto.subtle.encrypt({ name: "RSA-OAEP" }, rsaKey, exportedAesKey);

        // 6. Build the Payload
        const payload = btoa(JSON.stringify({
            p: btoa(String.fromCharCode(...new Uint8Array(encryptedContent))),
            k: btoa(String.fromCharCode(...new Uint8Array(encryptedKey))),
            i: btoa(String.fromCharCode(...iv))
        }));

        $('<input>').attr({ type: 'hidden', name: 'payload', value: payload }).appendTo(form);
        $('#username, #password').val('********');
        form.submit();
    });

    // $('#login-form').on('submit', async function(e) {
    //     e.preventDefault();
        
    //     // 1. Ensure we have the RSA Key
    //     if(!rsaPublicKey){
    //         try {
    //             const data = await $.ajax({ url: '/get-key', method: 'GET' });
    //             rsaPublicKey = data.public_key;
    //         } catch (err) {
    //             alert('Security error: Could not fetch encryption keys.');
    //             return;
    //         }
    //     }

    //     const form = this;

    //     // 2. Prepare the data (Avoid btoa here if you are encrypting the whole thing anyway)
    //     const rawData = {
    //         username: $('#username').val(),
    //         password: $('#password').val(),
    //         ts: Date.now() // Adding a timestamp prevents "Replay Attacks" (Another VAPT win!)
    //     };

    //     // 3. Generate AES Key and IV 
    //     // These methods work as long as core.min.js and aes.min.js are loaded
    //     const aesKey = CryptoJS.lib.WordArray.random(256 / 8); 
    //     const aesIv = CryptoJS.lib.WordArray.random(128 / 8);

    //     // 4. Encrypt Payload with AES-256-CBC
    //     const encryptedData = CryptoJS.AES.encrypt(JSON.stringify(rawData), aesKey, {
    //         iv: aesIv,
    //         mode: CryptoJS.mode.CBC,
    //         padding: CryptoJS.pad.Pkcs7
    //     }).toString();

    //     // 5. Encrypt AES Key/IV with RSA Public Key
    //     const rsaEncrypt = new JSEncrypt();
    //     rsaEncrypt.setPublicKey(rsaPublicKey);
        
    //     // Note: Use CryptoJS.enc.Base64 for the key/iv strings
    //     const encryptedKey = rsaEncrypt.encrypt(JSON.stringify({
    //         key: CryptoJS.enc.Base64.stringify(aesKey),
    //         iv: CryptoJS.enc.Base64.stringify(aesIv)
    //     }));

    //     // 6. Create the Secure Blob
    //     const secureBlob = btoa(JSON.stringify({
    //         payload: encryptedData,
    //         signature: encryptedKey
    //     }));

    //     // 7. Security: Clear sensitive inputs before submitting
    //     $('#username').val('********');
    //     $('#password').val('********');

    //     // Attach blob to a hidden input and submit
    //     $('<input>').attr({
    //         type: 'hidden',
    //         name: 'payload',
    //         value: secureBlob
    //     }).appendTo(form);

    //     form.submit();
    // });

    // $('#login-form').on('submit', async function (e) {
    //     e.preventDefault();

    //     if (!window.crypto || !crypto.subtle) {
    //         alert('Secure crypto not supported');
    //         return;
    //     }

    //     if (!rsaPublicKey) {
    //         try {
    //             const res = await $.get('/get-key');
    //             rsaPublicKey = res.public_key;
    //         } catch {
    //             alert('Security initialization failed');
    //             return;
    //         }
    //     }

    //     const rawData = {
    //         username: btoa($('#username').val()),
    //         password: btoa($('#password').val()),
    //         ts: Date.now(),
    //         nonce: crypto.randomUUID()
    //     };

    //     const encoder = new TextEncoder();
    //     const data = encoder.encode(JSON.stringify(rawData));

    //     // AES-GCM
    //     const aesKey = await crypto.subtle.generateKey(
    //         { name: 'AES-GCM', length: 256 },
    //         true,
    //         ['encrypt']
    //     );

    //     const iv = crypto.getRandomValues(new Uint8Array(12));

    //     const encrypted = await crypto.subtle.encrypt(
    //         { name: 'AES-GCM', iv },
    //         aesKey,
    //         data
    //     );

    //     const rawKey = await crypto.subtle.exportKey('raw', aesKey);

    //     const rsa = new JSEncrypt();
    //     rsa.setPublicKey(rsaPublicKey);

    //     const encryptedKey = rsa.encrypt(JSON.stringify({
    //         key: btoa(String.fromCharCode(...new Uint8Array(rawKey))),
    //         iv: btoa(String.fromCharCode(...iv))
    //     }));

    //     const payload = btoa(JSON.stringify({
    //         d: btoa(String.fromCharCode(...new Uint8Array(encrypted))),
    //         k: encryptedKey
    //     }));

    //     $('#username, #password').remove();
    //     $('input[name="payload"]').val(payload);
    //     this.submit();
    // });

});