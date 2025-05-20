document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour normaliser les chaînes (enlève les accents)
    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    // Appliquer les filtres lors de la recherche
    document.getElementById('search').addEventListener('input', function () {
        applyFilters();
    });

    // Appliquer les filtres selon le statut
    document.getElementById('statusFilter').addEventListener('change', function () {
        applyFilters();
    });

    // Ajout des événements de suppression
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            deleteReservationRow(this);
        });
    });

    // Fonction de suppression d'une ligne de réservation
    function deleteReservationRow(button) {
        const row = button.closest('tr');
        const reservationId = row.querySelector('td:nth-child(1)').textContent;

        const confirmDelete = confirm(`Voulez-vous vraiment supprimer la réservation ${reservationId} ?`);
        if (confirmDelete) {
            row.remove();
            alert('Réservation supprimée.');
        }
    }

    // Fonction d'application des filtres
    function applyFilters() {
        const searchValue = normalizeString(document.getElementById('search').value);
        const statusValue = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#reservationTable tbody tr');

        rows.forEach(row => {
            const reservationId = normalizeString(row.querySelector('td:nth-child(1)').textContent);
            const userName = normalizeString(row.querySelector('td:nth-child(2)').textContent);
            const property = normalizeString(row.querySelector('td:nth-child(3)').textContent);
            const status = normalizeString(row.querySelector('td:nth-child(4)').textContent); // Status is in the 4th column

            const matchesSearch =
                reservationId.includes(searchValue) ||
                userName.includes(searchValue) ||
                property.includes(searchValue);

            let matchesStatus = false;
            if (statusValue === '') {
                matchesStatus = true; // Show all if no status filter is selected
            } else {
                matchesStatus = normalizeString(status) === normalizeString(statusValue === 'paid' ? 'payée' : 'non payée');
            }

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }
});
