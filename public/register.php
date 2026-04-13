<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../database/user_queries.php';

if (isset($_SESSION['user_id'])){
    header('Location: index.php');
    exit();
}

$pageTitle= 'Registrera';
$error = '';
$success='';
$username='';
$email='';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $validatedUsername = validateUsername($username);
    if (!$validatedUsername) {
        $error = 'Användarnamnet måste vara 3-50 tecken och får endast innehålla bokstäver, siffror och understreck.';
    }
}