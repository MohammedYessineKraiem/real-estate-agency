<?php
session_start(); // Start the session at the very beginning of the file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: LoginUser.php"); // Make sure this is the correct path to your login page
    exit();
}

// If the user is logged in, we can access their information from the session
$userName = $_SESSION['user_name'];
$userId = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "bzbzd"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");

// Fetch properties for the connected user
$sql = "SELECT * FROM properties WHERE owner = $userId AND disponibilite = 1 ORDER BY dateajout DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Properties</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-dashboard">

    <div class="ds-left-menu ">
            <button class="btn-menu">
                <i class="fa-solid fa-circle-chevron-left"></i>
            </button>

            <div class="ds-perfil">
            <div class="foto">
                <button style="background: none; border: none; padding: 0; cursor: pointer;" onclick="window.location.href='user.php'">
                    <img src="assets/3135715.png" alt="User Profile">
                </button>
            </div>
            <div class="info-perfil">
                    <span>User</span>
                    <h3><?php echo $userName; ?></h3>
                </div>
            </div>

            <div class="ds-menu">
                <ul>
                    <li><a href="acceuil.php"><i class="fa-solid fa-home"></i><span>Home</span></a></li>
                    <li><a href="ListOfHouses.php"><i class="fa-solid fa-truck-fast"></i><span>List of Houses</span></a></li>
                    <li><a href="ContactUs.php"><i class="fa-solid fa-gift"></i><span>Contact</span></a></li>
                    <li><a href="AboutUs.php"><i class="fa-solid fa-gift"></i><span>About Us</span></a></li>
                    <li><a href="MyHouses.php"><i class="fa-solid fa-gift"></i><span>My Houses</span></a></li>

                </ul>
            </div>

            <div class="sign-off">
                <a href="Index.php" class="btn-sign-off">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>log out</span>
                </a>
            </div>
        </div>

        <div class="ds-panel">
            <div class="panel-header">
                <header>
                    <div class="header-content">
                        <h1>Browse Our Available Properties</h1>
                        <p>Find your perfect home from our extensive listings</p>
                    </div>
                </header>
            </div>

            <div class="ds-panel-content">
                <div class="search-container">
                    <div class="search-wrapper">
                        <input type="text" id="propertySearch" class="search-input" placeholder="Search by location, price, or features...">
                        <button class="search-button" id="searchBtn">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        <a href="NewOffer." class="add-button" title="Add New Property">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                    <div class="filter-options">
                    <div class="filter-group">
                        <label for="priceRange">Price Range:</label>
                        <select id="priceRange" class="filter-select">
                            <option value="">Any Price</option>
                            <option value="0-500">$0 - $500</option>
                            <option value="1000-1500">$1,000 - $1,500</option>
                            <option value="1500-2500">$1,500 - $2,500</option>
                            <option value="2500-5000">$2,500 - $5,000</option>
                            <option value="5000+">$5,000+</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="propertyType">Property Type:</label>
                        <select id="propertyType" class="filter-select">
                            <option value="">All Types</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="villa">Villa</option>
                            <option value="commercial">Commercial</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="bedrooms">Bedrooms:</label>
                        <select id="bedrooms" class="filter-select">
                            <option value="">Any</option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                    <button id="resetFilters" class="reset-button">Reset Filters</button>
                    </div>
                </div>

                <section class="home-listings" id="propertiesContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while($row = $result->fetch_assoc()) {
                            $imageUrl = !empty($row["image"]) ? $row["image"] : "assets/default-property.jpg";
                            $price = number_format($row["price"], 0, ',', ' ');
                            $statusBadge = '';
                            
                            // Add status badge (for sale/for rent)
                            if (!empty($row["status"])) {
                                $statusClass = strtolower($row["status"]) === 'for sale' ? 'sale' : 'rent';
                                $statusBadge = "<span class='property-status {$statusClass}'>{$row["status"]}</span>";
                            }
                            
                            echo "
                            <div class='home-card' data-id='{$row["id"]}' data-price='{$row["price"]}' data-type='{$row["type"]}' data-bedrooms='{$row["nbpiece"]}' data-location='{$row["location"]}'>
                                {$statusBadge}
                                <img src='{$imageUrl}' alt='{$row["title"]}'>
                                <div class='home-info'>
                                    <h3>{$row["title"]}</h3>
                                    <p class='location'><i class='fa-solid fa-location-dot'></i> {$row["location"]}</p>
                                    <div class='property-details'>
                                        <span><i class='fa-solid fa-bed'></i> {$row["nbpiece"]} rooms</span>
                                        <span><i class='fa-solid fa-ruler-combined'></i> {$row["superficie"]} m²</span>
                                    </div>
                                    <div class='price'>{$price} €</div>
                                    <a href='NewDetailsHouse.php?id={$row["id"]}' class='view-details-btn'>View Details</a>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<div class='no-results'>No properties found</div>";
                    }
                    $conn->close();
                    ?>
                </section>

                <footer class="modern-footer">
                    <div class="footer-grid">
                    <div class="footer-brand">
                        <h2>Idéal Immobilier</h2>
                        <p>Agence située aux Berges du Lac, nous vous accompagnons dans l'achat, la vente ou la location de biens immobiliers.</p>
                    </div>
                    <div class="footer-contact">
                        <h3>Contact</h3>
                        <ul>
                            <li>📍 Rue du Lac Ammar, 1053 Tunis</li>
                            <li>📞 (+216) 53 540 819</li>
                            <li>📧 contact@idealimmo.tn</li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h3>Liens utiles</h3>
                        <ul>
                            <li><a href="#">Biens à vendre</a></li>
                            <li><a href="#">Biens à louer</a></li>
                            <li><a href="#">Bureaux & Commerces</a></li>
                            <li><a href="#">Évaluation gratuite</a></li>
                        </ul>
                    </div>
                    </div>
                    <div class="footer-bottom">
                    <p>© Idéal Immobilier 2025 — Tous droits réservés</p>
                    </div>
                </footer>
            </div>
        </div>

            <div class="ds-panel-content">
                <section class="home-listings" id="propertiesContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $imageUrl = !empty($row["image"]) ? $row["image"] : "assets/default-property.jpg";
                            $price = number_format($row["price"], 0, ',', ' ');
                            echo "
                            <div class='home-card' data-id='{$row["id"]}' data-price='{$row["price"]}' data-type='{$row["type"]}' data-bedrooms='{$row["nbpiece"]}' data-location='{$row["location"]}'>
                                <img src='{$imageUrl}' alt='{$row["title"]}'>
                                <div class='home-info'>
                                    <h3>{$row["title"]}</h3>
                                    <p class='location'><i class='fa-solid fa-location-dot'></i> {$row["location"]}</p>
                                    <div class='property-details'>
                                        <span><i class='fa-solid fa-bed'></i> {$row["nbpiece"]} rooms</span>
                                        <span><i class='fa-solid fa-ruler-combined'></i> {$row["superficie"]} m²</span>
                                    </div>
                                    <div class='price'>{$price} €</div>
                                    <a href='NewDetailsHouse.php?id={$row["id"]}' class='view-details-btn'>View Details</a>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<div class='no-results'>You have no properties listed</div>";
                    }
                    ?>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
