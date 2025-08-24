<?php
session_start();
include "../config/db.php";

if (empty($_SESSION['mentor_id'])) {
    header("Location: login.php");
    exit;
}

$mentor_id = (int)$_SESSION['mentor_id'];

// Get the assigned year of this mentor
$stmtYear = $conn->prepare("SELECT assigned_year FROM mentors WHERE id = ?");
$stmtYear->bind_param("i", $mentor_id);
$stmtYear->execute();
$stmtYear->bind_result($assigned_year);
$stmtYear->fetch();
$stmtYear->close();

if (empty($assigned_year)) {
    die("Mentor has no assigned year set.");
}

$sql = "
    SELECT a.id, a.event, a.title, a.description, a.certificate, s.reg_no, s.name AS student_name
    FROM achievements a
    INNER JOIN students s ON a.reg_no = s.reg_no
    WHERE s.year = ?
    ORDER BY a.id DESC
";
$stmt = $conn->prepare($sql);
if (!$stmt) die("SQL Error: " . $conn->error);

$stmt->bind_param("s", $assigned_year);
$stmt->execute();
$result = $stmt->get_result();

$baseCertificateURL = "/Students_Achieva/achievements/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mentor Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f7f9; margin:0; }
header { background:#2c3e50; color:#fff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; }
header h1 { margin:0; font-size:20px; }
header a.btn { padding:8px 12px; background:#e74c3c; color:#fff; border-radius:6px; text-decoration:none; }
header a.btn:hover { background:#c0392b; }
.wrap { padding:20px; }
h2 { margin-bottom:15px; }
table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.08); }
th, td { padding:12px 10px; border:1px solid #e5e7eb; text-align:left; vertical-align:middle; }
th { background:#3498db; color:#fff; }
tr:nth-child(even) { background:#f9fbfd; }
a.view-btn { padding:6px 10px; background:#007bff; color:#fff; border-radius:4px; text-decoration:none; }
a.view-btn:hover { background:#0056b3; }
.muted { color:#777; }
</style>
</head>
<body>
<header>
    <h1>Mentor Dashboard — Year <?= htmlspecialchars($assigned_year) ?></h1>
    <a class="btn" href="logout.php">Logout</a>
</header>

<div class="wrap">
    <h2>Students & Achievements (Year <?= htmlspecialchars($assigned_year) ?>)</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Reg No</th>
            <th>Student Name</th>
            <th>Event</th>
            <th>Title</th>
            <th>Description</th>
            <th>Certificate</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['reg_no']) ?></td>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['event']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <?php if (!empty($row['certificate'])): ?>
                        <a href="<?= $baseCertificateURL . htmlspecialchars($row['certificate']) ?>" target="_blank" class="view-btn">View</a>
                    <?php else: ?>
                        <span class="muted">No File</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="muted" style="text-align:center;">No achievements found for this year.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
