@auth
    @php
        $currentBaby = auth()->user()->currentBaby();
    @endphp
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-blue-100 shadow-[0_-2px_10px_rgba(0,0,0,0.04)] sm:hidden">
        <div class="grid grid-cols-5 text-center">
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex flex-col items-center gap-0.5 py-2.5 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h0a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" />
                </svg>
                <span class="text-[11px] font-medium">Home</span>
            </a>

            <a href="{{ route('babies.index') }}" wire:navigate
               class="flex flex-col items-center gap-0.5 py-2.5 {{ request()->routeIs('babies.index') || request()->routeIs('babies.create') ? 'text-blue-700' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span class="text-[11px] font-medium">Babies</span>
            </a>

            <a href="{{ $currentBaby ? route('babies.growth', $currentBaby) : route('babies.create') }}" wire:navigate
               class="flex flex-col items-center gap-0.5 py-2.5 {{ request()->routeIs('babies.growth') ? 'text-blue-700' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l4.5-4.5 4 4L21 4.5M21 4.5H15M21 4.5v6M4 19.5h16" />
                </svg>
                <span class="text-[11px] font-medium">Growth</span>
            </a>

            <a href="{{ $currentBaby ? route('babies.guide', $currentBaby) : route('babies.create') }}" wire:navigate
               class="flex flex-col items-center gap-0.5 py-2.5 {{ request()->routeIs('babies.guide') ? 'text-blue-700' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="text-[11px] font-medium">Guide</span>
            </a>

            <a href="{{ route('profile') }}" wire:navigate
               class="flex flex-col items-center gap-0.5 py-2.5 {{ request()->routeIs('profile') ? 'text-blue-700' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                </svg>
                <span class="text-[11px] font-medium">More</span>
            </a>
        </div>
    </nav>
@endauth
