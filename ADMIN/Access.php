<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Squelette.css">
  <title>Access Page</title>
</head>
<style>
    /* Styling for the page */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f1fbe9;
        margin: 0;
        padding: 0;
        color: #081c15;
    }

    .sidebar {
        width: 250px;
        background-color: #00695c;
        color: white;
        padding: 20px;
        height: 100vh;
        position: fixed;
    }

    .sidebar h2 {
        margin-bottom: 20px;
        color: #95d5b2;
        font-size: 24px;
    }

    .sidebar a {
        display: block;
        padding: 10px;
        margin-bottom: 10px;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #004d40;
        font-weight: bold;
    }

    .content {
        margin-left: 270px;
        padding: 40px;
        flex-grow: 1;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 28px;
        color: #00695c;
    }

    .header input {
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        border: 1px solid #ddd;
        outline: none;
    }

    .header button {
        padding: 10px 20px;
        background-color: #00695c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    .header button:hover {
        background-color: #004d40;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table th {
        background-color: #00695c;
        color: white;
    }

    table td button {
        padding: 6px 12px;
        background-color: #40916c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    table td button:hover {
        background-color: #440f2c;
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: #00695c;
        color: white;
        font-size: 14px;
        position: relative;
        bottom: 0;
        width: 100%;
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
        <div class="header">
            <h1>Accorder ou retirer l'accès d'administration</h1>
            <div>
                <input type="text" id="searchInput" placeholder="Rechercher un utilisateur...">
                <button id="searchBtn">Rechercher</button>
            </div>
        </div>

        <!-- User Table -->
        <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle actuel</th>
                    <th>Accorder l'accès</th>
                    <th>Retirer l'accès</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Database connection
                include 'db_connection.php'; // Adjust the file for your DB connection
                $conn = OpenCon();

                // Check if the connection was successful
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch users from the database
                $sql = "SELECT id, name, email, isAdmin FROM users"; // Ensure 'isAdmin' is the correct column name
                $result = $conn->query($sql);

                // Check if the query was successful
                if ($result === false) {
                    die("Error: " . $conn->error);
                }

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        // Check if the user is an admin based on the isAdmin boolean
                        $role = ($row['isAdmin'] == 1) ? "Administrateur" : "Utilisateur"; 
                        
                        // Output each user's information
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $role . "</td>";
                        echo "<td><button onclick='grantAccess(" . $row["id"] . ")'>Accorder</button></td>";
                        echo "<td><button onclick='removeAccess(" . $row["id"] . ")'>Retirer</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No results found</td></tr>";
                }

                // Close the connection
                CloseCon($conn);
            ?>
            </tbody>
        </table>
    </div>

  <footer>
    &copy; 2025 Ideal Immobiliere – All rights reserved.
  </footer>

  <script>
        // Grant access to a user (change role to Admin)
        function grantAccess(userId) {
            const request = new XMLHttpRequest();
            request.open('POST', 'update_user_role.php', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    alert(request.responseText);
                    location.reload();
                }
            };
            request.send('user_id=' + userId + '&role=1'); // 1 for admin
        }

        // Remove access from a user (change role to User)
        function removeAccess(userId) {
            const request = new XMLHttpRequest();
            request.open('POST', 'update_user_role.php', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    alert(request.responseText);
                    location.reload();
                }
            };
            request.send('user_id=' + userId + '&role=0'); // 0 for user
        }

        // Search function
        document.getElementById('searchBtn').addEventListener('click', function () {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                if (name.includes(searchQuery) || email.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

  <script src="admin.js"></script>
</body>
</html>
