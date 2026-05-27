@php
    $c = $customer;
    $countries = config('countries');

    $phoneFull = old('phone_number', $c?->phone_number ?? '');
    $emergencyFull = old('emergency_contact_phone', $c?->emergency_contact_phone ?? '');

    $phoneCode = old('phone_code', '');
    $phoneNumber = $phoneFull;
    $emergencyCode = old('emergency_contact_phone_code', '');
    $emergencyNumber = $emergencyFull;

    if ($phoneCode === '' && $phoneFull !== '') {
        foreach ($countries as $c2) {
            $cLen = strlen($c2['code']);
            if (str_starts_with($phoneFull, $c2['code'])) {
                $phoneCode = $c2['code'];
                $phoneNumber = substr($phoneFull, $cLen);
                break;
            }
        }
    }

    if ($emergencyCode === '' && $emergencyFull !== '') {
        foreach ($countries as $c2) {
            $cLen = strlen($c2['code']);
            if (str_starts_with($emergencyFull, $c2['code'])) {
                $emergencyCode = $c2['code'];
                $emergencyNumber = substr($emergencyFull, $cLen);
                break;
            }
        }
    }
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="full_name" value="Full Name" />
        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $c?->full_name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
    </div>

    <div>
        <x-input-label for="phone_code" value="Phone Number" />
        <div class="mt-1 flex gap-2">
            <select id="phone_code" name="phone_code" class="w-32 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">
                <option value="">Code</option>
                @foreach ($countries as $country)
                    <option value="{{ $country['code'] }}" @selected($phoneCode === $country['code'])>{{ $country['code'] }}</option>
                @endforeach
            </select>
            <x-text-input id="phone_number" name="phone_number" type="tel" inputmode="numeric" pattern="[0-9]+" class="block flex-1" placeholder="Phone number" :value="$phoneNumber" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        <x-input-error class="mt-2" :messages="$errors->get('phone_code')" />
    </div>

    <div>
        <x-input-label for="nationality" value="Nationality" />
        <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full" :value="old('nationality', $c?->nationality)" />
        <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
    </div>

    <div>
        <x-input-label for="passport_or_id" value="Passport/ID" />
        <x-text-input id="passport_or_id" name="passport_or_id" type="text" class="mt-1 block w-full" :value="old('passport_or_id', $c?->passport_or_id)" />
        <x-input-error class="mt-2" :messages="$errors->get('passport_or_id')" />
    </div>

    <div>
        <x-input-label for="address" value="Address" />
        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $c?->address)" />
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>

    <div>
        <x-input-label for="emergency_contact_name" value="Emergency Contact Name" />
        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" :value="old('emergency_contact_name', $c?->emergency_contact_name)" />
        <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_name')" />
    </div>

    <div>
        <x-input-label for="emergency_contact_phone_code" value="Emergency Contact Phone" />
        <div class="mt-1 flex gap-2">
            <select id="emergency_contact_phone_code" name="emergency_contact_phone_code" class="w-32 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">
                <option value="">Code</option>
                @foreach ($countries as $country)
                    <option value="{{ $country['code'] }}" @selected($emergencyCode === $country['code'])>{{ $country['code'] }}</option>
                @endforeach
            </select>
            <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" inputmode="numeric" pattern="[0-9]+" class="block flex-1" placeholder="Phone number" :value="$emergencyNumber" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_phone')" />
        <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_phone_code')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">{{ old('notes', $c?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>

@push('scripts')
<script>
(function() {
    function combinePhone(codeId, numberId) {
        const code = document.getElementById(codeId);
        const num = document.getElementById(numberId);
        if (!code || !num) return;
        num.addEventListener('input', function() { this.value = this.value.replace(/[^0-9]/g, ''); });
    }
    combinePhone('phone_code', 'phone_number');
    combinePhone('emergency_contact_phone_code', 'emergency_contact_phone');
})();
</script>
@endpush

