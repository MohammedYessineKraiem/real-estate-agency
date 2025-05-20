<?php
// Include your database connection file
include 'db_connection.php';

// Function to fetch total count from a table
function getTotalCount($conn, $table, $condition = '') {
    $sql = "SELECT COUNT(*) as total FROM $table $condition";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to fetch total revenue from reservation table
function getTotalRevenue($conn) {
    $sql = "SELECT SUM(montant) as total FROM reservations";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
        return ($total !== null) ? $total : 0; // Ensure null is treated as 0
    } else {
        return 0;
    }
}

// Function to get new reservations this month
function getNewReservationsThisMonth($conn) {
    $sql = "SELECT COUNT(*) as total FROM reservations WHERE MONTH(reservationDate) = MONTH(CURDATE()) AND YEAR(reservationDate) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to get registered users this month
function getNewUsersThisMonth($conn) {
    $sql = "SELECT COUNT(*) as total FROM properties WHERE MONTH(dateajout) = MONTH(CURDATE()) AND YEAR(dateajout) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to calculate the average occupancy rate.
function getAverageOccupancyRate($conn) {
    // First, get the total number of properties.
    $totalProperties = getTotalCount($conn, 'properties');

    if ($totalProperties == 0) {
        return 0; // Avoid division by zero.
    }
    // Calculate the number of reserved properties
    $sql = "SELECT COUNT(DISTINCT propertyId) as reservedProperties FROM reservations";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reservedProperties = $row['reservedProperties'];
    } else {
        return 0;
    }
    $occupancyRate = ($reservedProperties / $totalProperties) * 100;
    return round($occupancyRate, 2); // Round to 2 decimal places
}

// Function to get monthly reservation counts
function getMonthlyReservationCounts($conn) {
    $monthlyCounts = array_fill(1, 12, 0); // Initialize an array for each month

    $sql = "SELECT MONTH(reservationDate) as month, COUNT(*) as count 
            FROM reservations 
            WHERE YEAR(reservationDate) = YEAR(CURDATE())
            GROUP BY MONTH(reservationDate)
            ORDER BY MONTH(reservationDate)";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $month = (int)$row['month']; // Ensure month is an integer
            $monthlyCounts[$month] = (int)$row['count']; // Store the count
        }
    }
    return array_values($monthlyCounts); // Return the array values
}


// Open the database connection
$conn = OpenCon();

// Get the data
$totalUsers = getTotalCount($conn, 'users');
$totalReservations = getTotalCount($conn, 'reservations');
$totalProperties = getTotalCount($conn, 'properties');
$totalRevenue = getTotalRevenue($conn);
$newReservationsThisMonth = getNewReservationsThisMonth($conn);
$newUsersThisMonth = getNewUsersThisMonth($conn);
$occupancyRate = getAverageOccupancyRate($conn);
$monthlyReservationCounts = getMonthlyReservationCounts($conn);

