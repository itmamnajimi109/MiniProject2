<?php
require_once 'conn.php';

$student_id = $_SESSION['user_id'];
$q = $_GET['q'] ?? '';

$stmt = $pdo->prepare("
    SELECT * FROM courses 
    WHERE course_id NOT IN (
        SELECT course_id FROM registrations WHERE student_id = ?
    )
    AND (course_id LIKE ? OR course_name LIKE ?)
");

$search = "%$q%";
$stmt->execute([$student_id, $search, $search]);

$courses = $stmt->fetchAll();

if (empty($courses)) {
    echo "<tr>
        <td colspan='4' class='text-center text-muted py-3'>
            No courses match your search.
        </td>
    </tr>";
} else {
    foreach ($courses as $c) {
        echo "<tr>
            <td><span class='badge bg-secondary'>" . htmlspecialchars($c['course_id']) . "</span></td>
            <td class='text-start'>" . htmlspecialchars($c['course_name']) . "</td>
            <td>" . $c['credits'] . "</td>
            <td>
                <a href='register_course.php?id=" . $c['course_id'] . "' 
                   class='btn btn-success btn-sm'>
                    Register
                </a>
            </td>
        </tr>";
    }
}
