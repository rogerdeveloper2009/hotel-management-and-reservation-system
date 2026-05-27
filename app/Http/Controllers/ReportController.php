<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private ReportExportService $exports)
    {
    }

    public function index(Request $request): View
    {
        $date = Carbon::parse($request->query('date', now()->toDateString()))->startOfDay();
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $totalRooms = (int) Room::query()->count();

        $occupiedRooms = (int) Booking::query()
            ->whereIn('status', [BookingStatus::CheckedIn->value, BookingStatus::CheckedOut->value])
            ->whereDate('check_in_date', '<=', $date->toDateString())
            ->whereDate('check_out_date', '>', $date->toDateString())
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
            ->whereDate('check_in_date', '<=', $date->toDateString())
            ->whereDate('check_out_date', '>', $date->toDateString())
            ->sum(DB::raw('rate_per_night'));

        $adr = $occupiedRooms > 0 ? round($roomRevenueForDay / $occupiedRooms, 2) : 0;
        $revpar = $totalRooms > 0 ? round($roomRevenueForDay / $totalRooms, 2) : 0;

        $outstandingBalance = (float) Booking::query()
            ->whereNotIn('status', [BookingStatus::Cancelled->value])
            ->sum(DB::raw('balance_amount'));

        return view('reports.index', [
            'date' => $date,
            'totalRooms' => $totalRooms,
            'occupiedRooms' => $occupiedRooms,
            'occupancyRate' => $occupancyRate,
            'dailyRevenue' => $dailyRevenue,
            'roomRevenueForDay' => $roomRevenueForDay,
            'adr' => $adr,
            'revpar' => $revpar,
            'outstandingBalance' => $outstandingBalance,
            'dailyPayments' => $dailyPayments,
        ]);
    }

    public function dailyPdf(Request $request)
    {
        $date = Carbon::parse($request->query('date', now()->toDateString()))->toDateString();

        return $this->exports->dailyRevenuePdf($date);
    }

    public function dailyExcel(Request $request)
    {
        $date = Carbon::parse($request->query('date', now()->toDateString()))->toDateString();

        return $this->exports->dailyRevenueXlsx($date);
    }
}

