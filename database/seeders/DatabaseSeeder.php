<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PaymentPlatformSeeder;
use Database\Seeders\CurrencySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class,
            PaymentPlatformSeeder::class,
        ]);
    
    }
}
