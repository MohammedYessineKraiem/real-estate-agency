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

// Database connection (assuming the same connection details as ListOfHouses.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Get property ID from URL
$property_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch property details
$sql = "SELECT id, title, description, nbpiece, disponibilite, price, location, dateajout, superficie, image, type, status, owner FROM properties WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imageUrl = !empty($row["image"]) ? $row["image"] : "assets/default-property.jpg";
    $price = number_format($row["price"], 0, ',', ' '); // Format the price
    $nbpiece = $row["nbpiece"];
    $disponibilite = $row["disponibilite"];
    $superficie = $row["superficie"];
    $location = $row["location"];
    $title = htmlspecialchars($row["title"]);
    $description = htmlspecialchars($row["description"]);

} else {
    echo "Property not found";
    exit; // Stop execution if property not found
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
    #reserver-btn {
        background-color: rgb(255, 215, 0);
        color: #fff;
        padding: 18px 60px;
        font-size: 20px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        display: block;
        margin: 30px auto;
        width: 300px;
        max-width: 90%;
        text-align: center;
    }

    #reserver-btn.clicked {
        transform: scale(0.95);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .shake {
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        50% { transform: translateX(4px); }
        75% { transform: translateX(-2px); }
        100% { transform: translateX(0); }
    }

    .content {
        display: flex; /* Use flexbox to arrange image and info side-by-side on larger screens */
        flex-wrap: wrap; /* Allow content to wrap on smaller screens */
        gap: 20px; /* Space between image and info */
    }

    .image-container {
        flex: 1 1 300px; /* Allow image container to grow/shrink, with a base width */
        max-width: 400px; /* Optional: set a maximum width for the image */
    }

    .image-container img {
        display: block;
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .property-info {
        flex: 1 1 400px; /* Allow property info to grow/shrink, with a base width */
    }

    .property-details-list {
        list-style: none;
        padding: 0;
    }

    .property-details-list li {
        margin-bottom: 8px;
    }

    .description {
        margin-top: 20px;
        line-height: 1.6;
    }

    /* Adjust for smaller screens to stack image and info */
    @media (max-width: 768px) {
        .content {
            flex-direction: column;
        }

        .image-container {
            max-width: 100%;
        }

        .property-info {
            margin-top: 20px;
        }
    }

    .modern-footer {
        background-color: #161719; /* Dark grey/almost black */
        color: #F0F0F0; /* Light grey/white */
        padding: 40px 20px 60px 20px; /* Add some padding at the bottom for the "log out" link */
        text-align: center;
        position: relative; /* To position the sign-off link */
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 20px; /* Reduce margin above the bottom section */
        text-align: left;
    }

    .footer-brand h2 {
        color: #00BCD4; /* Keep the teal color for the brand */
        margin-bottom: 10px;
    }

    .footer-contact h3,
    .footer-links h3 {
        color: #FFFFFF; /* White for the headings */
        margin-bottom: 15px;
        border-bottom: 1px solid #555; /* Lighter horizontal rule */
        padding-bottom: 5px;
    }

    .footer-contact ul,
    .footer-links ul {
        list-style: none;
        padding: 0;
    }

    .footer-contact li {
        margin-bottom: 10px;
    }

    .footer-contact li i { /* Style the icons if you are using any */
        margin-right: 5px;
        color: #888;
    }

    .footer-links li a {
        color: #F0F0F0; /* Light grey/white for links */
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links li a:hover {
        color: #FFFFFF; /* White on hover */
    }

    .footer-bottom {
        padding-top: 20px;
        border-top: 1px solid #555; /* Subtle horizontal line */
        font-size: 0.9em;
        color: #888; /* Slightly darker copyright text */
    }

    .sign-off-footer {
        position: absolute;
        bottom: 20px;
        left: 20px;
    }

    .sign-off-footer a {
        color: #F0F0F0; /* Light grey/white for the link */
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .sign-off-footer a i {
        margin-right: 5px;
    }

    .sign-off-footer a:hover {
        color: #FFFFFF; /* White on hover */
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
            </div>
        <div class="ds-panel">
            <div class="panel-header" >
                <header >
                    <div class="header-content">
                        <h1>Welcome to Our Rental Website</h1>
                        <p>Your ideal home is just a click away</p>
                    </div>
                </header>
            </div>
            <div class="ds-panel-content">
                <section class="wrapper comment-section">
                    <h2>Details</h2>
                    <section class="content">
                        <section class="image-container">
                            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $title; ?>" id="img">
                        </section>
                        <section class="property-info">
                            <h2><?php echo $title; ?></h2>
                            <ul class="property-details-list">
                                <li><strong>Prix :</strong> <?php echo $price; ?> &#8364; / mois</li>
                                <li><strong>Nombre de pièces :</strong> <?php echo htmlspecialchars($nbpiece); ?></li>
                                <li><strong>Disponibilité :</strong> <?php echo $disponibilite == 1 ? 'Disponible' : 'Non Disponible'; ?></li>
                                <li><strong>Superficie :</strong> <?php echo htmlspecialchars($superficie); ?> m²</li>
                                <li><strong>Localisation :</strong> <?php echo htmlspecialchars($location); ?></li>
                            </ul>
                            <a href="#comments" style="display: block; margin-top: 10px; color: orange; text-decoration: none;">(comments)</a>
                            <h3>Description</h3>
                            <section class="description">
                                <p><?php echo $description; ?></p>
                            </section>
                            <a href="ReservationForm.php?id=<?php echo $property_id; ?>" id="reserver-btn" class="reserve-button">Réserver</a>
                        </section>
                        <br>
                        <br>
                        <section class="comment-form">
                            <h2>Comments</h2>
                            <h3>Ajouter un commentaire</h3>
                            <textarea id="commentText" placeholder="Votre commentaire"></textarea>
                            <input type="number" id="commentRating" min="1" max="5" placeholder="Note (1-5)">
                            <button id="sendCommentButton">Envoyer</button>
                        </section>
                        <section class="comment" id="commentSection">
                            <img src="assets/3135715.png" alt="Profil">
                            <section class="comment-content" id="comments">
                                <p><strong>Jean Dupont</strong> - <span class="stars">★★★★★</span></p>
                                <p>Superbe maison, très confortable et bien située !</p>
                                <p><small>Posté le 26/02/2025</small></p>
                            </section>
                        </section>
                    </section>
                </section>
            </div>


                <footer class="modern-footer">
                    <div class="sign-off-footer">
                        <a href="Index.php" class="btn-sign-off">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            <span>log out</span>
                        </a>
                    </div>
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

        </body>
        </html>
        <?php
        $conn->close();
        ?>