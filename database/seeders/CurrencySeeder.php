<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // PayPal accepted currencies
        $currencies = array(
            "AUD" => "Australian Dollar",
            "BRL" => "Brazilian Real",
            "GBP" => "British Pound Sterling",
            "CAD" => "Canadian Dollar",
            "CNY" => "Chinese Yuan",
            "CZK" => "Czech Republic Koruna",
            "DKK" => "Danish Krone",
            "EUR" => "Euro",
            "HKD" => "Hong Kong Dollar",
            "HUF" => "Hungarian Forint",
            "ILS" => "Israeli New Sheqel",
            "JPY" => "Japanese Yen",
            "MYR" => "Malaysian Ringgit",
            "MXN" => "Mexican Peso",
            "TWD" => "New Taiwan Dollar",
            "NZD" => "New Zealand Dollar",
            "NOK" => "Norwegian Krone",
            "PHP" => "Philippine Peso",
            "PLN" => "Polish Zloty",
            "RUB" => "Russian Ruble",
            "SGD" => "Singapore Dollar",
            "SEK" => "Swedish Krona",
            "CHF" => "Swiss Franc",
            "THB" => "Thai Baht",
            "USD" => "US Dollar",
        ); 
        foreach($currencies as $key => $currency){
            Currency::create([
                'name'=> $currency,
                'iso'=> $key
            ]);
        }
    }
}
