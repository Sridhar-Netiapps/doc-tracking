$(document).ready(function(){
    $.validator.addMethod('sanitize', function (value, element) {
        return this.optional(element) || /^[^<>]*$/.test(value);
    }, 'Please enter valid input.');

    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[\w\s]+$/i.test(value);
    }, "Please enter letters, numbers, spaces or underscores only");

    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Please enter letters only"); 
    
    $.validator.addMethod("noSQL", function(value, element) {
        var sqlPattern = /(select|insert|update|delete|drop|exec|union|--|;|\/\*|\*\/)/i;
        return this.optional(element) || !sqlPattern.test(value);
    }, "Invalid input.");

    $.validator.addMethod("emp_id", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Spaces and special characters are not allowed.");

    $.validator.addMethod("removeLeadingZeros", function(value, element) {
        return this.optional(element) || /^[1-9]\d*$/.test(value);
    }, "Please enter a valid number leading zeros not allowed.");
    $.validator.addMethod("filesize", function (value, element, param) {
        if (element.files.length > 0) {
            return element.files[0].size <= param;
        }
        return true;
    }, "File size must be less than 2 MB.");

    // Add custom validation for file type
    $.validator.addMethod("filetype", function (value, element, param) {
        if (element.files.length > 0) {
            const allowedTypes = param.split(','); // Allowed MIME types
            const fileName = element.files[0].name.toLowerCase();
            const fileExtension = fileName.split('.').pop();
            return ['csv', 'xls', 'xlsx'].includes(fileExtension);
        }
        return true;
    }, "Only CSV or Excel files are allowed.");

    $('input[type="number"]').on('keyup', function (e) {
        if($(this).attr('id') != 'contact_no' && !$(this).hasClass("decimal"))
            $(this).val(parseInt($(this).val()));
    });

    // custom validation for reports page date criteria
    $.validator.addMethod("requiredIfDate", function (value, element) {
        const from = $('#from_date').val().trim();
        const to = $('#to_date').val().trim();
        if (from || to) {
            return $.trim(value) !== "";
        }
        return true;
    }, "Please select Date Criteria when From/To Date is filled.");

    $.validator.addMethod("customDate", function (value, element) {
        if ($.trim(value) === "") return true; // allow empty
        return /^\d{2}-\d{2}-\d{4}$/.test(value);
    }, "Please enter a valid date in DD-MM-YYYY format");

    // Custom method: To Date >= From Date
    $.validator.addMethod("greaterThanOrEqual", function (value, element, params) {
        if ($.trim(value) === "" || $.trim($(params).val()) === "") return true;
    
        var fromParts = $(params).val().split("-");
        var toParts = value.split("-");
    
        var fromDate = new Date(fromParts[2], fromParts[1] - 1, fromParts[0]);
        var toDate = new Date(toParts[2], toParts[1] - 1, toParts[0]);
    
        return toDate >= fromDate;
    }, "To Date must be greater than or equal to From Date");

    
    // Add a custom validation method
    $.validator.addMethod("greaterThanUnits", function (value, element) {
        let units = parseFloat($('#electricity_consumption_in_units').val());
        let billAmount = parseFloat(value);

        // Return true if valid; false otherwise
        return this.optional(element) || (billAmount > units); 
    }, "Bill Amount must be greater than Units."); // Custom error message

    $('input[type="number"]').on('keypress', function (e) {
        if(!$(this).hasClass("decimal")){
            if (e.key === '.' || e.key === 'e' || e.key === 'E') {
                e.preventDefault();
            }
        } else {
            const value = $(this).val();
            if ((e.key < '0' || e.key > '9') && e.key !== '.') {
                e.preventDefault();
                return;
            }
            if (e.key === '.' && value.indexOf('.') !== -1) {
                e.preventDefault();
                return;
            }
            if (value.indexOf('.') !== -1 && value.split('.')[1].length >= 2) {
                e.preventDefault();
            }
        }
    });

    $(document).on('keypress','.lettersonly', function (e) {
        if (!/[a-zA-Z\s]/.test(String.fromCharCode(e.which))) {
            e.preventDefault();
        }
    });

    // $(document).on('keypress', '.capsonly', function (e) {
    //     if (!/[A-Z0-9\s]/.test(String.fromCharCode(e.which))) {
    //         e.preventDefault();
    //     }
    // });

    $(document).on('input', '.capsonly, .alphanumeric', function () {
        let value = $(this).val();
    
        if ($(this).hasClass('capsonly')) {
            // Allow only letters & spaces, convert to uppercase
            value = value.replace(/[^a-zA-Z\s]/g, '').toUpperCase();
        }
    
        if ($(this).hasClass('alphanumeric')) {
            // Allow only letters, numbers & spaces, convert to uppercase
            value = value.replace(/[^a-zA-Z0-9\s]/g, '').toUpperCase();
        }
    
        $(this).val(value);
    });
    
    

    $(document).on('keypress','.alphanumeric', function (e) {
        if (!/^[\w\s]+$/.test(String.fromCharCode(e.which))) {
            e.preventDefault();
        }
    });

    $('.length_15').on('input', function () {
        var maxLength = 15;
        if ($(this).val().length > maxLength) {
            $(this).val($(this).val().slice(0, maxLength));
        }
    });

    $('.length_8').on('input', function () {
        var maxLength = 8;
        if ($(this).val().length > maxLength) {
            $(this).val($(this).val().slice(0, maxLength));
        }
    });

    $('.length_7').on('input', function () {
        var maxLength = 7;
        if ($(this).val().length > maxLength) {
            $(this).val($(this).val().slice(0, maxLength));
        }
    });
    
    $(document).on('change', '.file-validate', async function (event) {
        var tkn = $('input[name="_token"]').val();
        const files = event.target.files;
        let file_name = $(this).attr('name');
        if (!files.length) return;
        $(`label[id="${file_name}-error"]`).text('');
    
        const allowedExtensions = $(this).data('ext').split(',').map(ext => ext.trim().toLowerCase());
        const allowedMimeTypes = {
            'png': 'image/png', 'jpg': 'image/jpeg',
            'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'doc': 'application/msword',
            'xls': 'application/vnd.ms-excel', 'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xlsx': 'application/vnd.ms-excel', 'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv': 'text/csv', 'pdf': 'application/pdf', 'eml': 'message/rfc822'
        };
        const formData = new FormData();
        formData.append('_token',tkn);
        formData.append('process',file_name);
    
        for (let file of files) {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const fileMimeType = file.type;
            console.log(fileExtension);
            console.log(fileMimeType);
            
            
            if (!allowedExtensions.includes(fileExtension)) {
                $(`label[id="${file_name}-error"]`).text(`Invalid file type! Allowed: ${allowedExtensions.join(', ').toUpperCase()}.`);
                // alert('Invalid file type! Allowed: ' + allowedExtensions.join(', '));
                event.target.value = '';
                return;
            }
            
            if (allowedMimeTypes[fileExtension] && allowedMimeTypes[fileExtension] !== fileMimeType) {
                $(`label[id="${file_name}-error"]`).text(`Invalid file format! Please select a valid ${fileExtension.toUpperCase()} file.`);
                // alert('Invalid file format! Please select a valid file.');
                event.target.value = '';
                return;
            }
            
            const magicBytes = await readMagicBytes(file);
            console.log(magicBytes);
            
            if (!validateFileSignature(magicBytes, fileExtension)) {
                $(`label[id="${file_name}-error"]`).text(`Invalid file! The file type does not match its extension.`);
                // alert('Invalid file! This file type does not match its extension.');
                event.target.value = '';
                return;
            }
            // $.ajax({
            //     url: '/file-validation',
            //     type: 'POST',
            //     data: formData,
            //     processData:false,
            //     contentType:false,
            //     success: function(response) {
            //         formData.append('files[]',file);
            //     },
            //     error: function(xhr) {
            //         alert(xhr.responseJSON.error);
            //     }
            // });
        }
        
    });
    
    // Function to Read Magic Bytes
    async function readMagicBytes(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = function (event) {
                if (event.target.readyState === FileReader.DONE) {
                    const uint8Array = new Uint8Array(event.target.result);
                    const hexSignature = Array.from(uint8Array).slice(0, 4).map(byte => byte.toString(16).padStart(2, '0')).join('');
                    resolve(hexSignature);
                }
            };
            reader.readAsArrayBuffer(file.slice(0, 4));
        });
    }
    
    // Function to Validate File Signature
    function validateFileSignature(magicBytes, extension) {
        const fileSignatures = {
            'pdf': '25504446', 'doc': 'd0cf11e0', 'docx': '504b0304',
            'jpg': 'ffd8ffe0', 'png': '89504e47', 'xls': 'd0cf11e0',
            'xlsx': '504b0304', 'csv': '446f6375', 'eml': ''
        };    
        return fileSignatures[extension] ? fileSignatures[extension] === magicBytes : true;
    }


      // Show button when scrolled down 100px
    
    // document.addEventListener('change', function (e) {
    //     const input = e.target.closest('.file-validate');
    //     if (!input) return;
    
    //     const allowedExtensions = input.dataset.ext.split(',').map(ext => ext.trim().toLowerCase());
    //     const allowedMimeTypes = {
    //         'png': 'image/png',
    //         'jpg': 'image/jpeg',
    //         'jpeg': 'image/jpeg',
    //         'pdf': 'application/pdf',
    //         'doc': 'application/msword',
    //         'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    //         'xls': 'application/vnd.ms-excel',
    //         'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'csv': 'text/csv'
    //     };
    //     const maxFileSize = 5 * 1024 * 1024; // 5 MB
    //     const files = input.files;
    //     const errorLabel = document.getElementById(input.name + '-error');
    
    //     if (!files.length) return;
    //     if (errorLabel) errorLabel.textContent = ''; // Clear previous errors
    
    //     for (const file of files) {
    //         const name = file.name;
    //         const size = file.size;
    //         const mime = file.type;
    
    //         // ✅ 1. Reject multiple dots in filename
    //         if (name.split('.').length !== 2) {
    //             showError(errorLabel, 'Invalid filename! Only one dot allowed (e.g., file.pdf).', input);
    //             return;
    //         }
    
    //         // ✅ 2. Validate extension
    //         const fileExtension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
    //         if (!allowedExtensions.includes(fileExtension)) {
    //             showError(errorLabel, `Invalid file type! Allowed: ${allowedExtensions.join(', ').toUpperCase()}.`, input);
    //             return;
    //         }
    
    //         // ✅ 3. Validate size
    //         if (size > maxFileSize) {
    //             showError(errorLabel, `File too large! Max allowed size is ${maxFileSize / (1024 * 1024)} MB.`, input);
    //             return;
    //         }
    
    //         // ✅ 4. Validate MIME type (UX only)
    //         if (allowedMimeTypes[fileExtension] && allowedMimeTypes[fileExtension] !== mime) {
    //             showError(errorLabel, `File format mismatch! Please upload a valid ${fileExtension.toUpperCase()} file.`, input);
    //             return;
    //         }
    
    //         // ✅ 5. Magic bytes check (async)
    //         validateMagicBytes(file, fileExtension, (isValid) => {
    //             if (!isValid) {
    //                 showError(errorLabel, 'Invalid file! File content does not match its extension.', input);
    //             }
    //         });
    //     }
    // });
    
    // function showError(label, message, input) {
    //     if (label) label.textContent = message;
    //     input.value = '';
    // }
    
    // // ✅ Magic Bytes Validator
    // async function validateMagicBytes(file, ext, callback) {
    //     const signatures = {
    //         'pdf': ['25504446'], // %PDF
    //         'jpg': ['ffd8ff'],
    //         'jpeg': ['ffd8ff'],
    //         'png': ['89504e47'],
    //         'doc': ['d0cf11e0'],
    //         'docx': ['504b0304'],
    //         'xls': ['d0cf11e0'],
    //         'xlsx': ['504b0304'],
    //     };
    
    //     if (!signatures[ext]) {
    //         callback(true);
    //         return;
    //     }
    
    //     const slice = file.slice(0, 8);
    //     const buffer = await slice.arrayBuffer();
    //     const bytes = Array.from(new Uint8Array(buffer))
    //         .map(b => b.toString(16).padStart(2, '0'))
    //         .join('');
    
    //     const expected = signatures[ext];
    //     const match = expected.some(sig => bytes.startsWith(sig));
    //     callback(match);
    // }
    

    //   window.onscroll = function() {
    //     const btn = document.getElementById("backToTopBtn");
    //     if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    //         btn.style.display = "block";
    //     } else {
    //         btn.style.display = "none";
    //     }
    // };

    // // Scroll to top when clicked
    // document.getElementById("backToTopBtn").addEventListener("click", function() {
    //     window.scrollTo({ top: 0, behavior: 'smooth' });
    // });
    
});