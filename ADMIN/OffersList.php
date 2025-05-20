<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd"; // <-- À adapter

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$sql = "SELECT id,title, description, nbpiece, disponibilite, price, location, dateajout, superficie, image, type, status, owner FROM properties";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Squelette.css">
  <title>Liste des Offres</title>
</head>
<style>
  /* General Styling */
  body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
  }

  .content {
      padding: 20px;
      margin-left: 270px;
      background-color: #fff;
  }

  h1 {
      font-size: 28px;
      color: #2d6a4f;
      margin-bottom: 20px;
  }

  .filter-section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
  }

  #search {
      padding: 8px;
      width: 70%;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
  }

  #statusFilter {
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
  }

  table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  table th, table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
  }

  table th {
      background-color: #2d6a4f;
      color: #fff;
      font-weight: bold;
  }

  table td {
      font-size: 14px;
  }

  .available {
      color: #40916c;
      font-weight: bold;
  }

  .unavailable {
      color: #e63946;
      font-weight: bold;
  }

  button {
      padding: 8px 12px;
      font-size: 14px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
  }

  .edit-btn {
      background-color: #f1faee;
      color: #2d6a4f;
      margin-right: 8px;
  }

  .edit-btn:hover {
      background-color: #2d6a4f;
      color: #fff;
  }

  .delete-btn {
      background-color: #e63946;
      color: #fff;
  }

  .delete-btn:hover {
      background-color: #d32f2f;
  }

  @media (max-width: 768px) {
      table th, table td {
          font-size: 12px;
          padding: 10px;
      }

      #search {
          width: 60%;
      }

      #statusFilter {
          width: 30%;
      }
  }

</style>
<body>

  <!-- Sidebar inchangée -->
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

  <!-- Contenu dynamique -->
  <div class="content">
    <h1>Liste des Offres</h1>

    <div class="filter-section">
      <input type="text" id="search" placeholder="Rechercher une offre..." />
      <select id="statusFilter">
        <option value="">Statut</option>
        <option value="available">Disponible</option>
        <option value="unavailable">Indisponible</option>
      </select>
    </div>

    <table id="offersTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Titre</th>
          <th>Nombre de pièces</th>
          <th>Disponibilité</th>
          <th>Prix</th>
          <th>Localisation</th>
          <th>Date d'ajout</th>
          <th>Superficie</th>
          <th>Type</th>
          <th>Statut</th>
          <th>Propriétaire</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td>#<?= htmlspecialchars($row["id"]) ?></td>
            <td><?= htmlspecialchars($row["title"]) ?></td>
            <td><?= htmlspecialchars($row["nbpiece"]) ?></td>
            <td><?= htmlspecialchars($row["disponibilite"]) ?></td>
            <td><?= htmlspecialchars($row["price"]) ?> DT</td>
            <td><?= htmlspecialchars($row["location"]) ?></td>
            <td><?= htmlspecialchars($row["dateajout"]) ?></td>
            <td><?= htmlspecialchars($row["superficie"]) ?> m²</td>
            <td><?= htmlspecialchars($row["type"]) ?></td>
            <td class="<?= $row["status"] === 'available' ? 'available' : 'unavailable' ?>">
              <?= $row["status"] === 'available' ? 'Disponible' : 'Indisponible' ?>
            </td>
            <td><?= htmlspecialchars($row["owner"]) ?></td>
            <td>
              <button class="delete-btn">Supprimer</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <footer>
    &copy; 2025 Ideal Immobiliere – All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="admin.js"></script>
  <script src="offre.js"></script>
</body>
</html>

<?php $conn->close(); ?>
