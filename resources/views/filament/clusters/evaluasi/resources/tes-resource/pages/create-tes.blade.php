<div>
<x-filament-panels::page>
    <!-- Custom Styles from HTML -->
    <style>
        /* Custom Radio */
        .correct-radio:checked+div {
            border-color: #10B981;
            background-color: #ECFDF5;
        }

        .correct-radio:checked+div .check-icon {
            display: block;
        }
    </style>

    <!-- TABS NAVIGATION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8">
                <button class="border-primary-600 text-primary-600 py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors whitespace-nowrap cursor-default">
                    <x-heroicon-o-list-bullet class="w-5 h-5 mr-2" /> Setup Soal
                </button>
            </nav>
        </div>
    </div>

    <!-- CONTENT: SETUP SOAL -->
    <div>
        {{ $this->form }}
    </div>

</x-filament-panels::page>
</div>
