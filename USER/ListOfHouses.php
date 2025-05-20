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

// Fetch properties from database
$sql = "SELECT * FROM properties WHERE disponibilite = 1 ORDER BY dateajout DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List of Houses</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Fix for property cards layout */
        .home-card {
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
        }
        
        .home-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            position: relative;
            z-index: 1;
        }
        
        .home-card .home-info {
            padding: 15px;
            position: relative;
            z-index: 2;
            background-color: #fff;
            height: auto;
            display: flex;
            flex-direction: column;
        }
        
        .property-status {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 3;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }
        
        .property-status.sale {
            background-color: #2ecc71;
        }
        
        .property-status.rent {
            background-color: #3498db;
        }
        
        .home-listings {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }
        
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 16px;
            color: #6c757d;
        }
    </style>
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
                        <a href="NewOffer.php" class="add-button" title="Add New Property">
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
    </div>

    <script src="https://kit.fontawesome.com/075922b03a.js" crossorigin="anonymous"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all filter elements
        const searchInput = document.getElementById('propertySearch');
        const priceRangeSelect = document.getElementById('priceRange');
        const propertyTypeSelect = document.getElementById('propertyType');
        const bedroomsSelect = document.getElementById('bedrooms');
        const resetButton = document.getElementById('resetFilters');
        const searchButton = document.getElementById('searchBtn');
        
        // Get all property cards
        const propertyCards = document.querySelectorAll('.home-card');
        
        // Function to apply filters
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const priceRange = priceRangeSelect.value;
            const propertyType = propertyTypeSelect.value.toLowerCase();
            const bedrooms = bedroomsSelect.value;
            
            // Loop through all property cards
            propertyCards.forEach(card => {
                let showCard = true;
                
                // Search term filter
                const cardContent = card.textContent.toLowerCase();
                if (searchTerm && !cardContent.includes(searchTerm)) {
                    showCard = false;
                }
                
                // Price range filter
                if (priceRange && showCard) {
                    const price = parseInt(card.dataset.price);
                    if (priceRange === '5000+') {
                        if (price < 5000) showCard = false;
                    } else {
                        const [min, max] = priceRange.split('-').map(Number);
                        if (price < min || (max && price > max)) showCard = false;
                    }
                }
                
                // Property type filter
                if (propertyType && showCard) {
                    if (card.dataset.type.toLowerCase() !== propertyType) showCard = false;
                }
                
                // Bedrooms filter
                if (bedrooms && showCard) {
                    if (parseInt(card.dataset.bedrooms) < parseInt(bedrooms)) showCard = false;
                }
                
                // Show or hide the card
                card.style.display = showCard ? 'flex' : 'none';
            });
            
            // Check if there are visible cards
            const visibleCards = Array.from(propertyCards).filter(card => card.style.display === 'flex');
            const existingNoResults = document.querySelector('.no-results');
            
            if (visibleCards.length === 0) {
                if (!existingNoResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'no-results';
                    noResultsDiv.textContent = 'No properties match your search criteria';
                    document.getElementById('propertiesContainer').appendChild(noResultsDiv);
                }
            } else {
                if (existingNoResults) existingNoResults.remove();
            }
        }
        
        // Event listeners for filters
        searchButton.addEventListener('click', applyFilters);
        priceRangeSelect.addEventListener('change', applyFilters);
        propertyTypeSelect.addEventListener('change', applyFilters);
        bedroomsSelect.addEventListener('change', applyFilters);
        
        // Search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        // Reset filters
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            priceRangeSelect.selectedIndex = 0;
            propertyTypeSelect.selectedIndex = 0;
            bedroomsSelect.selectedIndex = 0;
            
            // Show all cards
            propertyCards.forEach(card => {
                card.style.display = 'flex';
            });
            
            // Remove no results message if it exists
            const noResults = document.querySelector('.no-results');
            if (noResults) noResults.remove();
        });
    });
    </script>
</body>
</html>