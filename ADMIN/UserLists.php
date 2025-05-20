<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd"; // <-- À adapter

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$sql = "SELECT id, prenom, name, email, phone, city, country, codepostal, isAdmin FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Squelette.css">
  <title>Users</title>
  <style>
        /* User List Page */
        .content {
        padding: 20px;
    }

    h1 {
        font-size: 24px;
        color: var(--primary);
    }

    .search-filters {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    #search {
        width: 30%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid var(--accent);
    }

    #statusFilter {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid var(--accent);
    }

    button {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: var(--accent);
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid var(--accent);
    }

    table th {
        background-color: var(--primary);
        color: white;
    }

    td.active {
        color: var(--highlight);
    }

    td.inactive {
        color: var(--text);
    }

    button.edit-btn {
        background-color: var(--accent);
    }

    button.deactivate-btn {
        background-color: var(--highlight);
    }

    button.delete-btn {
        background-color: #ff4d4d;
    }

    button.edit-btn:hover,
    button.deactivate-btn:hover,
    button.delete-btn:hover {
        background-color: var(--hover-bg);
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination button {
        padding: 10px;
        margin: 0 5px;
        background-color: var(--primary);
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .pagination button:hover {
        background-color: var(--accent);
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <div>
      <h2>Admin Panel</h2>
      <a href="MainPage.php">Main Page</a>
      <a href="Dashborad.php" class="active">Dashboard</a>
      <a href="UserLists.php">User List</a>
      <a href="ReservationList.php">Reservation List</a>
      <a href="OffersList.php">Offers List</a>
      <a href="PaymentHistory.php">Payment History</a>
      <a href="Communication.php">Messages</a>
      <a href="ChangePassword.php">Change Password</a>
      <a href="Access.php">Grant Access</a>
    </div>
    <div>
      <button class="logout-btn" id="logoutBtn" onclick="window.location.href='LoginAdmin.php'">Logout</button>
      <small>&copy; 2025 Admin</small>
    </div>
  </div>

  <div class="content">
    <h1>Liste des Utilisateurs</h1>

    <!-- Search and Filter Section -->
    <div class="search-filters">
        <input type="text" id="search" placeholder="Rechercher par nom ou email..." />
        <select id="statusFilter">
            <option value="">Tous les statuts</option>
            <option value="admin">Admin</option>
            <option value="user">Utilisateur</option>
        </select>
        <button onclick="applyFilters()">Filtrer</button>
    </div>

    <!-- User Table -->
    <table id="userTable">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Pays</th>
                <th>Code Postal</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["prenom"]) ?></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                        <td><?= htmlspecialchars($row["phone"]) ?></td>
                        <td><?= htmlspecialchars($row["city"]) ?></td>
                        <td><?= htmlspecialchars($row["country"]) ?></td>
                        <td><?= htmlspecialchars($row["codepostal"]) ?></td>
                        <td class="<?= $row["isAdmin"] ? "active" : "inactive" ?>">
                            <?= $row["isAdmin"] ? "Admin" : "Utilisateur" ?>
                        </td>
                        <td>
                            
                            <button class="delete-btn">Supprimer</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Section -->
    <div class="pagination">
        <button class="prev">Précédent</button>
        <button class="next">Suivant</button>
    </div>
  </div>

  <footer>
    &copy; 2025 Ideal Immobiliere – All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="users.js"></script>
  <script src="admin.js"></script>
</body>
</html>

<?php $conn->close(); ?>

