<?php

namespace App\Services\Donation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class DonationController extends Controller
{
    public function generatePayment(Request $request): JsonResponse
    {
        $amount = $request->input('amount', 100);

        $public_key = trim(env('LIQPAY_PUBLIC_KEY'));
        $private_key = trim(env('LIQPAY_PRIVATE_KEY'));

        if (empty($public_key) || empty($private_key)) {
            return response()->json(['error' => 'Ключі LiqPay не налаштовані на сервері'], 500);
        }

        $json_string = json_encode([
            'version' => 3,
            'public_key' => $public_key,
            'action' => 'pay',
            'amount' => $amount,
            'currency' => 'UAH',
            'description' => 'Благодійний внесок для AdoptMe Dnipro',
            'order_id' => uniqid('donate_'),
            'sandbox' => 1
        ]);

        $data = base64_encode($json_string);
        $signature = base64_encode(sha1($private_key . $data . $private_key, true));

        return response()->json([
            'data' => $data,
            'signature' => $signature
        ]);
    }
}