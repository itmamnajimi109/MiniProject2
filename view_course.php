<?php
require_once 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch registered courses ONLY
$stmt = $pdo->prepare("
    SELECT r.reg_id, c.course_id, c.course_name, c.credits
    FROM registrations r
    JOIN courses c ON r.course_id = c.course_id
    WHERE r.student_id = ?
");
$stmt->execute([$student_id]);
$my_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

    <div class="card">
        <div class="card-header bg-dark text-white fw-bold">
            <i class="bi bi-check-circle me-2"></i>My Registered Courses
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="courseTable">
                    <?php if (empty($my_courses)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">You have not registered for any courses yet.</td></tr>
                    <?php else: ?>
                        <?php foreach($my_courses as $row): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['course_id']) ?></strong></td>
                                <td><?= htmlspecialchars($row['course_name']) ?></td>
                                <td><?= $row['credits'] ?></td>
                                <td>
                                    <a href="drop_course.php?id=<?= $row['reg_id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Drop this course?')">
                                        Drop
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

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("search").addEventListener("keyup", function() {
    let query = this.value;

    fetch("search_course.php?q=" + query)
    .then(res => res.text())
    .then(data => {
        document.getElementById("courseTable").innerHTML = data;
    });
});
</script>

    
</body>
</html>