<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ReportExportService
{
    public function dailyRevenuePdf(string $date)
    {
        $payload = $this->dailyRevenuePayload($date);

        $pdf = Pdf::loadView('reports.daily-pdf', $payload)->setPaper('a4', 'portrait');

        return $pdf->download('daily-revenue-'.$date.'.pdf');
    }

    public function dailyRevenueXlsx(string $date)
    {
        $payload = $this->dailyRevenuePayload($date);

        $dir = storage_path('app/tmp');
        if (! is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $path = $dir.DIRECTORY_SEPARATOR.'daily-revenue-'.$date.'-'.uniqid().'.xlsx';

        $writer = new Writer();
        $writer->openToFile($path);

        $writer->addRow(Row::fromValues(['Daily Revenue Report', $date]));
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValues(['Total Rooms', $payload['totalRooms']]));
        $writer->addRow(Row::fromValues(['Occupied Rooms', $payload['occupiedRooms']]));
        $writer->addRow(Row::fromValues(['Occupancy Rate (%)', $payload['occupancyRate']]));
        $writer->addRow(Row::fromValues(['Room Revenue (Day)', $payload['roomRevenueForDay']]));
        $writer->addRow(Row::fromValues(['ADR', $payload['adr']]));
        $writer->addRow(Row::fromValues(['RevPAR', $payload['revpar']]));
        $writer->addRow(Row::fromValues(['Daily Payments Revenue', $payload['dailyRevenue']]));
        $writer->addRow(Row::fromValues(['Outstanding Balances', $payload['outstandingBalance']]));
        $writer->addRow(Row::fromValues([]));

        $writer->addRow(Row::fromValues(['Payments']));
        $writer->addRow(Row::fromValues(['Reference', 'Booking', 'Customer', 'Method', 'Amount', 'Paid At']));
        foreach ($payload['dailyPayments'] as $payment) {
            $writer->addRow(Row::fromValues([
                $payment->payment_reference,
                $payment->booking->booking_number,
                $payment->booking->customer->full_name,
                $payment->method->value,
                (string) $payment->amount,
                optional($payment->paid_at)->format('Y-m-d H:i'),
            ]));
        }

        $writer->close();

        return response()->download($path, 'daily-revenue-'.$date.'.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * @return array<string, mixed>
     */
    private function dailyRevenuePayload(string $date): array
    {
        $day = Carbon::parse($date)->startOfDay();
        $start = $day->copy()->startOfDay();
        $end = $day->copy()->endOfDay();

        $totalRooms = (int) Room::query()->count();

        $occupiedRooms = (int) Booking::query()
            ->whereIn('status', [BookingStatus::CheckedIn->value, BookingStatus::CheckedOut->value])
            ->whereDate('check_in_date', '<=', $day->toDateString())
            ->whereDate('check_out_date', '>', $day->toDateString())
            ->whereNotNull('room_id')
            ->distinct('room_id')
            ->count('room_id');

        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        $dailyPayments = Payment::query()
            ->with(['booking.customer'])
            ->whereNot('status', PaymentStatus::Cancelled->value)
            ->whereBetween('paid_at', [$start, $end])
            ->latest()
            ->get();

        $dailyRevenue = (float) $dailyPayments->sum('amount');

        $roomRevenueForDay = (float) Booking::query()
            ->whereIn('status', [BookingStatus::CheckedIn->value, BookingStatus::CheckedOut->value])
            ->whereDate('check_in_date', '<=', $day->toDateString())
            ->whereDate('check_out_date', '>', $day->toDateString())
            ->sum(DB::raw('rate_per_night'));

        $adr = $occupiedRooms > 0 ? round($roomRevenueForDay / $occupiedRooms, 2) : 0;
        $revpar = $totalRooms > 0 ? round($roomRevenueForDay / $totalRooms, 2) : 0;

        $outstandingBalance = (float) Booking::query()
            ->whereNotIn('status', [BookingStatus::Cancelled->value])
            ->sum(DB::raw('balance_amount'));

        return [
            'date' => $day,
            'totalRooms' => $totalRooms,
            'occupiedRooms' => $occupiedRooms,
            'occupancyRate' => $occupancyRate,
            'dailyRevenue' => round($dailyRevenue, 2),
            'roomRevenueForDay' => round($roomRevenueForDay, 2),
            'adr' => $adr,
            'revpar' => $revpar,
            'outstandingBalance' => round($outstandingBalance, 2),
            'dailyPayments' => $dailyPayments,
        ];
    }
}

