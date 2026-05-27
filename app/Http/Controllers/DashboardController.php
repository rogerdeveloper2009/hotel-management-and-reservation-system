<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\RoomStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRooms = (int) Room::query()->count();
        $availableRooms = (int) Room::query()->where('status', RoomStatus::Available->value)->count();
        $occupiedRooms = (int) Room::query()->where('status', RoomStatus::Occupied->value)->count();
        $reservedRooms = (int) Room::query()->where('status', RoomStatus::Reserved->value)->count();

        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        $days = 14;
        $from = now()->subDays($days - 1)->startOfDay();

        $bookingSeries = Booking::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd')
            ->all();

        $revenueSeries = Payment::query()
            ->whereNot('status', PaymentStatus::Cancelled->value)
            ->where('paid_at', '>=', $from)
            ->selectRaw('DATE(paid_at) as d, SUM(amount) as s')
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('s', 'd')
            ->all();

        $labels = collect(range(0, $days - 1))
            ->map(fn ($i) => $from->copy()->addDays($i)->toDateString());

        $bookingsChart = [
            'labels' => $labels,
            'data' => $labels->map(fn ($d) => (int) ($bookingSeries[$d] ?? 0)),
        ];

        $revenueChart = [
            'labels' => $labels,
            'data' => $labels->map(fn ($d) => (float) ($revenueSeries[$d] ?? 0)),
        ];

        $recentBookings = Booking::query()->with(['customer', 'room'])->latest()->limit(6)->get();
        $recentPayments = Payment::query()->with(['booking.customer'])->latest()->limit(6)->get();

        $today = now()->toDateString();
        $checkinsToday = Booking::query()
            ->whereDate('check_in_date', $today)
            ->whereIn('status', [BookingStatus::Pending->value, BookingStatus::Confirmed->value])
            ->count();

        $outstandingBalance = (float) Booking::query()
            ->whereNotIn('status', [BookingStatus::Cancelled->value])
            ->sum(DB::raw('balance_amount'));

        $mtdRevenue = (float) Payment::query()
            ->whereNot('status', PaymentStatus::Cancelled->value)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        return view('dashboard.index', [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'reservedRooms' => $reservedRooms,
            'occupancyRate' => $occupancyRate,
            'mtdRevenue' => $mtdRevenue,
            'outstandingBalance' => $outstandingBalance,
            'checkinsToday' => $checkinsToday,
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
            'bookingsChart' => $bookingsChart,
            'revenueChart' => $revenueChart,
        ]);
    }
}
