<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Resolvers\PaymentPlatformResolver;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');

        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    /**
     * get payment details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pay(Request $request)
    {
        $rules = [
            'value'=>'required| numeric',
            'currency'=>'required|exists:currencies,iso',
            'payment_platform'=>'required|exists:payment_platforms,id'
        ];
        dd($request->all());
        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);
        session()->put('paymentPlatformId',$request->payment_platform);
        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        if(session()->has('paymentPlatformId')){
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->has('paymentPlatformId'));
            return $paymentPlatform->handleApproval();
        }
        return redirect()->route('home')->withErrors("We cannot received your payment. Please try again later.");
    }

    public function cancelled()
    {
        
    }
}
