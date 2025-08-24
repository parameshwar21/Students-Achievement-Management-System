<?php
include "../config/db.php";
$message = "";
$type = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM achievements WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Achievement deleted successfully!";
        $type = "success";
    } else {
        $message = "Error deleting record: " . $stmt->error;
        $type = "error";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Achievement</title>
    <style>
        /* General Page Style */
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Container */
.container {
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    width: 500px;
    text-align: center;
}

/* Success Alert */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    font-weight: bold;
}

/* Error Alert */
.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    font-weight: bold;
}

/* Button */
button, a.btn {
    display: inline-block;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
    margin-top: 10px;
    cursor: pointer;
}

.btn-primary {
    background: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #a71d2a;
}

    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert-<?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
            <a href="index.php" class="btn btn-primary">Back to Achievements</a>
        <?php else: ?>
            <div class="alert-error">No achievement selected for deletion.</div>
            <a href="index.php" class="btn btn-primary">Back to Achievements</a>
        <?php endif; ?>
    </div>
</body>
</html>
