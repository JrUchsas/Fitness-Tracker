<section class="space-y-5" x-data="{ openDeleteModal: @js($errors->userDeletion->isNotEmpty()) }">
    <header class="pb-4 border-b border-slate-800">
        <h3 class="text-lg font-bold text-rose-400 tracking-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            {{ __('Delete Fitness Account') }}
        </h3>

        <p class="text-xs text-slate-400 mt-1">
            {{ __('Once your account is deleted, all workout logs, performance statistics, and personal data will be permanently wiped.') }}
        </p>
    </header>

    <button
        type="button"
        @click="openDeleteModal = true"
        class="py-3 px-6 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 font-bold text-sm rounded-xl transition duration-150 cursor-pointer"
    >
        {{ __('Delete Account') }}
    </button>

    {{-- Delete Account Confirmation Modal Teleported to Body --}}
    <template x-teleport="body">
        <div
            x-show="openDeleteModal"
            x-cloak
            @click.self="openDeleteModal = false"
            @keydown.escape.window="openDeleteModal = false"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-950/90 overflow-y-auto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                @click.stop
                class="bg-slate-900 border border-slate-700 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5 relative z-10 text-left my-auto"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5 text-rose-400 font-bold text-lg">
                        <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ __('Are you sure you want to delete your account?') }}</span>
                    </div>
                    <button type="button" @click="openDeleteModal = false" class="text-slate-400 hover:text-white text-2xl leading-none p-1">&times;</button>
                </div>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    {{ __('This action is permanent and cannot be undone. All your workout records, personal bests, and biometric measurements will be deleted permanently. Please enter your password to confirm.') }}
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="delete_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                            {{ __('Confirm Current Password') }}
                        </label>

                        <input
                            id="delete_password"
                            name="password"
                            type="password"
                            class="block w-full rounded-xl border border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 text-sm py-3 px-3.5 shadow-sm transition duration-150"
                            placeholder="{{ __('Enter your current password') }}"
                            required
                            autocomplete="current-password"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                        <button
                            type="button"
                            @click="openDeleteModal = false"
                            class="py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl border border-slate-700 cursor-pointer"
                        >
                            {{ __('Cancel') }}
                        </button>

                        <button
                            type="submit"
                            class="py-2.5 px-5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-600/25 transition duration-150 cursor-pointer"
                        >
                            {{ __('Permanently Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</section>
