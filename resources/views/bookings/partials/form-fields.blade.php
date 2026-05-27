@php
    $b = $booking;
    $preCustomerId = request()->query('customer_id');
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="customer_id" value="Customer" />
        <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
            @foreach ($customers as $customer)
                @php
                    $selected = old('customer_id', $b?->customer_id ?? $preCustomerId);
                @endphp
                <option value="{{ $customer->id }}" @selected($selected == $customer->id)>{{ $customer->full_name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
    </div>

    <div>
        <x-input-label for="status" value="Booking Status" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
            @foreach (['pending','confirmed','checked_in','checked_out','cancelled'] as $s)
                <option value="{{ $s }}" @selected(old('status', $b?->status->value ?? 'pending') === $s)>{{ str_replace('_', ' ', $s) }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <div>
        <x-input-label for="room_type_id" value="Room Type" />
        <select id="room_type_id" name="room_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}" @selected(old('room_type_id', $b?->room_type_id) == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('room_type_id')" />
    </div>

    <div>
        <x-input-label for="room_id" value="Room (optional)" />
        <select id="room_id" name="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">
            <option value="">Not assigned</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected(old('room_id', $b?->room_id) == $room->id)>{{ $room->room_number }} ({{ $room->roomType->name }})</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('room_id')" />
        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Assigning a room enforces double-booking prevention.</div>
    </div>

    <div>
        <x-input-label for="check_in_date" value="Check-in Date" />
        <x-text-input id="check_in_date" name="check_in_date" type="date" class="mt-1 block w-full" :value="old('check_in_date', $b?->check_in_date?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('check_in_date')" />
    </div>

    <div>
        <x-input-label for="check_out_date" value="Check-out Date" />
        <x-text-input id="check_out_date" name="check_out_date" type="date" class="mt-1 block w-full" :value="old('check_out_date', $b?->check_out_date?->format('Y-m-d') ?? now()->addDay()->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('check_out_date')" />
    </div>

    <div>
        <x-input-label for="adults" value="Adults" />
        <x-text-input id="adults" name="adults" type="number" class="mt-1 block w-full" :value="old('adults', $b?->adults ?? 1)" min="1" required />
        <x-input-error class="mt-2" :messages="$errors->get('adults')" />
    </div>

    <div>
        <x-input-label for="children" value="Children" />
        <x-text-input id="children" name="children" type="number" class="mt-1 block w-full" :value="old('children', $b?->children ?? 0)" min="0" />
        <x-input-error class="mt-2" :messages="$errors->get('children')" />
    </div>

    <div>
        <x-input-label for="rate_per_night" value="Rate per Night (RWF)" />
        <x-text-input id="rate_per_night" name="rate_per_night" type="number" step="0.01" class="mt-1 block w-full" :value="old('rate_per_night', $b?->rate_per_night)" />
        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to use the room or room type default rate.</div>
        <x-input-error class="mt-2" :messages="$errors->get('rate_per_night')" />
    </div>

    <div>
        <x-input-label for="tax_rate" value="Tax Rate (%)" />
        <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" class="mt-1 block w-full" :value="old('tax_rate', $b?->tax_rate ?? $defaultTaxRate ?? 0)" />
        <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
    </div>

    <div>
        <x-input-label for="discount_amount" value="Discount (RWF)" />
        <x-text-input id="discount_amount" name="discount_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('discount_amount', $b?->discount_amount ?? 0)" />
        <x-input-error class="mt-2" :messages="$errors->get('discount_amount')" />
    </div>

    <div>
        <x-input-label for="extra_services_amount" value="Extra Services (RWF)" />
        <x-text-input id="extra_services_amount" name="extra_services_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('extra_services_amount', $b?->extra_services_amount ?? 0)" />
        <x-input-error class="mt-2" :messages="$errors->get('extra_services_amount')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">{{ old('notes', $b?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>

