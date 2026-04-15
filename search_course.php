<?php
require_once 'conn.php';

$student_id = $_SESSION['user_id'];
$q = $_GET['q'] ?? '';

$stmt = $pdo->prepare("
    SELECT r.reg_id, c.course_id, c.course_name, c.credits 
    FROM registrations r
    JOIN courses c ON r.course_id = c.course_id
    WHERE r.student_id = ?
    AND (c.course_id LIKE ? OR c.course_name LIKE ?)
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
    foreach ($courses as $row) {
        echo "<tr>
            <td><strong>" . htmlspecialchars($row['course_id']) . "</strong></td>
            <td class='text-start'>" . htmlspecialchars($row['course_name']) . "</td>
            <td>" . $row['credits'] . "</td>
            <td>
                <a href='drop_course.php?id=" . $row['reg_id'] . "' 
                   class='btn btn-danger btn-sm'
                   onclick=\"return confirm('Drop this course?')\">
                    Drop
                </a>
            </td>
        </tr>";
    }
}
