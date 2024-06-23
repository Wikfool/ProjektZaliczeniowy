<?php
session_start();
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$uploadError = '';
$announcementMessage = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $photo = $_FILES['photo']['name'];
    $user_id = $_SESSION['user_id'];

    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($photo);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES['photo']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadError = "Plik nie jest zdjęciem.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        $uploadError = "Plik o tej nazwie już istnieje.";
        $uploadOk = 0;
    }

    if ($_FILES['photo']['size'] > 500000) {
        $uploadError = "Plik jest za duży.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $uploadError = "Dozwolone są tylko pliki typu JPG, JPEG, PNG i GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $uploadError .= " Plik nie został przesłany.";
    } else {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            if (addAnnouncement($title, $description, $photo, $price, $user_id)) {
                header('Location: dashboard.php?announcement_added=true');
                exit();
            } else {
                $uploadError = "Błąd podczas dodawania ogłoszenia.";
            }
        } else {
            $uploadError = "Wystąpił problem z przesyłaniem pliku.";
        }
    }
}

if (isset($_GET['announcement_deleted']) && $_GET['announcement_deleted'] == 'true') {
    $announcementMessage = "Ogłoszenie zostało pomyślnie usunięte.";
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] == 'delete_failed') {
        $announcementMessage = "Błąd podczas usuwania ogłoszenia.";
    } elseif ($_GET['error'] == 'invalid_id') {
        $announcementMessage = "Nieprawidłowe ID ogłoszenia.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <h2>Panel użytkownika</h2>
        <h3>Dodaj nowe ogłoszenie</h3>
        <form action="dashboard.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Tytuł ogłoszenia" required><br>
            <textarea name="description" placeholder="Opis ogłoszenia" rows="4" required></textarea><br>
            <input type="text" name="price" placeholder="Cena" required><br>
            <input type="file" name="photo" required><br>
            <button type="submit" name="add_announcement">Dodaj ogłoszenie</button>
        </form>
        <?php if (!empty($uploadError)) echo "<p class='error'>$uploadError</p>"; ?>
        <?php if (!empty($announcementMessage)) echo "<p class='message'>$announcementMessage</p>"; ?>
        <p><a href="logout.php">Wyloguj się</a></p>
        <h3>Twoje ogłoszenia</h3>
        <?php
        $announcements = getAnnouncements($_SESSION['user_id']);
        if ($announcements) {
            foreach ($announcements as $announcement) {
                echo "<div class='announcement'>";
                echo "<h2>Tytuł: {$announcement['title']}</h2>";
                echo "<p>Opis: {$announcement['description']}</p>";
                echo "<p>Cena: {$announcement['price']} PLN</p>";
                echo "<img src='uploads/{$announcement['photo']}' alt='photo'>";
                echo "<a href='delete.php?id={$announcement['id']}' class='delete-btn'>Usuń</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Brak ogłoszeń do wyświetlenia.</p>";
        }
        ?>
    </div>
</body>
</html>
