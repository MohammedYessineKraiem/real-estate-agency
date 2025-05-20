<?php
session_start();

// Check if the admin is logged in. If not, redirect them to the login page.
if (!isset($_SESSION['admin_id'])) {
    header("Location: LoginAdmin.php");
    exit();
}

// Retrieve the admin's name from the session
$adminName = $_SESSION['admin_name'];
$adminId = $_SESSION['admin_id'];

// Retrieve admin data from query string (passed from login for initial JS storage)
$initialAdminId = $_GET['admin_id'] ?? '';
$initialAdminName = $_GET['admin_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="MainPage.css" />
    <title>Admin Panel - Main Page</title>
</head>
<body>

    <h1 >Welcome, <?php echo htmlspecialchars($adminName); ?> to the Admin Portal</h1>

    <div class="main-container">
        

        <div class="card-grid">
            <a href="Dashborad.php" class="card" id="card-dashboard">
                <img src="https://img.icons8.com/color/96/dashboard.png" alt="Dashboard"/>
                <span>Dashboard</span>
            </a>
            <a href="UserLists.php" class="card" id="card-userlist">
                <img src="https://img.icons8.com/color/96/user-group-man-man.png" alt="User List"/>
                <span>User List</span>
            </a>
            <a href="ReservationList.php" class="card" id="card-reservations">
                <img src="https://img.icons8.com/color/96/calendar--v1.png" alt="Reservations"/>
                <span>Reservations</span>
            </a>

            <a href="OffersList.php" class="card" id="card-offers">
                <img src="https://img.icons8.com/color/96/price-tag--v1.png" alt="Offers"/>
                <span>Offers</span>
            </a>
            <a href="PaymentHistory.php" class="card" id="card-payments">
                <img src="https://img.icons8.com/color/96/money.png" alt="Payments"/>
                <span>Payment History</span>
            </a>
            <a href="Communication.php" class="card" id="card-messages">
                <img src="https://img.icons8.com/color/96/chat--v1.png" alt="Messages"/>
                <span>Messages</span>
            </a>

            <a href="ChangePassword.php" class="card" id="card-password">
                <img src="https://img.icons8.com/color/96/password.png" alt="Change Password"/>
                <span>Change Password</span>
            </a>
            <a href="Access.php" class="card" id="card-access">
                <img src="https://static.thenounproject.com/png/836673-200.png" alt="Grant Access"/>
                <span>Grant Access</span>
            </a>
        </div>
    </div>

    <footer>
        &copy; 2025 Ideal Immobiliere – All rights reserved.
    </footer>

    <script>
        // Store admin data in local storage upon successful login
        const adminId = "<?php echo htmlspecialchars($initialAdminId); ?>";
        const adminName = "<?php echo htmlspecialchars($initialAdminName); ?>";

        if (adminId && adminName) {
            localStorage.setItem('adminId', adminId);
            localStorage.setItem('adminName', adminName);
        }

        // You can retrieve this data on other pages using:
        // const storedAdminId = localStorage.getItem('adminId');
        // const storedAdminName = localStorage.getItem('adminName');
        // console.log('Stored Admin ID:', storedAdminId);
        // console.log('Stored Admin Name:', storedAdminName);
    </script>
    <script src="MainPage.js"></script>
</body>
</html> 