<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $request->validate($rules);

        $paymentPlatform = resolve(PayPalService::class);
        
        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        $paymentPlatform = resolve(PayPalService::class);
        return $paymentPlatform->handleApproval();
    }

    public function cancelled()
    {
        return redirect()->route('home')->withErrors('You cancelled the payment');
    }
}
