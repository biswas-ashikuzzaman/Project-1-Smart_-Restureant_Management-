<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - POS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>স্বাগতম, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h1>
    <p>তোমার রোল: <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>

    <a href="../api/logout.php">Logout</a>

    <!-- এখানে POS ড্যাশবোর্ড UI কোড যাবে -->
</body>
</html>
