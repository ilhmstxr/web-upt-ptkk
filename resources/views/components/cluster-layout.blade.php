<div class="flex h-full w-full flex-col gap-6 md:flex-row">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 md:w-64">
        <nav class="flex flex-col gap-2">
            {{-- Breadcrumbs (Optional, if needed here or handled by page) --}}
            <div class="mb-4 text-sm text-gray-500">
                {{ $breadcrumbs ?? '' }}
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
            
            {{-- Description --}}
            @if(isset($description))
                <p class="mt-2 text-sm text-gray-400">
                    {{ $description }}
                </p>
            @endif

            {{-- Menu Items --}}
            <div class="mt-8 flex flex-col gap-1">
                @foreach ($menu as $item)
                    <a href="{{ $item['url'] }}"
                        class="@if ($item['active']) bg-gray-800 text-primary-400 @else text-gray-400 hover:bg-gray-800 hover:text-white @endif flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors">
                        @if (isset($item['icon']))
                            <x-filament::icon
                                :icon="$item['icon']"
                                class="h-5 w-5"
                            />
                        @endif
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>
</div>
