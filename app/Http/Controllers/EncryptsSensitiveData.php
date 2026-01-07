<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;

trait EncryptsSensitiveData
{
    /**
     * Encrypt sensitive fields in data before passing to view
     * 
     * @param mixed $data - Collection, Model, or Array
     * @param array $fields - Fields to encrypt (default: common PII fields)
     * @return mixed
     */
    protected function encryptSensitive($data, array $fields = ['account_number', 'cif_id', 'customer_name', 'email', 'mobile_number', 'phone'])
    {
        if (is_null($data)) {
            return $data;
        }
        
        // Handle Collections
        if ($data instanceof \Illuminate\Support\Collection || $data instanceof \Illuminate\Contracts\Pagination\Paginator) {
            return $data->map(function ($item) use ($fields) {
                return $this->encryptSensitive($item, $fields);
            });
        }
        
        // Handle Models
        if (is_object($data) && method_exists($data, 'getAttributes')) {
            foreach ($fields as $field) {
                if (isset($data->$field) && !empty($data->$field)) {
                    $data->$field = EncryptHelper::encrypt($data->$field);
                }
            }
            return $data;
        }
        
        // Handle Arrays
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $data[$key] = $this->encryptSensitive($value, $fields);
                } elseif (in_array($key, $fields) && !empty($value)) {
                    $data[$key] = EncryptHelper::encrypt($value);
                }
            }
            return $data;
        }
        
        return $data;
    }
}

