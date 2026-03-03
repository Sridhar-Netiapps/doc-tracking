<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptHelper
{
    public static function generateSecureToken($documentId, $dtype, $field): string
    {
        $data = json_encode([
            'id' => $documentId,
            't' => $dtype,
            'f' => $field,
        ]);

        return bin2hex(Crypt::encryptString($data));
    }

    private static function normalizeSensitiveValue($value): string
    {
        if (is_null($value)) {
            return '';
        }

        $stringValue = (string) $value;
        $decrypted = self::decrypt($stringValue);

        return is_string($decrypted) ? trim($decrypted) : trim($stringValue);
    }

    public static function maskIdentifier($value, int $visibleDigits = 2): string
    {
        $value = self::normalizeSensitiveValue($value);

        if ($value === '' || $value === '-') {
            return '-';
        }

        $length = strlen($value);
        $visibleDigits = max(0, min($visibleDigits, $length));
        $maskedLength = $length - $visibleDigits;

        if ($maskedLength <= 0) {
            return str_repeat('X', $length);
        }

        return str_repeat('X', $maskedLength) . substr($value, -$visibleDigits);
    }

    public static function maskName($value): string
    {
        $value = self::normalizeSensitiveValue($value);

        if ($value === '' || $value === '-') {
            return '-';
        }

        $length = strlen($value);
        if ($length === 1) {
            return 'X';
        }

        return substr($value, 0, 1) . str_repeat('X', $length - 1);
    }

    public static function toEncryptedPayload($value): string
    {
        $value = is_null($value) ? '' : (string) $value;

        if ($value === '' || $value === '-') {
            return '';
        }

        try {
            Crypt::decryptString($value);
            return $value;
        } catch (\Exception $e) {
            return Crypt::encryptString($value);
        }
    }

    public static function encrypt($value)
    {
        if (empty($value) || is_null($value)) {
            return $value;
        }
        
        try {
            return Crypt::encryptString((string)$value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public static function decrypt($value)
    {
        if (empty($value) || is_null($value)) {
            return $value;
        }
        
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return original (might not be encrypted)
            return $value;
        }
    }

    public static function encryptSensitiveFields($data, array $fields = ['account_number', 'cif_id', 'customer_name'])
    {
        if (is_object($data) && method_exists($data, 'getAttributes')) {
            // It's a model
            foreach ($fields as $field) {
                if (isset($data->$field) && !empty($data->$field)) {
                    $data->$field = self::encrypt($data->$field);
                }
            }
        } elseif (is_array($data)) {
            // It's an array
            foreach ($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $data[$key] = self::encryptSensitiveFields($value, $fields);
                } elseif (in_array($key, $fields) && !empty($value)) {
                    $data[$key] = self::encrypt($value);
                }
            }
        }
        
        return $data;
    }

    public static function sensitiveOutput($value)
    {
        if (empty($value) || $value === '-') {
            return htmlspecialchars($value ?? '-', ENT_QUOTES, 'UTF-8');
        }

        $normalized = self::normalizeSensitiveValue($value);
        $masked = preg_match('/^[A-Za-z ]+$/', $normalized)
            ? self::maskName($normalized)
            : self::maskIdentifier($normalized);

        return htmlspecialchars($masked, ENT_QUOTES, 'UTF-8');
    }
}
