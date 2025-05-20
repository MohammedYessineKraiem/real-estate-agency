<?php
session_start();



// Check if the admin is logged in (optional, but good practice)
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page if not logged in
    header("Location: LoginAdmin.php");
    exit();
}
// Check if the session is set
// Retrieve session data
$adminId = $_SESSION['admin_id'];
$adminName = $_SESSION['admin_name'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$error = "";
$success = "";
// Check if the admin is logged in (optional, but good practice)
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit();
}

$adminId = $_SESSION['admin_id']; // Retrieve the admin's ID from the session

// Handle password change
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldPassword = $_POST["oldPassword"] ?? '';
    $newPassword = $_POST["newPassword"] ?? '';
    $confirmPassword = $_POST["confirmPassword"] ?? '';

    // Basic validation
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = "❌ Veuillez remplir tous les champs.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "❌ Le nouveau mot de passe et la confirmation ne correspondent pas.";
    } elseif (strlen($newPassword) < 6) {
        $error = "❌ Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Get current plain password
        $stmt = $conn->prepare("SELECT Password FROM users WHERE id = $adminId AND isAdmin = 1");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $currentPassword = $row['Password'];

            // Use plain text comparison
            if ($oldPassword === $currentPassword) {
                $updateStmt = $conn->prepare("UPDATE users SET Password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $newPassword, $adminId);

                if ($updateStmt->execute()) {
                    $success = "✅ Mot de passe mis à jour avec succès !";
                } else {
                    $error = "❌ Échec de la mise à jour du mot de passe : " . $conn->error;
                }
                $updateStmt->close();
            } else {
                $error = "❌ L'ancien mot de passe est incorrect.";
            }
        } else {
            $error = "❌ Administrateur non trouvé.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="Squelette.css">
    <title>Changer le mot de passe</title>
    <style>
        /* AdminPage.css */

        /* Form styling */
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-top: 30px;
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
        }

        input[type="password"]:focus {
            border-color: #00695c;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #00695c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #004d40;
        }

        .error-message {
            font-size: 12px;
            color: red;
            margin-top: 5px;
            display: block;
        }

        .success-message {
            font-size: 14px;
            color: green;
            margin-top: 10px;
            text-align: center;
            display: block;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
                max-width: 100%;
            }

            button {
                padding: 10px;
            }
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
        <h1>Changer le mot de passe</h1>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form id="changePasswordForm" method="POST" action="">
            <div class="form-group">
                <label for="oldPassword">Ancien mot de passe</label>
                <input type="password" id="oldPassword" name="oldPassword" placeholder="Entrez votre ancien mot de passe" required />
                <small id="oldPasswordError" class="error-message"></small>
            </div>

            <div class="form-group">
                <label for="newPassword">Nouveau mot de passe</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Entrez votre nouveau mot de passe" required />
                <small id="newPasswordError" class="error-message"></small>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmer le nouveau mot de passe" required />
                <small id="confirmPasswordError" class="error-message"></small>
            </div>

            <button type="submit">Changer le mot de passe</button>
        </form>
    </div>

    <footer>
        &copy; 2025 Ideal Immobiliere – All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="Dashboard.js"></script>
    <script src="admin.js"></script>
</body>
</html>