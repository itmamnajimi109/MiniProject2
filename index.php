<?php
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

include 'header.php';
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - PCRS</title>
</head>
<body class="bg-light">

<div class="container">
    <div class="row text-center mb-4">
        <div class="col">
            <h1>Welcome to PCRS</h1>
            <p class="lead">Polytechnic Course Registration System</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <?php if ($role == 'admin'): ?>
            <div class="col-md-4 mb-3">
                <div class="card text-center border-primary shadow-sm h-100">
                    <div class="card-body">
                        <h5>Manage Courses</h5>
                        <p class="small text-muted">Add, update or delete courses.</p>
                        <a href="admin_dashboard.php" class="btn btn-primary">Manage</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-4 mb-3">
                <div class="card text-center border-info shadow-sm h-100">
                    <div class="card-body">
                        <h5>Register Courses</h5>
                        <p class="small text-muted">Register new courses for this semester.</p>
                        <a href="my_course.php" class="btn btn-info text-white">Register</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center border-warning shadow-sm h-100">
                    <div class="card-body">
                        <h5>My Course</h5>
                        <p class="small text-muted">Check or drop registered courses.</p>
                        <a href="view_course.php" class="btn btn-warning">View</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>