<?php
require_once 'db.php';

function login($username, $password) {
    global $mysqli;
    $username = $mysqli->real_escape_string($username);
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return null;
}

function usernameExists($username) {
    global $mysqli;
    $username = $mysqli->real_escape_string($username);

    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    return $result->num_rows > 0;
}

function register($username, $password) {
    global $mysqli;
    if (usernameExists($username)) {
        return "Nazwa użytkownika jest już zajęta.";
    }

    $username = $mysqli->real_escape_string($username);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Haszowanie hasła

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$passwordHash')";
    if ($mysqli->query($query)) {
        return true;
    } else {
        return "Błąd podczas rejestracji użytkownika.";
    }
}


function isLoggedIn() {
    return isset($_SESSION['username']);
}

function addAnnouncement($title, $description, $photo, $price, $user_id) {
    global $mysqli;
    $title = $mysqli->real_escape_string($title);
    $description = $mysqli->real_escape_string($description);
    $photo = $mysqli->real_escape_string($photo);
    $price = $mysqli->real_escape_string($price);
    $user_id = $mysqli->real_escape_string($user_id);

    $query = "INSERT INTO announcements (title, description, photo, price, user_id) VALUES ('$title', '$description', '$photo', '$price', '$user_id')";
    return $mysqli->query($query);
}

function getAnnouncements($user_id) {
    global $mysqli;
    $user_id = $mysqli->real_escape_string($user_id);

    $query = "SELECT id, title, description, photo, price FROM announcements WHERE user_id = '$user_id'";
    $result = $mysqli->query($query);

    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    return $announcements;
}

function deleteAnnouncement($announcement_id, $user_id) {
    global $mysqli;
    $announcement_id = $mysqli->real_escape_string($announcement_id);
    $user_id = $mysqli->real_escape_string($user_id);
    $query = "DELETE FROM announcements WHERE id = '$announcement_id' AND user_id = '$user_id'";
    return $mysqli->query($query);
}
?>
