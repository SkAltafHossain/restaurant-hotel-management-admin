<div id="{{ $id ?? 'modal' }}" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-{{ $gradientFrom ?? 'blue' }}-600 to-{{ $gradientTo ?? 'indigo' }}-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">{{ $title ?? 'Modal' }}</h2>
                <button onclick="closeModal('{{ $id ?? 'modal' }}')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        {{ $slot ?? '' }}
    </div>
</div>
