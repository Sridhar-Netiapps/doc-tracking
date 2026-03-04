/**
 * Client-side decryption for sensitive data
 * This ensures encrypted values in HTML source are decrypted only for display
 */
(function() {
    'use strict';
    
    /**
     * Decrypt a Laravel-encrypted string using the server's decryption endpoint
     * This keeps the HTML source encrypted while displaying decrypted values
     */
    function decryptValue(encryptedValue, callback) {
        if (!encryptedValue || encryptedValue === '-' || encryptedValue.trim() === '') {
            callback(encryptedValue);
            return;
        }
        
        // Check if it's already decrypted (not encrypted format)
        if (!encryptedValue.startsWith('eyJ')) {
            callback(encryptedValue);
            return;
        }
        
        // Use jQuery to decrypt via server endpoint
        $.ajax({
            url: '/api/decrypt',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            data: JSON.stringify({ encrypted: encryptedValue }),
            success: function(data) {
                callback(data.decrypted || encryptedValue);
            },
            error: function(xhr, status, error) {
                console.error('Decryption error:', error);
                callback(encryptedValue); // Fallback to encrypted value
            }
        });
    }
    
    /**
     * Decrypt all elements with data-encrypted attribute
     */
    function decryptAllEncryptedElements() {
        const encryptedElements = document.querySelectorAll('[data-encrypted]');
        
        encryptedElements.forEach(function(element) {
            const encryptedValue = element.getAttribute('data-encrypted');
            const originalText = element.textContent.trim();
            
            // Only decrypt if content looks encrypted
            if (encryptedValue && encryptedValue.startsWith('eyJ')) {
                decryptValue(encryptedValue, function(decrypted) {
                    element.textContent = decrypted;
                    element.removeAttribute('data-encrypted');
                });
            } else if (element.textContent.trim() === encryptedValue) {
                // Element already has encrypted text, decrypt it
                decryptValue(originalText, function(decrypted) {
                    element.textContent = decrypted;
                });
            }
        });
    }
    
    /**
     * Initialize decryption when DOM is ready
     */
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', decryptAllEncryptedElements);
        } else {
            decryptAllEncryptedElements();
        }
        
        // Also handle dynamically added content
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    decryptAllEncryptedElements();
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    init();
})();

