<?php
require_once 'conn.php';

/* ── Auth guard ── */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

/* ── POST HANDLERS ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_course') {
        $code    = trim($_POST['course_code'] ?? '');
        $name    = trim($_POST['course_name'] ?? '');
        $credits = (int)($_POST['credit_hours'] ?? 0);

        if ($code && $name) {
            try {
                $pdo->prepare("
                    INSERT INTO courses (course_code, course_name, credit_hours)
                    VALUES (?, ?, ?)
                ")->execute([$code, $name, $credits]);

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => "Course <strong>$code</strong> added successfully."
                ];
            } catch (PDOException $e) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Something went wrong.'
                ];
            }
        }

        header('Location: admin_dashboard.php');
        exit;
    }
}

$courses = $pdo->query("SELECT * FROM courses ORDER BY course_code")->fetchAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses – PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #1a3c5e; --accent: #f0a500; }
        body { background: #f4f7fb; font-family: 'Segoe UI', sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .table thead th { background: var(--primary); color: #fff; }
        .page-title { color: var(--primary); font-weight: 700; border-left: 4px solid var(--accent); padding-left: 10px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Course Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="bi bi-plus-circle me-1"></i>Add New Course
        </button>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <?php if (empty($courses)): ?>
                <p class="text-muted p-4 mb-0">No courses yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Credit Hours</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($c['course_code']) ?></span></td>
                            <td><?= htmlspecialchars($c['course_name']) ?></td>
                            <td><?= (int)$c['credit_hours'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-icon me-1 edit-btn" 
                                        data-id="<?= $c['course_id'] ?>"
                                        data-code="<?= htmlspecialchars($c['course_code'], ENT_QUOTES) ?>"
                                        data-name="<?= htmlspecialchars($c['course_name'], ENT_QUOTES) ?>"
                                        data-credits="<?= (int)$c['credit_hours'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#editCourseModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-icon delete-btn"
                                        data-id="<?= $c['course_id'] ?>"
                                        data-code="<?= htmlspecialchars($c['course_code'], ENT_QUOTES) ?>"
                                        data-name="<?= htmlspecialchars($c['course_name'], ENT_QUOTES) ?>"
                                        data-bs-toggle="modal" data-bs-target="#deleteCourseModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="add_course">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course Code *</label>
                        <input type="text" name="course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name *</label>
                        <input type="text" name="course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credit Hours</label>
                        <input type="number" name="credit_hours" class="form-control" value="3" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="edit_course">
                <input type="hidden" name="course_id" id="edit_course_id">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course Code *</label>
                        <input type="text" name="course_code" id="edit_course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name *</label>
                        <input type="text" name="course_name" id="edit_course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credit Hours</label>
                        <input type="number" name="credit_hours" id="edit_credit_hours" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="delete_course">
                <input type="hidden" name="course_id" id="delete_course_id">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete Course</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="delete_course_label"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_course_id').value    = btn.dataset.id;
        document.getElementById('edit_course_code').value  = btn.dataset.code;
        document.getElementById('edit_course_name').value  = btn.dataset.name;
        document.getElementById('edit_credit_hours').value = btn.dataset.credits;
    });
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('delete_course_id').value = btn.dataset.id;
        document.getElementById('delete_course_label').textContent = btn.dataset.code;
    });
});
</script>
</body>
</html>