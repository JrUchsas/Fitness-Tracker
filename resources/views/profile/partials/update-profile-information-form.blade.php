<section>
    <header class="mb-6 pb-4 border-b border-slate-800">
        <h3 class="text-lg font-bold text-white tracking-tight">
            {{ __('Profile & Personal Information') }}
        </h3>
        <p class="text-xs text-slate-400 mt-1">
            {{ __("Update your account's profile details, personal metrics (gender, age, body weight), and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Name & Email (2 cols) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-slate-300">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-xs text-indigo-400 hover:text-indigo-300">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1.5 font-semibold text-xs text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Extra Personal Info: Gender, Age, Weight (3 cols) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-2">
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1.5 block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150">
                    <option value="" {{ old('gender', $user->gender) ? '' : 'selected' }}>Select Gender</option>
                    <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $user->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    <option value="Prefer not to say" {{ old('gender', $user->gender) === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                </select>
                <x-input-error class="mt-1" :messages="$errors->get('gender')" />
            </div>

            <div>
                <x-input-label for="age" :value="__('Age (years)')" />
                <x-text-input id="age" name="age" type="number" min="1" max="120" placeholder="e.g. 25" class="mt-1.5 block w-full" :value="old('age', $user->age)" />
                <x-input-error class="mt-1" :messages="$errors->get('age')" />
            </div>

            <div>
                <x-input-label for="weight_kg" :value="__('Body Weight (kg)')" />
                <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.1" min="1" max="500" placeholder="e.g. 70.5" class="mt-1.5 block w-full" :value="old('weight_kg', $user->weight_kg)" />
                <x-input-error class="mt-1" :messages="$errors->get('weight_kg')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
            <button type="submit" class="py-3 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/25 transition duration-150 cursor-pointer">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-400 flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Profile details updated successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>
