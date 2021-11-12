<?php

namespace App\Resolvers;

use App\Models\PaymentPlatform;

class PaymentPlatformResolver
{
    protected $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function resolveService($paymentPlatFormId)
    {
        $name = strtolower($this->paymentPlatforms->firstWhere('id',$paymentPlatFormId)->name);
        $service = config("services.{$name}.class");
        if($service){
            return resolve($service);
        }
        throw new \Exception('The selected platform not available.');
    }
}
