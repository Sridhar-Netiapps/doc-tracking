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
        if(!rsaPublicKey){
            await $.ajax({
                url: '/get-key',
                method: 'GET',
                success: function(data) {
                    rsaPublicKey = data.public_key;
                },
                error: function() {
                    alert('Critical security error: Could not fetch public key. Please refresh.');
                }
            });
        }
        if (!rsaPublicKey) {
            alert('Security key is not yet available. Please wait a moment and try again.');
            return;
        }
        const form = this;
        const password = $('#password').val();
        const aesKey = CryptoJS.lib.WordArray.random(128 / 8);
        const aesIv = CryptoJS.lib.WordArray.random(128 / 8);
        const encryptedPassword = CryptoJS.AES.encrypt(password, aesKey, {
            iv: aesIv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString();
        const rsaEncrypt = new JSEncrypt();
        rsaEncrypt.setPublicKey(rsaPublicKey);
        const aesPayload = JSON.stringify({
            key: CryptoJS.enc.Base64.stringify(aesKey),
            iv: CryptoJS.enc.Base64.stringify(aesIv)
        });
        
        const encryptedAesKey = rsaEncrypt.encrypt(aesPayload);
        
        if (!encryptedAesKey) {
            alert('Encryption failed. Could not encrypt session key.');
            return;
        }
        $('input[name="enc_aes_key"]').val(encryptedAesKey);
        $('#password').val(encryptedPassword);
        form.submit();
    });
});