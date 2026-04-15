<?php
include 'conn.php';

// Jika sudah login, hantar terus ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login - PCRS</title>
    <style>body { background-color: #1a4332; height: 100vh; display: flex; align-items: center; }</style>
</head>
<body>
    <div class="container">
        <div class="card mx-auto p-4 shadow" style="max-width: 400px; border-radius: 15px;">
            <h3 class="text-center fw-bold">PCRS LOGIN</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">LOGIN</button>
            </form>
            <p class="text-center mt-3 small">Dont have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>