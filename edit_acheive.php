<?php
include "../config/db.php";

if (!isset($_GET['id'])) { die("No ID provided"); }
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM achievements WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$achievement = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_no = $_POST['reg_no'];
    $student_name = $_POST['student_name'];
    $event = $_POST['event'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $certificate = $achievement['certificate'];
    if (!empty($_FILES['certificate']['name'])) {
        $certificate = time() . "_" . basename($_FILES['certificate']['name']);
        move_uploaded_file($_FILES['certificate']['tmp_name'], "uploads/" . $certificate);
    }

    $stmt = $conn->prepare("UPDATE achievements SET reg_no=?, student_name=?, event=?, title=?, description=?, certificate=? WHERE id=?");
    $stmt->bind_param("ssssssi", $reg_no, $student_name, $event, $title, $description, $certificate, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Achievement updated!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Achievement</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:40px; display:flex; justify-content:center; }
        form { background:#fff; padding:25px 30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); width:400px; }
        h2 { text-align:center; margin-bottom:20px; }
        label { font-weight:bold; margin:6px 0; display:block; }
        input, textarea, select { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ddd; border-radius:8px; }
        button { width:100%; padding:12px; border:none; border-radius:8px; background:#28a745; color:white; font-weight:bold; cursor:pointer; }
        button:hover { background:#218838; }
    </style>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <h2>Edit Achievement</h2>
    <label>Reg No</label>
    <input type="number" name="reg_no" value="<?= $achievement['reg_no'] ?>" required>
    <label>Student Name</label>
    <input type="text" name="student_name" value="<?= $achievement['student_name'] ?>" required>
    <label>Event</label>
    <select name="event" required>
        <option <?= ($achievement['event']=="Hackathon")?"selected":"" ?>>Hackathon</option>
        <option <?= ($achievement['event']=="Symposium")?"selected":"" ?>>Symposium</option>
        <option <?= ($achievement['event']=="Sports")?"selected":"" ?>>Sports</option>
        <option <?= ($achievement['event']=="Cultural")?"selected":"" ?>>Cultural</option>
        <option <?= ($achievement['event']=="Seminar")?"selected":"" ?>>Seminar</option>
        <option <?= ($achievement['event']=="Workshop")?"selected":"" ?>>Workshop</option>
        <option <?= ($achievement['event']=="Course")?"selected":"" ?>>Course</option>
        <option <?= ($achievement['event']=="Others")?"selected":"" ?>>Others</option>
    </select>
    <label>Title</label>
    <input type="text" name="title" value="<?= $achievement['title'] ?>" required>
    <label>Description</label>
    <textarea name="description" rows="3"><?= $achievement['description'] ?></textarea>
    <label>Certificate</label>
    
    <input type="file" name="certificate">
    <button type="submit">Update</button>
</form>
</body>
</html>
