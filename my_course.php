<?php
require_once 'conn.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// 1. Fetch courses the student IS NOT registered in yet
$stmt_avail = $pdo->prepare("
    SELECT * FROM courses 
    WHERE course_id NOT IN (
        SELECT course_id FROM registrations WHERE user_id = ?
    )
");
$stmt_avail->execute([$student_id]);
$available_courses = $stmt_avail->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Registration – PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table thead { background: #1a3c5e; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4 text-center">Student Course Dashboard</h2>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <input type="text" id="search" class="form-control mb-3" placeholder="Search course...">

    <div class="card mb-5">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-journal-plus me-2"></i>Available Courses
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="courseTable">
                    <?php if (empty($available_courses)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No new courses available.</td></tr>
                    <?php else: ?>
                        <?php foreach($available_courses as $c): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($c['course_code']) ?></span></td>
                                <td class="text-start"><?= htmlspecialchars($c['course_name']) ?></td>
                                <td><?= $c['credit_hours'] ?></td>
                                <td>
                                    <a href="register_course.php?id=<?= $c['course_id'] ?>" class="btn btn-success btn-sm">
                                        Register
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="index.php" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<?php include 'footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("search").addEventListener("keyup", function() {
    let query = this.value;

    fetch("search_available.php?q=" + encodeURIComponent(query))
    .then(res => res.text())
    .then(data => {
        document.getElementById("courseTable").innerHTML = data;
    });
});
</script>

</body>
</html>