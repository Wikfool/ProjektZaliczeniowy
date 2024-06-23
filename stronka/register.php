<?php
session_start();
require_once 'functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = register($username, $password);
    if ($result === true) {
        header('Location: index.php?registered=true');
        exit();
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container register-form">
        <h2>Rejestracja</h2>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zarejestruj się</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <p><a href="index.php">Powrót do logowania</a></p>
    </div>
</body>
</html>
