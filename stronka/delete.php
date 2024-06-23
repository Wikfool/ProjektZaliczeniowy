<?php
session_start();
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $announcement_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    if (deleteAnnouncement($announcement_id, $user_id)) {
        header('Location: dashboard.php?announcement_deleted=true');
    } else {
        header('Location: dashboard.php?error=delete_failed');
    }
    exit();
} else {
    header('Location: dashboard.php?error=invalid_id');
    exit();
}
?>

