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
            if (!validateFileSignature(magicBytes, fileExtension)) {
                $(`label[id="${file_name}-error"]`).text(`Invalid file! The file type does not match its extension.`);
                // alert('Invalid file! This file type does not match its extension.');
                event.target.value = '';
                return;
            }
        formData.append('files[]',file);
        }
        // $.ajax({
        //     url: '/file-validation',
        //     type: 'POST',
        //     data: formData,
        //     processData:false,
        //     contentType:false,
        //     success: function(response) {
        //         // alert('Files validated successfully!');
        //     },
        //     error: function(xhr) {
        //         alert(xhr.responseJSON.error);
        //     }
        // });
    
        // alert('File(s) validated successfully!');
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
            'xlsx': '504b0304', 'csv': '', 'eml': ''
        };
    
        return fileSignatures[extension] ? fileSignatures[extension] === magicBytes : true;
    }
    
});