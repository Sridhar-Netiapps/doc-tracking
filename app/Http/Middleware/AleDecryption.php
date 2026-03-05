<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use Symfony\Component\HttpFoundation\Response;

class AleDecryption
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('secure_req')) {
            return $next($request);
        }

        try {
            $secureRequest = (string) $request->input('secure_req');
            $outerBlob = json_decode(base64_decode($secureRequest), true);

            if (!is_array($outerBlob) || !isset($outerBlob['k'], $outerBlob['i'], $outerBlob['d'])) {
                return response()->json(['message' => 'Invalid secure request'], 422);
            }

            $privateKey = RSA::load(Storage::get('keys/private_key.pem'), config('app.private_key_passphrase'))
                ->withPadding(RSA::ENCRYPTION_OAEP)
                ->withHash('sha256')
                ->withMGFHash('sha256');

            $aesKeyRaw = $privateKey->decrypt(base64_decode($outerBlob['k']));
            if ($aesKeyRaw === false || strlen($aesKeyRaw) !== 32) {
                return response()->json(['message' => 'Invalid secure request'], 422);
            }

            $iv = base64_decode($outerBlob['i']);
            $cipherCombined = base64_decode($outerBlob['d']);
            if ($iv === false || strlen($iv) !== 12 || $cipherCombined === false || strlen($cipherCombined) <= 16) {
                return response()->json(['message' => 'Invalid secure request'], 422);
            }

            $ciphertext = substr($cipherCombined, 0, -16);
            $tag = substr($cipherCombined, -16);

            $aes = new AES('gcm');
            $aes->setKey($aesKeyRaw);
            $aes->setNonce($iv);
            $aes->setTag($tag);

            $decrypted = $aes->decrypt($ciphertext);
            if ($decrypted === false) {
                return response()->json(['message' => 'Invalid secure request'], 422);
            }

            $decryptedData = json_decode($decrypted, true);
            if (!is_array($decryptedData)) {
                return response()->json(['message' => 'Invalid secure request'], 422);
            }

            $request->request->remove('secure_req');
            if ($request->isJson()) {
                $request->json()->remove('secure_req');
            }

            $request->merge($decryptedData);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid secure request'], 422);
        }

        return $next($request);
    }
}
