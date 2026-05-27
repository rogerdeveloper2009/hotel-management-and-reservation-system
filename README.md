# HotelOps (Internal Hotel Management System)

Internal hotel management dashboard and operations system built with:
- Laravel 12 (PHP 8.3+)
- MySQL (recommended) / SQLite (local)
- Tailwind CSS (no Bootstrap)
- Blade + Alpine.js
- Chart.js

This is **NOT** a public hotel website or customer-facing booking site.

## Setup

1) Install PHP dependencies:
```powershell
composer install
```

2) Configure `.env` (MySQL recommended):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelops
DB_USERNAME=root
DB_PASSWORD=
```

3) Migrate + seed:
```powershell
php artisan migrate:fresh --seed
```

4) Build Tailwind assets (no Vite required in this repo):
```powershell
npx tailwindcss -i .\resources\css\app.css -o .\public\assets\app.css --minify
```

5) Run the app:
```powershell
php artisan serve
```

## Default Logins (change immediately)

Seeded users (username login only), all with password `ChangeMe123!`:
- `superadmin` (Super Admin)
- `admin` (Admin)
- `reception` (Receptionist)
- `manager` (Manager)

## Modules

- Dashboard (live KPIs + charts)
- Room Types, Rooms (incl. images + amenities)
- Amenities
- Customers
- Reservations / Bookings (double-booking prevention)
- Check-in / Check-out
- Payments (RWF calculations)
- Reports (Daily revenue + PDF/XLSX export)
- Settings (basic)
- Staff Users (Super Admin only)
