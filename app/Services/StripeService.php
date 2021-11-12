<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;
use Illuminate\Http\Request;

class StripeService
{
    use ConsumesExternalService;

    protected $base_uri;

    protected $key;

    protected $secret;

    public function __construct()
    {
        $this->base_uri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');

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
        return "Bearer {$this->secret}";
    }

    public function handlePayment(Request $request)
    {

    }

    public function handleApproval()
    {

    }

    public function capturePayment($approvalId)
    {
     
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