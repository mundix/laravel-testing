<?php

namespace App\Services;

class CurrencyService
{
    const RATE = [
        'usd' => [
            'eur' => 0.89
        ]
    ];

    public function convert($amount = 0, $currency_from = 'usd', $currency_to = 'eur' )
    {
         $rate = 0;
         if(isset(self::RATE[$currency_from])) {
             $rate = self::RATE[$currency_from][$currency_to] ?? 0;
         }

         return round($amount * $rate, 2);

    }
}