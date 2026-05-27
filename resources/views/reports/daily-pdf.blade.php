<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Revenue Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .muted { color: #6b7280; }
        .h1 { font-size: 18px; font-weight: 700; margin: 0; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-top: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        th { font-size: 10px; text-transform: uppercase; letter-spacing: .08em; color: #6b7280; }
    </style>
</head>
<body>
    <div>
        <p class="h1">Daily Revenue Report</p>
        <p class="muted">Date: {{ $date->toDateString() }}</p>
    </div>

    <div class="card">
        <table>
            <tr><td>Total Rooms</td><td>{{ $totalRooms }}</td></tr>
            <tr><td>Occupied Rooms</td><td>{{ $occupiedRooms }}</td></tr>
            <tr><td>Occupancy Rate</td><td>{{ $occupancyRate }}%</td></tr>
            <tr><td>Room Revenue (Day)</td><td>{{ \App\Support\Money::formatRwf($roomRevenueForDay) }}</td></tr>
            <tr><td>ADR</td><td>{{ \App\Support\Money::formatRwf($adr) }}</td></tr>
            <tr><td>RevPAR</td><td>{{ \App\Support\Money::formatRwf($revpar) }}</td></tr>
            <tr><td>Daily Payments Revenue</td><td>{{ \App\Support\Money::formatRwf($dailyRevenue) }}</td></tr>
            <tr><td>Outstanding Balances</td><td>{{ \App\Support\Money::formatRwf($outstandingBalance) }}</td></tr>
        </table>
    </div>

    <div class="card">
        <p style="margin:0 0 8px 0; font-weight:700;">Payments</p>
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Booking</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dailyPayments as $payment)
                    <tr>
                        <td>{{ $payment->payment_reference }}</td>
                        <td>{{ $payment->booking->booking_number }}</td>
                        <td>{{ $payment->booking->customer->full_name }}</td>
                        <td>{{ str_replace('_', ' ', $payment->method->value) }}</td>
                        <td>{{ \App\Support\Money::formatRwf($payment->amount) }}</td>
                        <td>{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

