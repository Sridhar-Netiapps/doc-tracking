<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptHelper
{

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

    public static function encryptSensitiveFields($data, array $fields = ['account_number', 'cif_id', 'customer_name', 'email', 'mobile_number'])
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
        if (empty($value) || $value === '-' || !is_string($value)) {
            return htmlspecialchars($value ?? '-', ENT_QUOTES, 'UTF-8');
        }
        
        $encrypted = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        return '<span data-encrypted="' . $encrypted . '">' . $encrypted . '</span>';
    }
}