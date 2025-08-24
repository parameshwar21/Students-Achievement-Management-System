
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Achievements Management System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      text-align: center;
    }
    .logo {
      width: 100px;
      height: auto;
      display: block;
      margin: 0 auto 10px;
    }
    .app-name {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .box {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 300px;
      margin: 0 auto;
    }
    .college-name {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }
    .btn {
      display: block;
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      background: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      transition: 0.3s;
    }
    .btn:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="assets/logo.png" alt="App Logo" class="logo">
    <div class="app-name">Achieva</div>

    <div class="box">
      <div class="college-name">Nadar Saraswathi College of Engineering and Technology</div>
      <a href="student/register.php" class="btn">Student </a>
      <a href="mentor/register.php" class="btn">Mentor</a>
    </div>
  </div>
</body>
</html>
