document.addEventListener('DOMContentLoaded', function () {
    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
    }

    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#offersTable tbody tr');

    function applyFilters() {
        const searchValue = normalizeString(searchInput.value);
        const statusValue = statusFilter.value;

        rows.forEach(row => {
            const offerId = normalizeString(row.cells[0].textContent);
            const offerName = normalizeString(row.cells[1].textContent);
            const nbPieces = normalizeString(row.cells[2].textContent);
            const disponibility = normalizeString(row.cells[3].textContent);
            const price = normalizeString(row.cells[4].textContent);
            const location = normalizeString(row.cells[5].textContent);
            const dateajout = normalizeString(row.cells[6].textContent);
            const surface = normalizeString(row.cells[7].textContent);
            const type = normalizeString(row.cells[8].textContent);
            const statusText = normalizeString(row.cells[9].textContent); // Get the text from the cell
            const owner = normalizeString(row.cells[10].textContent);


            const matchesSearch =
                offerId.includes(searchValue) ||
                offerName.includes(searchValue) ||
                nbPieces.includes(searchValue) ||
                disponibility.includes(searchValue) ||
                price.includes(searchValue) ||
                location.includes(searchValue) ||
                dateajout.includes(searchValue) ||
                surface.includes(searchValue) ||
                type.includes(searchValue) ||
                owner.includes(searchValue);

            let matchesStatus = false;
            if (statusValue === '') {
                matchesStatus = true; // Show all if no status filter is selected
            } else {
                matchesStatus = normalizeString(statusText) === normalizeString(statusValue);
            }

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    function deleteOfferRow(button) {
        const row = button.closest('tr');
        const offerId = row.cells[0].textContent;

        const confirmDelete = confirm(`Voulez-vous vraiment supprimer l'offre ${offerId} ?`);
        if (confirmDelete) {
            row.remove();
            alert('Offre supprimée.');
        }
    }

    searchInput.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            deleteOfferRow(this);
        });
    });
});
