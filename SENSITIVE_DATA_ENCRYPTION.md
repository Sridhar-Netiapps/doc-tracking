# Sensitive Data Encryption - Minimal Fix

## Overview
This implementation encrypts sensitive fields (account numbers, CIF IDs, customer names) in the HTML source code while still displaying them correctly in the UI. When you view page source, you'll see encrypted values, but the rendered page shows decrypted values.

## How It Works

### 1. Encryption in Controllers
Use the `encryptSensitive()` method to encrypt sensitive fields before passing data to views:

```php
// Encrypt sensitive fields in collections/models
$loan_document = $this->encryptSensitive($loan_document);
$gold_loan_document = $this->encryptSensitive($gold_loan_document);
```

This encrypts the following fields by default:
- `account_number`
- `cif_id`
- `customer_name`
- `email`
- `mobile_number`
- `phone`

### 2. Display in Views
Use the `@sensitive` Blade directive to mark sensitive fields. This keeps them encrypted in HTML source:

```blade
<!-- Before -->
<td>{{ $row->account_number }}</td>
<td>{{ $row->cif_id }}</td>
<td>{{ $row->customer_name }}</td>

<!-- After - encrypted in HTML source, decrypted on display -->
<td>{!! @sensitive($row->account_number) !!}</td>
<td>{!! @sensitive($row->cif_id) !!}</td>
<td>{!! @sensitive($row->customer_name) !!}</td>
```

**Note:** The `@sensitive` directive outputs HTML with a `data-encrypted` attribute. JavaScript automatically decrypts these values after page load.

## Files Created/Modified

1. **`app/Helpers/EncryptHelper.php`** - Helper class for encryption/decryption
2. **`app/Http/Controllers/EncryptsSensitiveData.php`** - Trait for encrypting sensitive data
3. **`app/Http/Controllers/DecryptController.php`** - API endpoint for client-side decryption
4. **`app/Http/Controllers/Controller.php`** - Added trait to base controller
5. **`app/Providers/AppServiceProvider.php`** - Registered `@sensitive` Blade directive
6. **`public/js/decrypt-sensitive.js`** - JavaScript for client-side decryption
7. **`routes/web.php`** - Added decrypt API endpoint

## Usage Examples

### In Controllers

```php
public function index()
{
    $documents = LoanDocument::all();
    
    // Encrypt sensitive fields before passing to view
    $documents = $this->encryptSensitive($documents);
    
    return view('documents.index', compact('documents'));
}
```

### Custom Fields to Encrypt

```php
// Encrypt only specific fields
$documents = $this->encryptSensitive($documents, ['account_number', 'cif_id']);
```

### In Views

```blade
@foreach($documents as $doc)
    <tr>
        <td>{!! @sensitive($doc->account_number) !!}</td>
        <td>{!! @sensitive($doc->cif_id) !!}</td>
        <td>{!! @sensitive($doc->customer_name) !!}</td>
        <!-- Non-sensitive fields don't need @sensitive -->
        <td>{{ $doc->branch_code }}</td>
        <td>{{ $doc->status }}</td>
    </tr>
@endforeach
```

## Security Benefits

- ✅ Sensitive data is encrypted in HTML source code
- ✅ Data appears correctly in the UI (decrypted client-side for display)
- ✅ Viewing page source shows encrypted strings, not plain text
- ✅ Minimal code changes required
- ✅ Only encrypts what you specify (not all data)
- ✅ Decryption happens client-side via authenticated API endpoint

## How It Works

1. **Controller**: Encrypts sensitive fields using `encryptSensitive()` method
2. **Blade Template**: Uses `@sensitive` directive which outputs encrypted value with `data-encrypted` attribute
3. **HTML Source**: Contains encrypted values (safe from view source inspection)
4. **JavaScript**: Automatically decrypts values after page load via `/api/decrypt` endpoint
5. **Display**: Users see decrypted values in the rendered page

## Testing

1. Visit a page that displays sensitive data
2. View page source (right-click → View Page Source or Ctrl+U)
3. Search for account numbers or CIF IDs
4. You should see encrypted strings (long base64-like strings starting with "eyJ") - NOT plain text
5. On the rendered page, data should display correctly (decrypted)
6. Open browser DevTools → Network tab to see decryption API calls

## Migration Checklist

To apply this to other controllers/views:

1. **In Controller**: Add `$this->encryptSensitive($data)` before passing to view
2. **In View**: Replace `{{ $field }}` with `{!! @sensitive($field) !!}` for sensitive fields

**Important**: Use `{!! !!}` (not `{{ }}`) because `@sensitive` outputs HTML.

Fields to encrypt:
- `account_number`
- `cif_id`
- `customer_name`
- `email` (if displayed)
- `mobile_number` / `phone`

