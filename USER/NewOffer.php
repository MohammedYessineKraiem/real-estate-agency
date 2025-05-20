<?php
session_start();

// Define $userName.
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bzbzd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $disponibilite = $_POST["disponibilite"];
    $superficie = $_POST["superficie"];
    $nbpiece = $_POST["nbpiece"];
    $location = $_POST["location"];
    $dateajout = $_POST["dateajout"];
    $type = $_POST["type"];
    $status = "available";
    $owner = $_SESSION['user_id'];

    // Handle image upload
    $image = $_FILES['image']['name']; // Get the name of the uploaded file
    $image_tmp_name = $_FILES['image']['tmp_name']; //get the tmp name
    $image_destination = "uploads/" . $image;  //set a destination

    //check if the file was selected
    if(!empty($image)){
        if (move_uploaded_file($image_tmp_name, $image_destination)) {
             // File uploaded successfully, store the file path
             $image_path = $image_destination;
        } else {
            echo "Failed to upload image.";
            $image_path = ""; // Set to empty if upload fails, or set a default image path
        }
    }
    else{
        $image_path = "";
    }


    // Prepare and execute the SQL statement
    $sql = "INSERT INTO properties (title, description, price, disponibilite, superficie, nbpiece, location, dateajout, image, type, status, owner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssdsssssssssi", $title, $description, $price, $disponibilite, $superficie, $nbpiece, $location, $dateajout, $image_path, $type, $status, $owner);

    if ($stmt->execute()) {
        echo "<script>alert('Property added successfully!'); window.location.href='ListOfHouses.html';</script>";
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
    <title>Add Property</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* ... (rest of your CSS styles) ... */
        /* Animation du background (léger zoom) */
        @keyframes backgroundZoom {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.05);
            }
        }

        /* Conteneur principal */
        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 1600px;
            margin-left: 50px;
            margin-top: 10px;
            border-radius: 12px;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section du formulaire */
        .form-container {
            width: 100%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.801);
            background: rgba(0, 0, 0, 0.5);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
            animation: slideIn 1s ease-in-out;
        }

        /* Animation du formulaire */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-container h2 {
            text-align: center;
            color: rgb(231 234 241);
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 13px;
            font-weight: bold;
            color: rgb(255, 255, 255);
            margin-bottom: -5px;
        }

        input, select, textarea {
            width: 95%;
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 13px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            outline: none;
            transition: 0.3s ease-in-out;
        }

        textarea {
            height: 60px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid black;
            box-shadow: 0 0 10px black;
            transform: scale(1.02);
        }

        button {
            background: rgb(230 235 248);
            color: #24313f;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            margin-top: 5px;
        }

        button:hover {
            background: rgb(58 70 105);
            box-shadow: 0 0 12px rgb(58 70 105);
            color: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            width: 95%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .wrapper {
                width: 95%;
            }
        }

        .wrapper {
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
            max-width: 1400px;
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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
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
            background-color: #a0aaaf;
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
                            <img src="assets/3135715.png" alt="">
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
                        <h1>Welcome to Our Rental Website</h1>
                        <p>Your ideal home is just a click away</p>
                    </div>
                </header>
            </div>
            <div class="ds-panel-content">
                <div class="wrapper">
                    <div class="form-container">
                        <h2>Add a Property</h2>
                        <form id="addPropertyForm" method="post" enctype="multipart/form-data">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" placeholder="Ex: Modern Apartment" required>

                            <label for="description">Description:</label>
                            <textarea id="description" name="description" placeholder="Describe the property here..." rows="4" required></textarea>

                            <label for="price">Price per month (DT):</label>
                            <input type="number" id="price" name="price" min="0" placeholder="Ex: 1200" required>

                            <label for="disponibilite">Availability:</label>
                            <select id="disponibilite" name="disponibilite" required>
                                <option value="">-- Select --</option>
                                <option value="Oui">Yes</option>
                                <option value="Non">No</option>
                            </select>

                            <label for="superficie">Area (m²):</label>
                            <input type="number" id="superficie" name="superficie" min="0" placeholder="Ex: 120" required>

                            <label for="nbpiece">Number of Rooms:</label>
                            <input type="number" id="nbpiece" name="nbpiece" min="1" placeholder="Ex: 3" required>

                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location" placeholder="Ex: Tunis, Lac 2" required>

                            <label for="dateajout">Date Added:</label>
                            <input type="date" id="dateajout" name="dateajout" required>

                            <label for="image">Image:</label>
                            <input type="file" id="image" name="image" accept="image/*" required>

                            <label for="type">Type:</label>
                            <input type="text" id="type" name="type" placeholder="Ex: Apartment, Villa" required>

                            <button type="submit" class="form-btn">Add Property</button>
                            <button type="button" class="form-btn cancel-btn" onclick="window.location.href='ListOfHouses.html'">Cancel</button>
                        </form>
                    </div>
                </div>
                <script>
                    document.getElementById("addPropertyForm").addEventListener("submit", function (e) {
                        e.preventDefault();

                        const fields = ["title", "description", "price", "disponibilite", "superficie", "nbpiece", "location", "dateajout", "image", "type"];
                        let isValid = true;

                        fields.forEach(id => {
                            const field = document.getElementById(id);
                            if (id === "image") { //handle image field
                                if (!field || !field.files[0]) {
                                    isValid = false;
                                }
                            }
                            else if (!field || !field.value.trim()) {
                                isValid = false;
                            }
                        });

                        if (!isValid) {
                            alert("Please fill in all fields and select an image before submitting the form.");
                            return;
                        }

                        this.submit();
                    });
                </script>
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
    <script src="app.js"></script>
</body>
</html>
