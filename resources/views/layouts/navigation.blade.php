<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/dashboard" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Samuel App" class="h-9 w-9 rounded-lg object-cover">
                        <span class="font-bold text-[#172554]">Samuel App</span>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <a href="/dashboard" class="relative px-3 py-2 rounded-md text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                    {{ __('Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</nav>