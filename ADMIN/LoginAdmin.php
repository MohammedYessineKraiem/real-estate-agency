<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // MySQL password
$dbname = "bzbzd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"] ?? '';
    $admincode = trim($_POST["admincode"] ?? '');

    if (empty($password) || empty($admincode)) {
        $error = "Please fill in all fields.";
    } elseif ($admincode !== "ninir") {
        $error = "Incorrect administrator code.";
    } else {
        // Fetch admin user based on the admin status
        $stmt = $conn->prepare("SELECT id, name, prenom, password FROM users WHERE isAdmin = 1");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            $loggedIn = false;
            while ($row = $result->fetch_assoc()) {
                // Directly compare the entered password with the database password (INSECURE!)
                if ($password === $row['password']) {
                    // Password matches for *this* admin user
                    $loggedIn = true;
                    session_start();
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['name'] . ' ' . $row['prenom'];

                    header("Location: MainPage.php");
                    exit();
                }
            }
            if ($isAdmin && password_verify($_POST['password'], $adminData['Password'])) {
                $_SESSION['admin_id'] = $adminData['id']; // Store the admin's ID in the session
                // Redirect to the admin dashboard or the password change page
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Identifiants incorrects.";
            }

            // This error message is now more accurate: it means no *administrator's* password matched.
            if (!$loggedIn) {
                $error = "Incorrect password for an administrator.";
            }

            $stmt->close();
        } else {
            $error = "SQL Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #081c15; /* Very dark background */
            color: #f1fbe9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background-color: #1b4332; /* Dark green */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
            width: 350px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #f1fbe9;
            font-size: 24px;
        }

        .login-box input[type="password"],
        .login-box input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #40916c;
            background-color: #f1fbe9;
            color: #081c15;
            font-size: 15px;
        }

        .login-box input[type="submit"] {
            background-color: #40916c;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-box input[type="submit"]:hover {
            background-color: #52b788;
        }

        /* Alert box for errors */
        .alert {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            background-color: #ffccd5;
            color: #720026;
            font-size: 14px;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Keep your existing styling */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { height: 100vh; background: linear-gradient(to right, #004d4d 40%, transparent 96%), url('https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1600') no-repeat center center/cover; display: flex; justify-content: center; align-items: center; background-size: cover; background-position: right; }
        .login-container { background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25); width: 400px; animation: fadeIn 1.2s ease-in-out; }
        .login-container h2 { text-align: center; margin-bottom: 24px; color: #004d4d; }
        .input-group { margin-bottom: 18px; position: relative; }
        .input-group label { display: block; margin-bottom: 6px; color: #333; }
        .input-group input { width: 100%; padding: 12px; border: 2px solid #ccc; border-radius: 8px; outline: none; transition: 0.3s; }
        .input-group input:focus { border-color: #009999; box-shadow: 0 0 5px #00cccc; }
        .login-btn { background-color: #007777; color: #fff; width: 100%; padding: 12px; border: none; border-radius: 10px; font-size: 1em; cursor: pointer; transition: background 0.3s; }
        .login-btn:hover { background-color: #009999; }
        .message { text-align: center; margin-top: 20px; display: none; font-weight: bold; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <form method="POST" action="">
        <input type="password" name="password" placeholder="Password"><br>
        <input type="text" name="admincode" placeholder="Administrator Code"><br>
        <input type="submit" value="Login">
    </form>

    <?php if (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>


<script src="MainPage.js"></script>
</body>
</html>


