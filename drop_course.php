<?php
require_once 'conn.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: view_course.php");
    exit();
}

$reg_id = (int)$_GET['id'];
$student_id = $_SESSION['user_id'];

// Security: Ensure the registration belongs to the logged-in student
$stmt = $pdo->prepare("DELETE FROM registrations WHERE reg_id = ? AND user_id = ?");
if ($stmt->execute([$reg_id, $student_id])) {
    $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Course dropped successfully.'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Failed to drop course.'];
}

header("Location: view_course.php");
exit();