<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $error = "Username already exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Register - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #1a4332;
            height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card mx-auto p-4 shadow" 
             style="max-width: 400px; border-radius: 15px;">

            <h3 class="text-center fw-bold">PCRS REGISTER</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select">
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold">
                    REGISTER
                </button>

            </form>

            <p class="text-center mt-3 small">
                Already have an account? 
                <a href="login.php">Login here</a>
            </p>

        </div>
    </div>
</body>
</html>
