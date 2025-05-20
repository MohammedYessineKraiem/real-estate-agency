<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: LoginUser.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Initialize variables
$propertyId = isset($_GET['id']) ? $_GET['id'] : 0;
$offreNom = '';
$montant = 0;
$maxPersons = 0;

// Fetch property details if property ID is valid
if ($propertyId > 0) {
    $sql = "SELECT title, price FROM properties WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $offreNom = $row['title'];
        $montant = $row['price'];
    } else {
        echo "Property not found";
        // exit;  Removed exit, so the form can still load. The form will handle the missing data.
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch userId from session
    if (isset($_SESSION["user_id"])) {
        $userId = $_SESSION["user_id"];
    } else {
        // Handle the case where the user is not logged in or session is not set.
        echo "<script>alert('Please log in to make a reservation.'); window.location.href='LoginUser.php';</script>";
        exit; // Stop further execution
    }

    $propertyId = $_POST["propertyId"];
    $methodePaiement = $_POST["paiement"];
    $reservationDate = date("Y-m-d");
    $jourRv = $_POST["jour"];
    $dateDebut = $_POST["date_debut"];
    $dateFin = $_POST["date_fin"];
    $offreNom = $_POST["maison"];
    $nbPersonnes = $_POST["personnes"];
    $montant = $_POST["montant"];
    // $message = $_POST["message"]; // Removed message

    // Generate a new unique ID.  Use a simple approach, but a more robust method might be needed in a high-traffic environment.
    $newReservationId = rand(100000, 999999); // Example:  6-digit random number.  

    $sql = "INSERT INTO reservations (id, userId, propertyId, methodepaiment, reservationDate, jourRv, datedebut, datefin, offreNom, nbPersonnes, montant)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iiisssssisd", $newReservationId, $userId, $propertyId, $methodePaiement, $reservationDate, $jourRv, $dateDebut, $dateFin, $offreNom, $nbPersonnes, $montant);

    if ($stmt->execute()) {
        echo "<script>alert('Réservation enregistrée avec succès !  Reservation ID: " . $newReservationId . "'); window.location.href='ListOfHouses.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>






<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .wrapper {
            width: 80%;
            max-width: 1100px;
            background: rgba(0, 0, 0, 0.8);
            color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            margin-left: 150px;
            margin-bottom: 50px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            padding: 40px;
            background-color: #f0f2f5;
        }

        .form-container {
            background-color: #fff;
            padding: 35px 45px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4a90e2;
        }

        form label {
            display: block;
            margin-top: 14px;
            font-weight: 600;
            color: black;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        .radio-option {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 15px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: white;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .radio-option input[type="radio"] {
            display: none;
        }

        .radio-option.active {
            background-color: #e6f7ff;
            border-color: #2196F3;
            color: #0b355e;
        }

        .radio-option:hover {
            background-color: #f0f0f0;
        }

        .checkbox-group {
            margin-top: 8px;
            display: flex;
            flex-direction: column;
        }

        .checkbox-group label {
            font-weight: normal;
            margin-bottom: 6px;
        }

        .form-btn {
            width: 100%;
            padding: 14px;
            margin-top: 18px;
            font-size: 16px;
            background-color: #105e85;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        .form-btn:hover {
            transform: scale(1.03);
        }

        .form-btn:active {
            transform: scale(0.96);
        }

        .cancel-btn {
            background-color: #999;
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
                    <h3><?php echo $_SESSION['user_name']; ?></h3>
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
                <a href="LoginUser.html" class="btn-sign-off">
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
            <br>
            <br>
            <div class="ds-panel-content">
                <div class="wrapper">
                    <div class="form-container">
                        <h2>Réservation</h2>
                        <form id="reservationForm" method="post">
                            <input type="hidden" id="propertyId" name="propertyId" value="<?php echo $propertyId; ?>">
                            <input type="hidden" id="maison" name="maison" value="<?php echo $offreNom; ?>">
                            <input type="hidden" id="montant" name="montant" value="<?php echo $montant; ?>">

                            <label for="propertyId">Property ID:</label>
                            <input type="text"  value="<?php echo $propertyId; ?>" readonly>

                            <label for="maison">Nom de la maison :</label>
                            <input type="text" value="<?php echo $offreNom; ?>" readonly>

                            <label for="montant">Montant:</label>
                            <input type="text"  value="<?php echo $montant; ?>" readonly>

                            <label>Méthode de paiement :</label>
                            <div class="checkbox-group" id="paiementOptions">
                                <label class="radio-option">
                                    <input type="radio" name="paiement" value="Espèces">
                                    <span>Espèces</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="paiement" value="Carte Bancaire">
                                    <span>Carte Bancaire</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="paiement" value="Virement">
                                    <span>Virement</span>
                                </label>
                            </div>
                            <script>
                                const options = document.querySelectorAll('.radio-option');
                                options.forEach(option => {
                                    const input = option.querySelector('input[type="radio"]');
                                    input.addEventListener('change', () => {
                                        options.forEach(o => o.classList.remove('active'));
                                        option.classList.add('active');
                                    });
                                });
                            </script>

                            <label for="jour">Jour de rendez-vous suggéré :</label>
                            <select id="jour" name="jour" required>
                                <option value="">-- Choisir un jour --</option>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                                <option value="Dimanche">Dimanche</option>
                            </select>

                            <label for="date_debut">Date de début :</label>
                            <input type="date" id="date_debut" name="date_debut" required>

                            <label for="date_fin">Date de fin :</label>
                            <input type="date" id="date_fin" name="date_fin" required>

                            <label for="personnes">Nombre de personnes :</label>
                            <input type="number" id="personnes" name="personnes" min="1" placeholder="Ex: 4" required>

                            <label for="message">Message supplémentaire :</label>
                            <textarea id="message" name="message" placeholder="Écrivez vos remarques ou préférences ici..." rows="4"></textarea>

                            <button type="submit" class="form-btn" >Réserver</button>
                            <button type="button" class="form-btn cancel-btn" onclick="window.location.href='ListOfHouses.php'">Annuler</button>
                        </form>
                    </div>
                </div>
            </div>
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
    <script src="https://kit.fontawesome.com/075922b03a.js" crossorigin="anonymous"></script>
    <script src="app.js"></script>
</body>
</html>
