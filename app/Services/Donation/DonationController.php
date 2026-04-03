<?php

namespace App\Services\Donation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function generatePayment(Request $request)
    {
        $amount = $request->input('amount', 100);
        $public_key = env('LIQPAY_PUBLIC_KEY');
        $private_key = env('LIQPAY_PRIVATE_KEY');

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
        $signature = base64_encode(sha1($private_key . $data . $private_key, 1));

        return response()->json(compact('data', 'signature'));
    }
}