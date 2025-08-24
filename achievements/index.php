<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION['student_reg_no'])) {
    header("Location: ../student/login.php");
    exit;
}

$reg_no = $_SESSION['student_reg_no'];

$stmt = $conn->prepare("SELECT * FROM achievements WHERE reg_no = ? ORDER BY id DESC");
$stmt->bind_param("s", $reg_no);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Achievements</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; background:#f4f6f9; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { padding:12px; border:1px solid #ddd; text-align:center; }
        th { background:#007bff; color:white; }
        a.btn { padding:6px 12px; border-radius:6px; text-decoration:none; color:white; }
        .edit { background:#28a745; }
        .delete { background:#dc3545; }
        .add { background:#007bff; margin-bottom:15px; display:inline-block; }
    </style>
</head>
<body>
    <h2>My Achievements</h2>
    <a href="add_acheive.php" class="btn add">+ Add Achievement</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Reg No</th>
            <th>Event</th>
            <th>Title</th>
            <th>Description</th>
            <th>Certificate</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['reg_no']) ?></td>
            <td><?= htmlspecialchars($row['event']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
                <?php if(!empty($row['certificate'])): ?>
                    <a href="<?= htmlspecialchars($row['certificate']) ?>" target="_blank">View</a>
                <?php else: ?>
                    No File
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_acheive.php?id=<?= $row['id'] ?>" class="btn edit">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn delete" onclick="return confirm('Delete this record?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
