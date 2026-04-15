<?php
require_once 'conn.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: my_course.php");
    exit();
}

$course_id = (int)$_GET['id'];
$student_id = $_SESSION['user_id'];

// Check capacity first
$stmt = $pdo->prepare("
    SELECT (SELECT COUNT(*) FROM registrations WHERE course_id = ?) as enrolled
    FROM courses WHERE course_id = ?
");
$stmt->execute([$course_id, $course_id]);
$course = $stmt->fetch();

if ($course) {
    $insert = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
    if ($insert->execute([$student_id, $course_id])) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Successfully registered for the course!'];
    }
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Sorry, this course is already full.'];
}

header("Location: my_course.php");
exit();