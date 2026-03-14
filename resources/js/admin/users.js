// resources/js/admin/users.js

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActionSelect = document.getElementById('bulk-action');
    const applyBulkActionBtn = document.getElementById('apply-bulk-action');
    const selectedCountSpan = document.getElementById('selected-count');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });
    }

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedCount;
        }
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === userCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < userCheckboxes.length;
        }
    }

    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const action = bulkActionSelect.value;
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.value);

            if (!action) {
                alert('Please select an action');
                return;
            }

            if (selectedIds.length === 0) {
                alert('Please select at least one user');
                return;
            }

            let confirmMessage = '';
            switch (action) {
                case 'activate':
                    confirmMessage = `Are you sure you want to activate ${selectedIds.length} user(s)?`;
                    break;
                case 'suspend':
                    confirmMessage = `Are you sure you want to suspend ${selectedIds.length} user(s)?`;
                    break;
                case 'delete':
                    confirmMessage = `Are you sure you want to delete ${selectedIds.length} user(s)? This action cannot be undone.`;
                    break;
            }

            if (confirm(confirmMessage)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = document.getElementById('bulk-action-form').action;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);

                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});