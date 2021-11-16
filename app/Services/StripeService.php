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
        $request->validate([
            'payment_method' =>"required"
        ]);
        $intent = json_decode($this->createIntent($request->value,$request->currency,$request->payment_method));
        session()->put('paymentIntentId',$intent->id);
        return redirect()->route('approval');
    }

    public function handleApproval()
    {
        if(session()->has('paymentIntentId'))
        {
            $paymentIntentId = session()->get('paymentIntentId');
            $confirmation = json_decode($this->confirmPayment($paymentIntentId));
            
            if($confirmation->status == "requires_action"){                
                $clientSecret = $confirmation->client_secret;
                return view('stripe.3d-secure',compact('clientSecret'));
            }

            if($confirmation->status == "succeeded"){                
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount / $this->resolveFactor($currency);

                return redirect()->route('home')->withSuccess(['payment' => "Thanks, {$name}. We received your {$amount} {$currency} payment."]);        
            }
        }

        return redirect()->route('home')->withErrors('Sorry, We can not confirm your payment. Please try again.');
    }

    public function capturePayment($approvalId)
    {
     
    }

    public function createIntent($value, $currency, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                "amount" => round($value * $factor = $this->resolveFactor($currency)),
                "currency"=> strtolower($currency),
                "payment_method" => $paymentMethod,
                "confirmation_method" => "manual",
                "description"=>"test desc",
                "shipping"=>[
                    "name" => auth()->user()->name ,
                    "address" => [
                        "city" => "Sacramento",
                        "line1" => "1002 Park Avenue",
                        "country" => "US",
                        "postal_code" => "95814",
                        "state" => "California",
                    ],
                ], 
            ]
        );
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
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