<?php
// Include database connection file
include 'db_connection.php';  // Corrected include syntax

// Open the database connection
$conn = OpenCon();

// Fetch reservations from the database
$query = "SELECT r.id AS reservation_id, u.name AS user_name, p.title AS property_name, 
            r.datedebut AS start_date, r.datefin AS end_date, 
            r.methodepaiment AS payment_method, 
            r.montant AS amount, r.jourRv AS reservation_day
          FROM reservations r
          JOIN users u ON r.userId = u.id
          JOIN properties p ON r.propertyId = p.id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error); // Handle query errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Squelette.css">
  <title>Payment</title>
</head>
<style>
    /* Payment History CSS */
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

    .completed {
        color: #40916c;
        font-weight: bold;
    }

    .pending {
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
        <h1>Liste des Réservations</h1>

        <div class="filter-section">
            <input type="text" id="search" placeholder="Rechercher..." />
            <select id="statusFilter">
                <option value="">Statut</option>
                <option value="paid">Payé</option>
                <option value="unpaid">Non payé</option>
            </select>
        </div>

        <table id="reservationTable">
            <thead>
                <tr>
                    <th>ID Paiement</th>
                    <th>Nom de l'Utilisateur</th>
                    <th>Bien Réservé</th>
                    <th>Statut de Paiement</th>
                    <th>Montant</th>
                    <th>Mode de Paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the results and display each reservation
                while ($row = $result->fetch_assoc()) {
                    $payment_class = ($row['payment_method'] == 'cartebancaire') ? 'paid' : 'unpaid';
                    if ($row['payment_method'] == 'cartebancaire' || $row['payment_method'] == 'virement') {
                        $payment_status = 'payée';  // Payment is considered 'paid'
                    } else {
                        $payment_status = 'non payée';  // Payment is considered 'unpaid'
                    }
                    echo "<tr>";
                    echo "<td>#{$row['reservation_id']}</td>";
                    echo "<td>{$row['user_name']}</td>";
                    echo "<td>{$row['property_name']}</td>";
                    echo "<td class='$payment_status'>{$payment_status}</td>";
                    echo "<td>{$row['amount']} DT</td>";
                    echo "<td>{$row['payment_method']}</td>";
                    echo "<td>
                            <button class='delete-btn'>Supprimer</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

  <footer>
    &copy; 2025 Ideal Immobiliere – All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="pay.js"></script>
  <script src="admin.js"></script>
</body>
</html>


----$_COOKIE
