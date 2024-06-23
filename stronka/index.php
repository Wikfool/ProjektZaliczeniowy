<?php
session_start();
require_once 'functions.php';

$error = '';
$registerSuccess = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = login($username, $password);
    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Nieprawidłowa nazwa użytkownika lub hasło.";
    }
}

if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $registerSuccess = "Konto zostało pomyślnie utworzone. Możesz się teraz zalogować.";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container login-form">
        <h2>Logowanie</h2>
        <?php if (!empty($registerSuccess)) echo "<p class='success'>$registerSuccess</p>"; ?>
        <form action="index.php" method="post">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zaloguj się</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <p>Nie masz konta? <a href="register.php">Załóż konto</a></p>
    </div>
</body>
</html>
