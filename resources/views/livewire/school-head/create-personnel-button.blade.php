<!-- Create Personnel Button for School Head -->
<div>
    <!-- Include Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div x-data>
        <!-- Button to trigger modal -->
        <button @click="$dispatch('open-create-personnel-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Personnel
        </button>

        <!-- Include the modal -->
        @include('livewire.school-head.create-personnel-modal')
    </div>

    <script>
    // Toast notification system
    document.addEventListener('DOMContentLoaded', () => {
        // Listen for toast events
        window.addEventListener('show-toast', (event) => {
            const toast = event.detail;
            const toastEl = document.createElement('div');
            toastEl.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
                toast.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toastEl.textContent = toast.message;
            document.body.appendChild(toastEl);

            setTimeout(() => {
                toastEl.remove();
            }, 3000);
        });

        // Listen for personnel created event
        window.addEventListener('personnel-created', () => {
            // Reload the page to show the new personnel
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    });
    </script>
</div>
