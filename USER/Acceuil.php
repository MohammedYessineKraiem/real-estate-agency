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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acceuil</title>

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
            
            <div class="panel-header" >
                
                <header >
                
                    <div class="header-content">
                        <h1>Welcome to Our Rental Website</h1>
                        <p>Your ideal home is just a click away</p>
                    </div>
                </header>
            </div>

            <div class="ds-panel-content">

                <section class="offer-container">
                    <h2 class="special-offer-title">Special Offers</h2>
                    <div class="carousel">
                        <div class="carousel-images">
                            <img src="assets/hq720 (1).jpg" alt="Offer 1">
                            <img src="assets/hq720.jpg" alt="Offer 2">
                            <img src="assets/maxresdefault.jpg" alt="Offer 3">
                        </div>
                        <button class="carousel-button prev" onclick="moveSlide(-1)">&#10094;</button>
                        <button class="carousel-button next" onclick="moveSlide(1)">&#10095;</button>
                    </div>
                </section>
            

                <section class="properties">
                    <article class="property">
                        <h2>Luxury Apartments for Rent</h2>
                        <p>Explore our range of luxury apartments with modern amenities and prime locations.</p>
                        <img src="assets/maison.jpeg" alt="Luxury Apartments">
                    </article>
            
                    <article class="property">
                        <h2>Affordable Housing Options</h2>
                        <p>Discover affordable rental homes that meet your budget without compromising quality.</p>
                        <img src="assets/images (2).jpeg" alt="Affordable Housing">
                    </article>
            
                    <article class="property">
                        <h2>Commercial Spaces Available</h2>
                        <p>Find the perfect commercial space to grow your business in bustling city centers.</p>
                        <img src="assets/images.jpeg" alt="Commercial Spaces">
                    </article>
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

    
    <script>
        let currentSlide = 0;


        function moveSlide(step) {
            const slides = document.querySelector('.carousel-images');
            const totalSlides = slides.children.length;
            currentSlide = (currentSlide + step + totalSlides) % totalSlides;
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.querySelector('.btn-menu');
            const leftMenu = document.querySelector('.ds-left-menu');

            menuBtn.addEventListener('click', () => {
                leftMenu.classList.toggle('collapsed');
                leftMenu.classList.toggle('show');
            });
        });
    </script>
</body>
</html>
