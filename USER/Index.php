<?php
// Start the session at the very beginning of the file
session_start();

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

$error = "";
$message = ""; // To display success/info messages

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $remember = isset($_POST["remember"]);

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Fetch user based on the email
        $stmt = $conn->prepare("SELECT id, password, name, prenom FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Verify the password 
                if ($password === $row['password']) {
                    // Password is correct, start session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'] . ' ' . $row['prenom'];
                    $_SESSION['user_email'] = $email; //store email

                    if ($remember) {
                         $cookie_name = "remember_user";
                         $cookie_value = $email;
                         $cookie_expiration = time() + (30 * 24 * 60 * 60);
                         setcookie($cookie_name, $cookie_value, $cookie_expiration, "/");
                    }
                    
                    $message = "Connexion réussie!";
                    header("Location: Acceuil.php");
                    exit();
                } else {
                    $error = "Adresse email ou mot de passe incorrect.";
                }
            } else {
                $error = "Adresse email ou mot de passe incorrect.";
            }
            $stmt->close();
        } else {
            $error = "Erreur SQL: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(to right, #1f212d 40%, transparent 96%),
                url('https://images.pexels.com/photos/2506923/pexels-photo-2506923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: contain;
            background-position: right;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            animation: fadeIn 1.5s ease;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1f212d;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #B77B82;
            box-shadow: 0 0 5px #B77B82;
        }

        .forgot-password {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .forgot-password a {
            color: #B77B82;
            text-decoration: none;
            cursor: pointer;
        }

        .login-btn {
            background-color: #1f212d;
            color: #fff;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background-color: #33354a;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
            display: none;
            animation: fadeIn 0.5s;
        }
        .error-message {
            text-align: center;
            margin-top: 20px;
            color: red;
            display: none;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Se connecter</h2>
        <form id="loginForm" method="POST" action="">
            <div class="input-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@mail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Votre mot de passe" />
            </div>

            <div class="forgot-password">
                <label><input type="checkbox" id="remember" name="remember" <?php echo isset($_POST['remember']) ? 'checked' : ''; ?> /> Se souvenir de moi</label>
                <a id="forgotPasswordLink" href="forgot_password.php">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="login-btn">Connexion</button>
        </form>
        <div class="message" id="messageBox" style="<?php echo !empty($message) ? 'display: block; color: green;' : ''; ?>"><?php echo $message; ?></div>
        <div class="error-message" id="errorBox" style="<?php echo !empty($error) ? 'display: block; color: red;' : ''; ?>"><?php echo $error; ?></div>

    </div>

    <script>
        const form = document.getElementById('loginForm');
        const messageBox = document.getElementById('messageBox');
        const errorBox = document.getElementById('errorBox');
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');


        function showMessage(msg, type) {
            const box = type === 'red' ? errorBox : messageBox;
            box.textContent = msg;
            box.style.display = 'block';
            box.style.color = type;
            box.style.animation = 'fadeIn 0.5s';
        }


        forgotPasswordLink.addEventListener('click', () => {
            const email = prompt("Veuillez entrer votre adresse email pour réinitialiser le mot de passe:");
            if (email && email.includes('@')) {
                showMessage("Un lien de réinitialisation a été envoyé à " + email, 'green');
            } else {
                showMessage("Adresse email invalide", 'red');
            }
        });
    </script>
</body>
</html>
