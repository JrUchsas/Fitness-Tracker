<section class="space-y-5">
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
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="py-3 px-6 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 font-bold text-sm rounded-xl transition duration-150 cursor-pointer"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-5">
            @csrf
            @method('delete')

            <div>
                <h3 class="text-lg font-bold text-white">
                    {{ __('Are you sure you want to delete your account?') }}
                </h3>

                <p class="mt-1 text-xs text-slate-400">
                    {{ __('This action is permanent and cannot be undone. Please enter your password to confirm account deletion.') }}
                </p>
            </div>

            <div>
                <x-input-label for="password" value="{{ __('Confirm Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Enter your current password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" x-on:click="$dispatch('close')" class="py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl border border-slate-700 cursor-pointer">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="py-2.5 px-5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-600/20 cursor-pointer">
                    {{ __('Permanently Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
