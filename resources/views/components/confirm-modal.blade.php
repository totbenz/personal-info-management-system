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
                <button id="confirmBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-transparent rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Yes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global variables for modal state
    window.currentConfirmAction = null;

    function showConfirmModal(action, callback) {
        console.log('showConfirmModal called with action:', action);

        const modal = document.getElementById('confirmModal');
        const actionSpan = document.getElementById('confirmAction');

        if (!modal || !actionSpan) {
            console.error('Modal elements not found');
            // Fallback to browser confirm
            if (confirm(`Are you sure you want to ${action} this request?`)) {
                callback();
            }
            return;
        }

        // Store the callback globally
        window.currentConfirmAction = callback;

        // Set the action text
        actionSpan.textContent = action;

        // Show the modal
        modal.classList.remove('hidden');
    }

    function handleConfirmModalCancel() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        window.currentConfirmAction = null;
    }

    function handleConfirmModalConfirm() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');

        const callback = window.currentConfirmAction;
        window.currentConfirmAction = null;

        if (callback && typeof callback === 'function') {
            setTimeout(callback, 50); // Small delay to ensure modal is hidden
        }
    }

    // Set up event listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');

        if (cancelBtn) {
            cancelBtn.addEventListener('click', handleConfirmModalCancel);
        }

        if (confirmBtn) {
            confirmBtn.addEventListener('click', handleConfirmModalConfirm);
        }

        if (modal) {
            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    handleConfirmModalCancel();
                }
            });
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                handleConfirmModalCancel();
            }
        });
    });
</script>
