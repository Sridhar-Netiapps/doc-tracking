<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecryptController extends Controller
{
    /**
     * Decrypt sensitive data for client-side display
     * This endpoint decrypts values that are encrypted in HTML source
     */
    public function decrypt(Request $request)
    {
        return response()->json([
            'message' => 'Deprecated endpoint. Use authorized server-rendered secure views for sensitive data.',
        ], 410);
    }
}