// Close the database connection
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="Squelette.css">
    <title>Dashboard - Admin Panel</title>
    <style>
        /* Dashboard.css */
        :root {
        --primary: #2d6a4f;
        --accent: #40916c;
        --background: #f1fbe9;
        --text: #081c15;
        --highlight: #95d5b2;
        --hover-bg: #d8f3dc;
        }

        .content {
        margin-left: 270px;
        padding: 40px;
        min-height: 100vh;
        background-color: var(--background);
        color: var(--text);
        }

        h1 {
        margin-bottom: 30px;
        }

        .dashboard-grid {
            display: flex;
            flex-direction: column; /* Stack cards vertically by default */
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (min-width: 768px) {
            .dashboard-grid {
                flex-direction: row;          /* Arrange cards in a row on medium screens and up */
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); /* Use grid for responsiveness */
            }
        }


        .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
        text-align: center;
        }

        .card:hover {
        transform: translateY(-4px);
        background-color: var(--hover-bg);
        }

        .card h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: var(--primary);
        }

        .card p {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 8px;
        color: var(--accent);
        }

        .quick-graph {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .quick-graph h2 {
        margin-bottom: 20px;
        color: var(--primary);
        }
        .chart-section {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center charts horizontally */
            margin-top: 20px; /* Add some space above the charts */
        }

        .charts-grid {
            display: flex;
            flex-direction: column; /* Stack charts vertically by default */
            align-items: stretch; /* Stretch items to container width */
            gap: 20px;
            width: 95%; /* Take up most of the container width */
            max-width: 1200px; /* Limit the maximum width of the chart grid */
            margin-top: 20px;
        }

        /* Style for the canvas elements */
        .charts-grid canvas {
            width: 100%; /* Make charts responsive within their grid cells */
            height: auto; /* Maintain aspect ratio */
            max-height: 300px; /* Limit maximum height of charts */
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* Ensure charts are always stacked, even on larger screens */
        @media (min-width: 768px) {
            .charts-grid {
                flex-direction: column;
                grid-template-columns: 1fr; /* Force single column layout */
            }
        }
        @media (min-width: 1200px) {
             .charts-grid {
                 flex-direction: column;
                grid-template-columns: 1fr; /* Force single column layout */
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
        <a href="PaymentHistory.php">Messages</a>
        <a href="ChangePassword.php">Change Password</a>
        <a href="Access.php">Grant Access</a>
        </div>
        <div>
        <button class="logout-btn" id="logoutBtn" onclick="window.location.href='LoginAdmin.php'">Logout</button>
        <small>&copy; 2025 Admin</small>
        </div>
    </div>

    <div class="content">
        <h1>Tableau de bord analytique</h1>

        <div class="dashboard-grid">
        <div class="card">
            <h3>Total Utilisateurs</h3>
            <p id="total-users"><?php echo $totalUsers; ?></p>
            <small>Inscrits ce mois: <?php echo $newUsersThisMonth; ?></small>
        </div>
        <div class="card">
            <h3>Réservations</h3>
            <p id="total-reservations"><?php echo $totalReservations; ?></p>
             <small>Nouvelles réservations ce mois: <?php echo $newReservationsThisMonth; ?></small>
        </div>
        <div class="card">
            <h3>Biens listés</h3>
            <p id="total-properties"><?php echo $totalProperties; ?></p>
            <small>Actuellement actifs</small>
        </div>
        <div class="card">
            <h3>Revenus</h3>
            <p id="revenue"><?php echo $totalRevenue; ?> DT</p>
            <small>Total</small>
        </div>
         <div class="card">
            <h3>Taux d'occupation</h3>
            <p id="occupation-rate"><?php echo $occupancyRate; ?>%</p>
            <small>Moyenne</small>
        </div>
        </div>

        <div class="chart-section">
            <h2>Activité mensuelle & journalière</h2>
            <div class="charts-grid">
                <canvas id="userChart"></canvas>
                <canvas id="reservationChart"></canvas>
                <canvas id="dailyIncomeChart"></canvas>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 Ideal Immobiliere – All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script >
    // Get the canvas elements
    const userChartCtx = document.getElementById('userChart').getContext('2d');
    const reservationChartCtx = document.getElementById('reservationChart').getContext('2d');
    const dailyIncomeChartCtx = document.getElementById('dailyIncomeChart').getContext('2d');
    const occupationRateDisplay = document.getElementById('occupation-rate');

    // --- User Chart ---
    const userChart = new Chart(userChartCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nouveaux Utilisateurs',
                data: [
                    <?php
                        // In a real scenario, you would fetch this data from the database
                        // For demonstration, I'm using placeholder data
                        echo isset($newUsersThisMonth) ? $newUsersThisMonth : 0;
                        ?>,
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Inscriptions Mensuelles',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // --- Reservation Chart ---
    const reservationChart = new Chart(reservationChartCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nouvelles Réservations',
                data: [
                    <?php echo implode(',', $monthlyReservationCounts); ?>,
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Réservations Mensuelles',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // --- Daily Income Chart ---
    const dailyIncomeChart = new Chart(dailyIncomeChartCtx, {
        type: 'bar',
        data: {
            labels:  ['Jour 1', 'Jour 2', 'Jour 3', 'Jour 4', 'Jour 5', 'Jour 6', 'Jour 7', 'Jour 8', 'Jour 9', 'Jour 10', 'Jour 11', 'Jour 12','Jour 13','Jour 14','Jour 15','Jour 16','Jour 17','Jour 18','Jour 19','Jour 20','Jour 21','Jour 22','Jour 23','Jour 24','Jour 25','Jour 26','Jour 27','Jour 28','Jour 29','Jour 30'], // Will be populated with days of the month
            datasets: [{
                label: 'Revenu Quotidien',
                data: [<?php echo isset($totalRevenue) ? $totalRevenue/30 : 0; ?>,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0], // Will be populated with daily income
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Revenu Quotidien ce Mois',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    occupationRateDisplay.textContent =  <?php echo $occupancyRate; ?> + "%";

    </script>
    <script src="admin.js"></script>
</body>
</html>
