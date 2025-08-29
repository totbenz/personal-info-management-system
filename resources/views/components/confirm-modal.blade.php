<div id="confirmModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Action</h3>
            </div>

            <div class="px-6 py-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to <span id="confirmAction" class="font-medium text-gray-900"></span> this request?
                </p>
                <p class="text-xs text-gray-500 mt-2">This action cannot be undone.</p>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                <button id="cancelBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirmBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmModal(action, callback) {
        const modal = document.getElementById('confirmModal');
        const actionSpan = document.getElementById('confirmAction');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');

        // Set the action text
        actionSpan.textContent = action;

        // Show the modal
        modal.classList.remove('hidden');

        // Handle cancel
        const handleCancel = () => {
            modal.classList.add('hidden');
            cleanup();
        };

        // Handle confirm
        const handleConfirm = () => {
            modal.classList.add('hidden');
            cleanup();
            if (callback) callback();
        };

        // Cleanup function
        const cleanup = () => {
            cancelBtn.removeEventListener('click', handleCancel);
            confirmBtn.removeEventListener('click', handleConfirm);
            modal.removeEventListener('click', handleBackdropClick);
        };

        // Handle backdrop click
        const handleBackdropClick = (e) => {
            if (e.target === modal) {
                handleCancel();
            }
        };

        // Add event listeners
        cancelBtn.addEventListener('click', handleCancel);
        confirmBtn.addEventListener('click', handleConfirm);
        modal.addEventListener('click', handleBackdropClick);

        // Handle escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                handleCancel();
            }
        };
        document.addEventListener('keydown', handleEscape);

        // Cleanup escape key listener when modal is closed
        const originalCleanup = cleanup;
        cleanup = () => {
            originalCleanup();
            document.removeEventListener('keydown', handleEscape);
        };
    }
</script>
