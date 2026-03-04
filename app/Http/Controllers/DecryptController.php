<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DecryptController extends Controller
{
    /**
     * Decrypt sensitive data for client-side display
     * This endpoint decrypts values that are encrypted in HTML source
     */
    public function decrypt(Request $request)
    {
        $request->validate([
            'encrypted' => 'required|string'
        ]);
        
        try {
            $decrypted = Crypt::decryptString($request->encrypted);
            return response()->json(['decrypted' => $decrypted]);
        } catch (\Exception $e) {
            // If decryption fails, return original
            return response()->json(['decrypted' => $request->encrypted]);
        }
    }
}

