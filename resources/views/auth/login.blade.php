<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Doc_Tracking') }}</title>
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('js/crypto-js.min.js') }}"></script>
        <script src="{{ asset('js/jsencrypt.min.js') }}"></script>
        <script src="{{ asset('js/crypto-js.min.js') }}"></script>
    </head>
    <body>
        <div id="app">
            <div class="login">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-8">
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="loginContent">
                                {{-- <img class="logoIcon" src="/images/logoIcon.svg"/> --}}
                                <h2>DOCUMENT TRACKER</h2>
                                {{-- <div> <img width="300" src="/images/logo.svg" /> </div> --}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="login-bg">
                                <form method="POST" action="{{ route('login') }}" id="login-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="col-form-label">{{ __('Employee ID') }}</label>
                                        <div class="">
                                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}"  autofocus>
                                            @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>                                    
                                    <div class="mb-3">
                                        <label for="password" class=" col-form-label ">{{ __('Password') }}</label>
                                        <div class="">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="">
                                            <input type="hidden" name="enc_aes_key">
                                            <button type="submit" id="login" class="btn btn-primary">{{ __('Login') }} </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <script nonce='{{ env("CSP_NONCE") }}'>
            // let aesKey = null;
            // $('#login-form').on('submit', async function (e) {
            //     e.preventDefault();
            //     const $passwordField = $('#password');
        
            //     try {
            //         // Fetch AES key only if not already available
            //         if (!aesKey) {
            //             const response = await $.ajax({
            //                 url: '/get-key',
            //                 method: 'GET',
            //                 dataType: 'json'
            //             });
            //             // aesKey = response.aes_key_encrypted; 
            //             aesKey = response.aes_key_base64; 
            //         }
        
            //         if (!aesKey) {
            //             throw new Error('AES key not available. Please refresh the page.');
            //         }
        
            //         // Convert Base64 AES key → WordArray for CryptoJS
            //         const key = CryptoJS.enc.Base64.parse(aesKey);
        
            //         // Encrypt password using AES-128-ECB
            //         const encryptedPassword = CryptoJS.AES.encrypt(
            //             $passwordField.val(),
            //             key,
            //             { mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7 }
            //         ).toString();
        
            //         // Replace password field with encrypted value
            //         $passwordField.val(encryptedPassword);
        
            //         this.submit(); // Continue form submit
            //     } catch (error) {
            //         alert('Encryption error: ' + error.message);
            //         console.error(error);
            //     }
            // });
            // // Use $.ajax for fetching the AES key
            // function fetchAESKey() {
            //     return $.ajax({
            //         url: '/get-key',
            //         method: 'GET', // Or 'POST' if the server expects it
            //         dataType: 'json'
            //     })
            //     .done(function(data) {
            //         aesKey = data.aes_key;
            //     })
            //     .fail(function(jqXHR, textStatus, errorThrown) {
            //         alert('Error fetching AES key: ' + textStatus); // Handle errors
            //     });
            // }
        
            // function encryptWithAES(password) {
            //     if (!aesKey) {
            //         alert('AES key not available. Please refresh the page.');
            //         return null;
            //     }
        
            //     // Convert AES key from Base64
            //     const key = CryptoJS.enc.Utf8.parse(aesKey);
            //     const encrypted = CryptoJS.AES.encrypt(password, key, { mode: CryptoJS.mode.ECB }).toString();
            //     return encrypted;
            // }
        
            // // Use jQuery's .on() for event handling
            // $('#login-form').on('submit', async function (e) {
            //     e.preventDefault();
        
            //     if (!aesKey) {
            //         try {
            //             await fetchAESKey();
            //         } catch (error) {
            //             // Error already handled in the fetchAESKey() fail callback
            //             return;
            //         }
            //     }
        
            //     // const $passwordField = $('#password'); // Use jQuery selector for the password field
            //     const encryptedPassword = encryptWithAES($('#password').val()); // Get value using .val()
        
            //     if (encryptedPassword) {
            //         $('#password').val(encryptedPassword); // Set value using .val()
            //         this.submit(); // Submit the form (non-AJAX, if that's desired)
            //         // If you want to submit the form via AJAX as well, replace this.submit() with $.ajax()
            //         // See the explanation for an example of submitting via AJAX
            //     } else {
            //         alert('Encryption failed!');
            //     }
            // });
        
            // $(document).ready(function () {
            //     $('#login-form').validate({
            //         rules: {
            //             username: {
            //                 required: true,
            //                 alphanumeric: true 
            //             },
            //             password: {
            //                 required: true,
            //                 minlength: 6
            //             }
            //         },
            //         messages: {
            //             username: {
            //                 required: "Please enter your Employee ID",
            //                 alphanumeric: "Employee ID must be letters and numbers only"
            //             },
            //             password: {
            //                 required: "Please enter your password",
            //                 minlength: "Password must be at least 6 characters"
            //             }
            //         },
            //         errorElement: 'span',
            //         errorClass: 'invalid-feedback',
            //         highlight: function (element) {
            //             $(element).addClass('is-invalid');
            //         },
            //         unhighlight: function (element) {
            //             $(element).removeClass('is-invalid');
            //         },
            //         errorPlacement: function (error, element) {
            //             error.insertAfter(element);
            //         }
            //     });
            
            //     // Trim input
            //     $('input').on('input', function () {
            //         $(this).val($(this).val().trim());
            //     });
            // });
        
        
            // let aesKey = null; // Stays global for persistence
        
            // Use jQuery's .on() for event handling
            // $('#login-form').on('submit', async function (e) {
            //     e.preventDefault(); // Prevent default form submission
            //     const $passwordField = $('#password'); // Cache the password field selector
            //     try {
            //         // Fetch AES key only if it's not already available
            //         if (!aesKey) {
            //             const response = await $.ajax({
            //                 url: '/get-key',
            //                 method: 'GET',
            //                 dataType: 'json'
            //             });
            //             aesKey = response.aes_key;
            //         }
        
            //         // Encrypt the password
            //         if (!aesKey) { // Double-check if key is still unavailable (e.g., if fetch failed silently)
            //             throw new Error('AES key not available for encryption. Please refresh the page.');
            //         }
        
            //         // Convert AES key from Base64
            //         const key = CryptoJS.enc.Utf8.parse(aesKey);
            //         const encryptedPassword = CryptoJS.AES.encrypt($passwordField.val(), key, { mode: CryptoJS.mode.ECB }).toString();
        
            //         // Update the password field and submit the form
            //         $passwordField.val(encryptedPassword);
            //         this.submit(); // Perform the standard form submission (with encrypted password)
            //     } catch (error) {
            //         // Centralized error handling
            //         alert('An error occurred: ' + error.message);
            //         console.error(error); // Log the error for debugging
            //     }
            // });
        $(document).ready(function() {
            let rsaPublicKey = null;

            // 1. Fetch the public key from the server as soon as the page loads
            $.ajax({
                url: '/get-key',
                method: 'GET',
                success: function(data) {
                    rsaPublicKey = data.public_key;
                },
                error: function() {
                    alert('Critical security error: Could not fetch public key. Please refresh.');
                }
            });

            // 2. Intercept the form submission
            $('#login-form').on('submit', function(e) {
                e.preventDefault(); // Stop the form from submitting immediately

                if (!rsaPublicKey) {
                    alert('Security key is not yet available. Please wait a moment and try again.');
                    return;
                }

                const form = this;
                const password = $('#password').val();

                // 3. Generate a random AES key and IV
                // CryptoJS word arrays are 32-bit words. 128 bits = 4 words.
                const aesKey = CryptoJS.lib.WordArray.random(128 / 8); // 16 bytes
                const aesIv = CryptoJS.lib.WordArray.random(128 / 8);  // 16 bytes for CBC

                // 4. Encrypt the password with AES-CBC
                const encryptedPassword = CryptoJS.AES.encrypt(password, aesKey, {
                    iv: aesIv,
                    mode: CryptoJS.mode.CBC, // Use a secure mode like CBC
                    padding: CryptoJS.pad.Pkcs7
                }).toString();

                // 5. Encrypt the AES key and IV with the RSA public key
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

                // 6. Populate hidden fields, clear original password, and submit
                // $('input[name="enc_password"]').val(encryptedPassword);
                $('input[name="enc_aes_key"]').val(encryptedAesKey);
                $('#password').val(encryptedPassword); // Clear the plaintext password from the form

                form.submit();
            });
        });
        </script>     --}}
        <script src="{{ asset('js/encryption.js') }}"></script>
    </body>
</html>
