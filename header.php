<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCRS System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">PCRS System</a>

        <span class="navbar-text text-white">
            Log masuk sebagai: 
            <strong><?= strtoupper(htmlspecialchars($role)) ?></strong> 
            (<?= htmlspecialchars($username) ?>)
        </span>

        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>
