<?php
include "../config/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, assigned_year FROM mentors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $assigned_year);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['mentor_id'] = $id;
            $_SESSION['mentor_name'] = $name;
            $_SESSION['assigned_year'] = $assigned_year;
            header("Location: dashboard.php"); 
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Mentor not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mentor Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #28a745;
            margin-bottom: 20px;
        }
        label { font-weight: bold; color: #444; }
        input {
            width: 100%; padding: 10px; margin: 8px 0 15px 0;
            border: 1px solid #ccc; border-radius: 8px; outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40,167,69,0.4);
        }
        button {
            width: 100%; padding: 12px; background: #28a745; color: white;
            border: none; border-radius: 8px; font-size: 16px; cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #218838; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .link { text-align: center; margin-top: 10px; }
        .link a { color: #28a745; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mentor Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
       
    </div>
</body>
</html>
