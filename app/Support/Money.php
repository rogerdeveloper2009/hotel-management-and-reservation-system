<?php

namespace App\Support;

class Money
{
    public static function formatRwf(null|int|float|string $amount): string
    {
        if ($amount === null || $amount === '') {
            return 'RWF 0';
        }

        $value = (float) $amount;
        $decimals = (abs($value - round($value)) < 0.00001) ? 0 : 2;

        return 'RWF '.number_format($value, $decimals, '.', ',');
    }
}

