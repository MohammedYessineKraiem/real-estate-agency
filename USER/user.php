<?php
session_start(); // Start the session at the very beginning of the file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: LoginUser.php"); // Make sure this is the correct path to your login page
    exit();
}

// If the user is logged in, we can access their information from the session
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];
$userEmail = $_SESSION['user_email'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; //  IMPORTANT:  Use a strong password in production!
$dbname = "bzbzd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error); //  Use die() for fatal errors
}



// Fetch user data from the database
$sql = "SELECT name, prenom, city, country, codepostal FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $userId); // "i" for integer
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error executing statement: " . $stmt->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_first_name'] = $row['prenom'];
    $_SESSION['user_last_name'] = $row['name'];
    $_SESSION['user_city'] = $row['city'];
    $_SESSION['user_country'] = $row['country'];
    $_SESSION['user_postal_code'] = $row['codepostal'];
    
    $userFirstName = $row['prenom']; // Consistent variable name
    $userLastName = $row['name'];     // Consistent variable name
    $userCity = $row['city'];
    $userCountry = $row['country'];
    $userPostalCode = $row['codepostal'];
    $userAddress = $userCity . ", " . $userCountry; // Improved address
} else {
    $error = "No user found with this ID";
}



// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect updated values from the form
    $updatedFirstName = $_POST['first_name'];
    $updatedLastName = $_POST['last_name'];
    $updatedCity = $_POST['city'];
    $updatedCountry = $_POST['country'];
    $updatedPostalCode = $_POST['postal_code'];
    $updatedEmail = $_POST['email'];  // Added email to be updated

    // Prepare and execute the SQL UPDATE statement
    $updateSql = "UPDATE users SET 
                    name = ?, 
                    prenom = ?, 
                    city = ?, 
                    country = ?, 
                    codepostal = ?,
                    email = ?
                WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt === false) {
        die("Error preparing update statement: " . $conn->error);
    }

    $updateStmt->bind_param("ssssssi", 
                            $updatedLastName, $updatedFirstName, $updatedCity, $updatedCountry, $updatedPostalCode, $updatedEmail, $userId); // Correct order, removed username

    if ($updateStmt->execute()) {
        // Update successful, update session variables
        $_SESSION['user_email'] = $updatedEmail;
        $_SESSION['user_first_name'] = $updatedFirstName;
        $_SESSION['user_last_name'] = $updatedLastName;
        $_SESSION['user_city'] = $updatedCity;
        $_SESSION['user_country'] = $updatedCountry;
        $_SESSION['user_postal_code'] = $updatedPostalCode;
        $successMessage = "Profile updated successfully!";
        
        // Refresh the page, to show the updated data.
        header("Location: user.php");
        exit();

    } else {
        $errorMessage = "Error updating profile: " . $updateStmt->error;
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* Main layout */
        .user-dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        /* Left menu styles (from acceuil.html) */
        .ds-left-menu {
            width: 250px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
            position: relative;
            z-index: 10;
        }
        
        .btn-menu {
            background: none;
            border: none;
            color: #007BFF;
            font-size: 20px;
            cursor: pointer;
            align-self: flex-end;
        }
        
        .ds-perfil {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        
        .ds-perfil .foto {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
        }
        
        .ds-perfil .foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .info-perfil span {
            color: #999;
            font-size: 14px;
        }
        
        .info-perfil h3 {
            color: #333;
            font-size: 16px;
        }
        
        .ds-menu ul {
            list-style: none;
            padding: 0;
        }
        
        .ds-menu ul li {
            margin-bottom: 10px;
        }
        
        .ds-menu ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .ds-menu ul li a:hover {
            background-color: #f0f0f0;
            color: #007BFF;
        }
        
        .ds-menu ul li a i {
            margin-right: 10px;
            min-width: 20px;
            text-align: center;
        }
        
        /* Sign-off specifically for user.html */
        .user-dashboard .ds-left-menu .sign-off {
            position: absolute;
            bottom: 0;
            background: #f4f4f4;
            width: calc(100% - 40px);
            margin: auto;
            border-top: 1px solid #252628;
            padding: 15px 0px;
        }
        
        /* Sign-off button specifically for user.html */
        .user-dashboard .ds-left-menu .sign-off a.btn-sign-off {
            cursor: pointer;
            color: #161719;
            background: transparent;
            width: 100%;
            display: block;
            padding: 10px;
            border-radius: 4px;
        }
        
        .btn-sign-off:hover {
            background-color: #ffeeee;
            color: #ff3b3b;
        }
        
        .btn-sign-off i {
            margin-right: 10px;
        }
        
        /* Main content adjustments */
        .main-content {
            flex: 1;
            padding: 0;
        }
        
        /* Background image for header */
        .header {
            position: relative;
            padding: 100px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1073&q=80');
            background-size: cover;
            background-position: center;
            color: white;
        }
        
        /* Modern footer styles (from acceuil.html) */
        .modern-footer {
            background-color: #333;
            color: #fff;
            padding: 40px 20px 20px;
            margin-top: 40px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .footer-brand h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .footer-brand p {
            line-height: 1.6;
            color: #ccc;
        }
        
        .footer-contact h3,
        .footer-links h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .footer-contact ul,
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-contact li,
        .footer-links li {
            margin-bottom: 10px;
            color: #ccc;
        }
        
        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #007BFF;
        }
        
        .footer-bottom {
            border-top: 1px solid #444;
            padding-top: 20px;
            text-align: center;
            color: #999;
        }
        
        /* Responsive design */
        @media (max-width: 991px) {
            .user-dashboard {
                flex-direction: column;
            }
            
            .ds-left-menu {
                width: 100%;
                position: static;
            }
            
            .user-dashboard .ds-left-menu .sign-off {
                position: static;
                width: 100%;
            }
            
            .profile-card, .col-xl-8 {
                width: 100%;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="user-dashboard">
        <div class="ds-left-menu">
            <button class="btn-menu">
                <i class="fa-solid fa-circle-chevron-left"></i>
            </button>

            <div class="ds-perfil">
                <div class="foto">
                    <img src="assets/3135715.png" alt="">
                </div>
                <div class="info-perfil">
                    <span>User</span>
                    <h3><?php echo $userName; ?></h3>
                </div>
            </div>

            <div class="ds-menu">
                <ul>
                    <li><a href="acceuil.php"><i class="fa-solid fa-home"></i><span>Home</span></a></li>
                    <li><a href="MyHouses.php"><i class="fa-solid fa-gift"></i><span>My Houses</span></a></li>

                </ul>
            </div>

            <div class="sign-off">
                <a href="LoginUser.php" class="btn-sign-off">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>log out</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            <div class="header d-flex align-items-center">
                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-8" style="padding-left: 30px;">
                            <h1 class="text-white">Welcome, <?php echo $userName; ?></h1>
                            <p class="text-white">Manage your profile and explore features with ease.</p>
                            <div class="btn-group">
                                <a href="#!" class="btn btn-primary">Edit Profile</a>
                                <a href="#!" class="btn btn-light">Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid mt--7">
                <div class="row">
                    <div class="profile-card">
                        <div class="card">
                            <div class="profile-image">
                                <a href="#">
                                    <img src="user.jpg" alt="Profile Image" class="rounded-circle">
                                </a>
                            </div>
                            <div class="card-header">
                                <div class="buttons">
                                    <a href="#" class="btn btn-connect">Connect</a>
                                    <a href="#" class="btn btn-message">Message</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="profile-info">
                                    <h3><?php echo $userName; ?></h3>
                                    <p><i class="icon">📍</i> <?php echo $userAddress; ?></p>
                                    <p><i class="icon">💼</i> Active User</p>
                                    <p><i class="icon">🎓</i> ENSI-Mannouba </p>
                                    <hr>
                                    <p>----------------</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">My account</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <h6 class="heading-medium text-muted mb-4">User Information</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-username">Username</label>
                                                    <input type="text" id="input-username" name="username" class="form-control form-control-alternative" placeholder="Username" value="<?php echo $userName; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-email">Email address</label>
                                                    <input type="email" id="input-email" name="email" class="form-control form-control-alternative" placeholder="user@example.com" value="<?php echo $userEmail; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-first-name">First name</label>
                                                    <input type="text" id="input-first-name" name="first_name" class="form-control form-control-alternative" placeholder="First name" value="<?php  echo $userFirstName; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-last-name">Last name</label>
                                                    <input type="text" id="input-last-name" name="last_name" class="form-control form-control-alternative" placeholder="Last name" value="<?php echo $userLastName; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    
                                    <h6 class="heading-medium text-muted mb-4">Contact Information</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-address">Address</label>
                                                    <input id="input-address" class="form-control form-control-alternative" placeholder="Home Address" value="<?php echo $userAddress; ?>" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-city">City</label>
                                                    <input type="text" id="input-city" name="city" class="form-control form-control-alternative" placeholder="City" value="<?php echo $userCity; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-country">Country</label>
                                                    <input type="text" id="input-country" name="country" class="form-control form-control-alternative" placeholder="Country" value="<?php echo $userCountry; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-postal-code">Postal code</label>
                                                    <input type="number" id="input-postal-code" name="postal_code" class="form-control form-control-alternative" placeholder="Postal code"  value="<?php echo $userPostalCode; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Update Profile</button>
                                </form>
                                <?php
                                    if(isset($successMessage)){
                                        echo "<div class='alert alert-success mt-3' role='alert'>$successMessage</div>";
                                    }
                                    if(isset($errorMessage)){
                                        echo "<div class='alert alert-danger mt-3' role='alert'>$errorMessage</div>";
                                    }
                                ?>
                            </div>
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
    </div>

    <script src="https://kit.fontawesome.com/075922b03a.js" crossorigin="anonymous"></script>
    <script src="app.js"></script>
</body>
</html>
