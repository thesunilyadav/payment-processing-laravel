<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;
use Illuminate\Http\Request;

class PayPalService
{
    use ConsumesExternalService;

    protected $base_uri;

    protected $clientID;

    protected $clientSecret;

    public function __construct()
    {
        $this->base_uri = config('services.paypal.base_uri');
        $this->clientID = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');

    }

    public function resolveAuthorization(&$quesryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        $credentials = base64_encode("{$this->clientID}:{$this->clientSecret}");
        return "Basic {$credentials}";
    }

    public function handlePayment(Request $request)
    {
        $order = $this->createOrder($request->value,$request->currency);
        $order = json_decode($order);
        $orderlinks = collect($order->links);
        $approve = $orderlinks->where('rel','approve')->first();
        session()->put('approvalId',$order->id);

        return redirect($approve->href);
    }

    public function handleApproval()
    {
        if(session()->has('approvalId'))
        {
            $approvalId = session()->get('approvalId');
            $payment = json_decode($this->capturePayment($approvalId));
            $name = $payment->payer->name->given_name;
            $payment = $payment->purchase_units[0]->payments->captures[0]->amount;
            $amount = $payment->value;
            $currency = $payment->currency_code;

            return redirect()->route('home')->withSuccess(['payment' => "Thanks, {$name}. We received your {$amount} {$currency} payment."]);        
        }

        return redirect()->route('home')->withErrors('Sorry, We can not proceed your payment. Please try again.');
    }

    public function createOrder($value, $currency)
    {
        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
            [],
            [
                'intent'=> "CAPTURE",
                'purchase_units' => [
                    0 => [
                        'amount'=>[
                            'currency_code' => strtoupper($currency),
                            'value' => round($value * $factor = $this->resolveFactor($currency)) / $factor,
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('approval'),
                    'cancel_url' => route('cancelled'),
                ],
            ],
            [],
            $isJsonRequest = true
        );
    }

    public function capturePayment($approvalId)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalId}/capture",
            [],
            [],
            [
                'Content-Type' => "application/json"
            ]
        );
    }

    public function resolveFactor($currency)
    {
        $zeroDecimalCurrencies = ['JPY'];
        if(in_array($currency, $zeroDecimalCurrencies)){
            return 1;
        }
        return 100;
    }
}