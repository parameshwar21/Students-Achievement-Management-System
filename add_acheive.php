<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION['student_reg_no'])) {
    header("Location: ../student/login.php");
    exit;
}

$reg_no = $_SESSION['student_reg_no'];
$student_name = $_SESSION['student_name'];

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event        = $_POST['event'];
    $title        = trim($_POST['title']);
    $description  = trim($_POST['description']);
    $created_at   = date("Y-m-d H:i:s");

    $certificate = null;
    if (!empty($_FILES['certificate']['name'])) {
        $targetDir = __DIR__ . "/uploads/";
        $webDir    = "uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $uniqueName = time() . "_" . basename($_FILES['certificate']['name']);
        $targetFile = $targetDir . $uniqueName;
        $certificate = $webDir . $uniqueName;

        if (!move_uploaded_file($_FILES['certificate']['tmp_name'], $targetFile)) {
            die("Error uploading file.");
        }
    }

    $stmt = $conn->prepare("INSERT INTO achievements
        (student_name, reg_no, event, title, description, certificate, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssss", 
        $student_name, 
        $reg_no, 
        $event, 
        $title, 
        $description, 
        $certificate, 
        $created_at
    );

    if ($stmt->execute()) {
        $success = "Achievement added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Achievement</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
          margin: 0;
          padding: 40px;
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
      }
      form {
          background: #fff;
          padding: 30px 35px;
          border-radius: 15px;
          box-shadow: 0 10px 25px rgba(0,0,0,0.2);
          width: 100%;
          max-width: 500px;
      }
      h2 {
          text-align: center;
          margin-bottom: 20px;
          color: #333;
      }
      label { font-weight: bold; margin-bottom: 6px; display:block; }
      input[type="text"], textarea, input[type="file"], select {
          width: 100%; padding: 12px; border: 1px solid #ccc;
          border-radius: 8px; margin-bottom: 15px; font-size: 14px;
          transition: 0.3s;
      }
      input:focus, textarea:focus, select:focus {
          border-color: #9b59b6;
          box-shadow: 0 0 8px rgba(155, 89, 182, 0.3);
      }
      button {
          width: 100%; padding: 12px; border: none;
          border-radius: 8px; background: #9b59b6; color: white;
          font-size: 16px; font-weight: bold; cursor: pointer;
          transition: 0.3s;
      }
      button:hover { background: #8e44ad; }
      .success { color: green; text-align: center; margin-bottom: 15px; }
      .error { color: red; text-align: center; margin-bottom: 15px; }
  </style>
</head>
<body>
  <form action="" method="POST" enctype="multipart/form-data">
      <h2>Add Achievement</h2>

      <?php
      if ($success) echo "<p class='success'>$success</p>";
      if ($error) echo "<p class='error'>$error</p>";
      ?>

      <label>Student Name</label>
      <input type="text" value="<?php echo htmlspecialchars($student_name); ?>" readonly>

      <label>Register No</label>
      <input type="text" value="<?php echo htmlspecialchars($reg_no); ?>" readonly>

      <label>Event</label>
      <select name="event" required>
          <option value="">--Select Event--</option>
          <option value="Hackathon">Hackathon</option>
          <option value="Symposium">Symposium</option>
          <option value="Sports">Sports</option>
          <option value="Cultural">Cultural</option>
          <option value="Seminar">Seminar</option>
          <option value="Workshop">Workshop</option>
          <option value="Course">Course</option>
          <option value="Others">Others</option>
      </select>

      <label>Title</label>
      <input type="text" name="title" required>

      <label>Description</label>
      <textarea name="description" rows="3" required></textarea>

      <label>Upload Certificate</label>
      <input type="file" name="certificate">

      <button type="submit">Add Achievement</button>
  </form>
</body>
</html>
