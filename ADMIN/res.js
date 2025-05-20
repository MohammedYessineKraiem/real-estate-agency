document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour normaliser les chaînes (enlève les accents)
    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
    }

    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#reservationTable tbody tr');

    function applyFilters() {
        const searchValue = normalizeString(searchInput.value);
        const statusValue = statusFilter.value; // "paid", "unpaid", or ""

        rows.forEach(row => {
            const reservationId = normalizeString(row.cells[0].textContent);
            const userName = normalizeString(row.cells[1].textContent);
            const propertyName = normalizeString(row.cells[2].textContent);
            const startDate = normalizeString(row.cells[3].textContent);
            const endDate = normalizeString(row.cells[4].textContent);
            const paymentStatus = normalizeString(row.cells[5].textContent); // Get text
            const amount = normalizeString(row.cells[6].textContent);
            const paymentMethod = normalizeString(row.cells[7].textContent);


            const matchesSearch =
                reservationId.includes(searchValue) ||
                userName.includes(searchValue) ||
                propertyName.includes(searchValue) ||
                startDate.includes(searchValue) ||
                endDate.includes(searchValue) ||
                amount.includes(searchValue) ||
                paymentMethod.includes(searchValue);

            let matchesStatus = false;
            if (statusValue === '') {
                matchesStatus = true; // Show all
            } else {
                matchesStatus = paymentStatus === (statusValue === 'paid' ? 'payée' : 'non payée');
            }

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    function deleteReservationRow(button) {
        const row = button.closest('tr');
        const reservationId = row.querySelector('td:nth-child(1)').textContent;

        const confirmDelete = confirm(`Voulez-vous vraiment supprimer la réservation ${reservationId} ?`);
        if (confirmDelete) {
            row.remove();
            alert('Réservation supprimée.');
        }
    }

    searchInput.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            deleteReservationRow(this);
        });
    });
});
