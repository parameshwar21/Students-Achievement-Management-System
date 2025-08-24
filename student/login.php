<?php
include "../config/db.php"; 
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reg_no = trim($_POST['reg_no']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT name, password FROM students WHERE reg_no = ?");
    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session
            $_SESSION['student_name'] = $name;
            $_SESSION['student_reg_no'] = $reg_no;
            header("Location: ../achievements/index.php"); 
            exit;
        } else {
            $error = "Invalid registration number or password!";
        }
    } else {
        $error = "Invalid registration number or password!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: 600;
        }
        label { font-weight: bold; color: #444; }
        input {
            width: 100%; padding: 12px 15px; margin: 12px 0;
            border-radius: 8px; border: 1px solid #ccc; outline: none;
            font-size: 16px; transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 8px rgba(155, 89, 182, 0.3);
        }
        button {
            width: 100%; padding: 12px; background: #9b59b6; color: white;
            border: none; border-radius: 8px; font-size: 16px; cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background: #8e44ad;
            transform: translateY(-2px);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #9b59b6;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        @media(max-width: 480px){
            .container { padding: 30px 20px; }
            h2 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Registration No:</label>
            <input type="text" name="reg_no" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        
    </div>
</body>
</html>
