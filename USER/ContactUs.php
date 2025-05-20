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
    <title>Contact</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="ContactUsStyle.css">
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
                <section class="contact-container">
                    <div class="contact-info">
                        <h2>NOUS SOMMES LÀ POUR VOUS !</h2>
                        <p>📍 Adresse : Immeuble Constance, Rue du Lac Ammar, Les Berges du Lac, 1053 Tunis</p>
                        <p>📧 Email : idealmmobiliere12@gmail.com</p>
                        <p>📞 Portable : +216 53 540 819 / +216 53 679 529</p>
                        <h3>📅 Horaires</h3>
                        <p><strong>Du lundi au vendredi :</strong> 9h30 - 12h30 / 13h30 - 17h</p>
                        <p><strong>Après 17h :</strong> Sur rendez-vous</p>
                        <p><strong>Week-end et jours fériés :</strong> Sur rendez-vous</p>
                    </div>
                
                    <div class="contact-form">
                        <h2>Envoyez-nous un message</h2>
                        <form onsubmit="return confirm('Bien reçu !');">
                            <div class="form-group">
                                <input type="text" placeholder="Prénom" required>
                                <input type="text" placeholder="Nom" required>
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email" required>
                                <input type="tel" placeholder="Téléphone" required>
                            </div>
                            <input type="text" placeholder="Objet du message" required>
                            <textarea placeholder="Votre message..." rows="5" maxlength="500" oninput="updateCharCount(this)"></textarea>
                            <p id="char-count">0 / 500 caractères</p>
                            <div class="form-group checkbox-group">
                                <label class="custom-checkbox">
                                    <input type="checkbox" required>
                                    <span class="checkmark"></span>
                                    J’accepte les <a href="#" target="_blank">termes et conditions</a>
                                </label>
                            </div>                                        
                            <button type="submit" class="btn-submit">Envoyer</button>
                        </form>
                    </div>
                </section>
                <script>
                // 1. Fade-in on scroll
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.3 });

                document.querySelectorAll('.contact-container, .map-section').forEach(el => {
                    el.classList.add('pre-fade');
                    observer.observe(el);
                });

                // 2. Ripple effect on button click
                document.querySelectorAll("button").forEach(button => {
                    button.addEventListener("click", function (e) {
                        const ripple = document.createElement("span");
                        ripple.className = "ripple";
                        ripple.style.left = `${e.offsetX}px`;
                        ripple.style.top = `${e.offsetY}px`;
                        this.appendChild(ripple);
                        setTimeout(() => ripple.remove(), 600);
                    });
                });
            </script>

                <script>
                    function updateCharCount(textarea) {
                        const count = textarea.value.length;
                        document.getElementById("char-count").textContent = `${count} / 500 caractères`;
                    }
                </script>

                <section class="map-section">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3188.5060551088194!2d10.243894615145122!3d36.83030837994442!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd331314e8af7d%3A0x8a6bfb5b4b4d13d5!2sRue%20du%20Lac%20Ammar%2C%20Tunis%2C%20Tunisie!5e0!3m2!1sfr!2stn!4v1614270420647"
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </section>

            </div>
        </div>

    </div>

</body>
</html>
