document.addEventListener('DOMContentLoaded', function () {
    // Apply search filters
    document.getElementById('search').addEventListener('input', function() {
        applyFilters();
    });

    // Filter by user status
    document.getElementById('statusFilter').addEventListener('change', function() {
        applyFilters();
    });

    // Add event listeners for the delete buttons
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            deleteUserRow(this);
        });
    });

    // Example actions for user buttons (could be expanded)
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            alert('Modifier l\'utilisateur');
        });
    });

    document.querySelectorAll('.deactivate-btn').forEach(button => {
        button.addEventListener('click', function() {
            alert('Désactiver l\'utilisateur');
        });
    });
});

// Function to apply filters based on search value and status
function applyFilters() {
    const searchValue = document.getElementById('search').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;

    const rows = document.querySelectorAll('#userTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

        const matchesSearch = name.includes(searchValue) || email.includes(searchValue);
        const matchesStatus = statusValue === '' || status.includes(statusValue);

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Function to delete a user row from the table
function deleteUserRow(button) {
    const row = button.closest('tr');  // Find the row of the clicked button
    const userName = row.querySelector('td:nth-child(1)').textContent;

    // Confirm before deleting
    const confirmDelete = confirm(`Voulez-vous vraiment supprimer l'utilisateur ${userName}?`);
    if (confirmDelete) {
        row.remove();  // Remove the row from the table
        alert('Utilisateur supprimé.');
    }
}
