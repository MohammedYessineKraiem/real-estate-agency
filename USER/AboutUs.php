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
    <title>About</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="AboutUsStyle.css">

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
                    <li><a href="MyHouses.php"><i class="fa-solid fa-gift"></i><span>My houses</span></a></li>
                    

                </ul>

            </div>

            <div class="sign-off">
                <a href="LoginUser.php" class="btn-sign-off">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Cerrar Sesión</span>
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
                <section class="about-grid-flex">
                    <div>
                        <h2>Qui sommes-nous ?</h2>
                        <p>Nous sommes une agence dédiée à vous aider à trouver la maison parfaite. Notre mission est de rendre le processus d'achat ou de location aussi simple que possible.</p>
                    </div>
                </section>
                <section class="team-section">
                    <h2>Rencontrez notre équipe</h2>
                    <div class="team-members">
                        <div class="team-card">
                        <img src="yosra.jpg" alt="Yosra">
                        <p>Yosra</p>
                        </div>
                        <div class="team-card">
                        <img src="yessone.jpg" alt="Mohammed Yessine">
                        <p>Mohammed Yessine</p>
                        </div>
                        <div class="team-card">
                        <img src="nour.jpg" alt="Nour">
                        <p>Nour</p>
                        </div>
                        <div class="team-card">
                        <img src="amine.jpg" alt="Amine">
                        <p>Amine</p>
                        </div>
                    </div>
                </section>
                <section class="float-section" style="padding: 20px;">
                    <p>Notre équipe est composée d’experts passionnés, toujours prêts à vous conseiller. Grâce à notre large réseau, nous proposons des biens adaptés à chaque besoin.</p>
                    <div class="clear"></div>
                </section>

                <section class="video-section" style="padding: 20px;">
                    <h3>Découvrez notre agence</h3>
                    <video controls>
                    <source src="real_estate_video.mp4.MOV" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </section>

                <section class="lists-section">
                    <div class="service-box">
                        <h4>Nos services</h4>
                        <ul>
                            <li><span class="icon">🏠</span> Vente de maisons</li>
                            <li><span class="icon">🔑</span> Location longue et courte durée</li>
                            <li><span class="icon">📊</span> Évaluation immobilière</li>
                            <li><span class="icon">⚖️</span> Accompagnement juridique</li>
                        </ul>
                    </div>
                    <div class="steps-box">
                        <h4>Étapes de votre projet</h4>
                        <ol>
                            <li><span class="icon">📞</span> Prise de contact</li>
                            <li><span class="icon">🏡</span> Visites personnalisées</li>
                            <li><span class="icon">✅</span> Choix du bien</li>
                            <li><span class="icon">✍️</span> Signature et remise des clés</li>
                        </ol>
                    </div>
                </section>
                <section class="vision-mission-section">
                    <div class="vision-mission-box">
                    <h2>Notre vision</h2>
                    <p>Créer une expérience immobilière fluide, transparente et humaine pour chaque client.</p>
                    </div>
                
                    <div class="vision-mission-box">
                    <h2>Notre mission</h2>
                    <p>Connecter les gens à leur futur chez-soi grâce à la technologie, l'expertise et la passion du service.</p>
                    </div>
                </section>
                

                <section style="padding: 20px; text-align: center;">
                    <h3 class="impact-title">Notre impact en chiffres</h3>
                    <div id="animated-stats" class="impact-stats">0+ clients satisfaits</div>
                    <script>
                        let count = 0;
                        const stats = document.getElementById('animated-stats');
                        function animateStats() {
                            if (count < 6984){
                                count += 10;
                                stats.textContent = count + "+ clients satisfaits";
                                requestAnimationFrame(animateStats);
                            }
                        }
                        animateStats();
                    </script>
                </section>
                

                <section class="table-container">
                    <h2 class="table-title">Données Immobilières en Tunisie</h2>
                    <table class="real-estate-table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Type de Bien</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Emplacement</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                        <td>1</td>
                        <td>Appartement</td>
                        <td>€100,000</td>
                        <td>Vente</td>
                        <td>Tunis Centre</td>
                        </tr>
                        <tr>
                        <td>2</td>
                        <td>Maison</td>
                        <td>€220,000</td>
                        <td>Location</td>
                        <td>La Marsa</td>
                        </tr>
                        <tr>
                        <td>3</td>
                        <td>Studio</td>
                        <td>€75,000</td>
                        <td>Vente</td>
                        <td>Manouba</td>
                        </tr>
                        <tr>
                        <td>4</td>
                        <td>Villa</td>
                        <td>€350,000</td>
                        <td>Vente</td>
                        <td>Hammamet</td>
                        </tr>
                        <tr>
                        <td>5</td>
                        <td>Local Commercial</td>
                        <td>€120,000</td>
                        <td>Location</td>
                        <td>Sfax</td>
                        </tr>
                        <tr>
                        <td>6</td>
                        <td>Terrain</td>
                        <td>€50,000</td>
                        <td>Vente</td>
                        <td>Bizerte</td>
                        </tr>
                    </tbody>
                    </table>
                </section>
                
                <script>
                    // Fade-in animation on table rows
                    const rows = document.querySelectorAll("#table-body tr");
                    rows.forEach((row, index) => {
                    row.style.opacity = 0;
                    row.style.transform = "translateY(20px)";
                    // setTimeout to stagger the animation
                    setTimeout(() => {
                        row.style.transition = "all 0.6s ease";
                        row.style.opacity = 1;
                        row.style.transform = "translateY(0)";
                    }, index * 150); // delay each row animation
                    });
                </script>

                <section class="cta-section">
                    <div class="cta-content">
                    <h2>Prêt à trouver votre futur chez-vous ?</h2>
                    <p>Contactez-nous dès aujourd'hui pour planifier une visite ou en savoir plus sur nos services.</p>
                    <a href="ContactUs.html" class="cta-button">Nous Contacter</a>
                    <br>
                    <br>
                    <a href="ListOfHouses.html" class="cta-button">Voir Nos Offres</a>
                    </div>
                </section>
                

                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    // Scroll Fade-in Animation
                    const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                        }
                    });
                    }, { threshold: 0.1 });
                
                    document.querySelectorAll("section").forEach(section => {
                    observer.observe(section);
                    });
                
                    // Count-up Animation for Properties Sold
                    const soldStat = document.getElementById("prop-sold");
                    let count2 = 0;
                
                    function animateSold() {
                    if (count2 < 350) {
                        count2 += 7;
                        soldStat.textContent = count2 + " propriétés vendues";
                        requestAnimationFrame(animateSold);
                    } else {
                        soldStat.textContent = "350 propriétés vendues";
                    }
                    }
                
                    if (soldStat) {
                    animateSold();
                    }
                
                    // Hover Grow Animation for Team Pictures
                    document.querySelectorAll(".team-member img").forEach(img => {
                    img.addEventListener("mouseenter", () => {
                        img.style.transform = "scale(1.1)";
                        img.style.transition = "transform 0.3s";
                    });
                    img.addEventListener("mouseleave", () => {
                        img.style.transform = "scale(1)";
                    });
                    });
                
                    // CTA Text Flashing Color
                    const cta = document.querySelector(".cta-section h2");
                    if (cta) {
                    let flash = true;
                    //set interval to change color every 700ms
                    setInterval(() => {
                        cta.style.color = flash ? "#B77B82" : "#1f212d";
                        flash = !flash;
                    }, 700);
                    }
                });
                </script>
                

                <script src="https://kit.fontawesome.com/075922b03a.js" crossorigin="anonymous"></script>
                <script src="app.js"></script>
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
</body>
</html>
