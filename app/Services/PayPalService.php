<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Log;

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
        Log::info("Credentials");
        Log::info($credentials);
        return "Basic {$credentials}";
    }
}