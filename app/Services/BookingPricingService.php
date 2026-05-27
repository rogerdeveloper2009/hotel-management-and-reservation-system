<?php

namespace App\Services;

class BookingPricingService
{
    /**
     * @return array{subtotal:string, tax_amount:string, total_amount:string, balance_amount:string, nights:int}
     */
    public function calculate(
        string $checkInDate,
        string $checkOutDate,
        float $ratePerNight,
        float $discountAmount = 0,
        float $extraServicesAmount = 0,
        float $taxRatePercent = 0,
        float $paidAmount = 0,
    ): array {
        $in = new \DateTimeImmutable($checkInDate);
        $out = new \DateTimeImmutable($checkOutDate);
        $nights = max(1, (int) $in->diff($out)->days);

        $subtotal = round($ratePerNight * $nights, 2);
        $taxable = max(0, $subtotal - $discountAmount + $extraServicesAmount);
        $taxAmount = round($taxable * ($taxRatePercent / 100), 2);
        $total = round($taxable + $taxAmount, 2);
        $balance = round(max(0, $total - $paidAmount), 2);

        return [
            'nights' => $nights,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'total_amount' => number_format($total, 2, '.', ''),
            'balance_amount' => number_format($balance, 2, '.', ''),
        ];
    }
}

