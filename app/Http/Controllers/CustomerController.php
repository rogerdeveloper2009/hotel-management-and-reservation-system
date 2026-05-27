<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $customers = Customer::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('full_name', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%")
                        ->orWhere('passport_or_id', 'like', "%{$q}%");
                });
            })
            ->withCount('bookings')
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('customers.index', compact('customers', 'q'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['phone_number'] = $this->mergePhoneCode($data, 'phone_code', 'phone_number');
        $data['emergency_contact_phone'] = $this->mergePhoneCode($data, 'emergency_contact_phone_code', 'emergency_contact_phone');
        unset($data['phone_code'], $data['emergency_contact_phone_code']);

        $customer = Customer::create($data);
        app(ActivityLogger::class)->log('customer.create', $customer, "Created customer {$customer->full_name}");

        return redirect()->route('customers.show', $customer)->with('success', 'Customer created.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['bookings' => fn ($q) => $q->latest()->limit(20)]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();
        $data['phone_number'] = $this->mergePhoneCode($data, 'phone_code', 'phone_number');
        $data['emergency_contact_phone'] = $this->mergePhoneCode($data, 'emergency_contact_phone_code', 'emergency_contact_phone');
        unset($data['phone_code'], $data['emergency_contact_phone_code']);

        $customer->update($data);
        app(ActivityLogger::class)->log('customer.update', $customer, "Updated customer {$customer->full_name}");

        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated.');
    }

    private function mergePhoneCode(array &$data, string $codeKey, string $numberKey): string
    {
        $code = trim($data[$codeKey] ?? '');
        $number = trim(preg_replace('/[^0-9]/', '', $data[$numberKey] ?? ''));
        if ($code !== '' && $number !== '') {
            return $code . $number;
        }
        return $number !== '' ? $number : ($code . $number);
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $name = $customer->full_name;
        $customer->delete();
        app(ActivityLogger::class)->log('customer.delete', $customer, "Deleted customer {$name}");

        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }
}

