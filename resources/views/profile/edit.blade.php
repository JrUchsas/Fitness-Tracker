<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white tracking-tight leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl">
                <div class="max-w-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">Database Backup & Local Sync</h3>
                        <p class="text-xs text-slate-400 mt-1">Download your live cloud database file anytime to sync all online workouts to localhost.</p>
                    </div>
                    <a href="{{ route('export-db') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg transition duration-150 flex items-center gap-2 shrink-0">
                        💾 Download Backup (.sqlite)
                    </a>
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
