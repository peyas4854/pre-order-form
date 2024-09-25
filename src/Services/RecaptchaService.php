<?php

namespace Peyas\PreOrderForm\Services;
use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function recaptchaValidate($request,$ipAddress=null)
    {
        // Recaptcha validation logic
        $recaptcha = $request['recaptcha'];
        $secretKey = config('services.recaptcha.secret');

        // Verify the reCAPTCHA response with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptcha,
            'remoteip' => $ipAddress
        ]);

        $result = $response->json();
        if (!$result['success']) {
            return response()->json(['message' => 'Recaptcha validation failed'], 400);
        }
    }

}
