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
    elseif(!validateEmail($email)){
        $error = 'Ange en giltig email-adress.';
    }

    elseif(!validatePassword($password)){
        $error = 'Lösenordet måste vara minst 8 tecken långt, innehålla minst en stor bokstav och en siffra.';
    }

    elseif($password !== $confirm_password){
        $error = 'Lösenord matchar inte.';
    }

    else{
        $existingUser = getUserByUsername($validatedUsername);
        $existingEmail = getUserByEmail($email);

        if ($existingUser) {
            $error = 'Användarnamnet är redan upptaget. Välj ett annat.';
        } elseif ($existingEmail) {
            $error = 'Email-adressen är redan registrerad.';
        } else {
            if (createUser($validatedUsername, $email, $password)) {
                $success = 'Din registrering lyckades! Du kan nu logga in.';
                logActivity(0, 'Ny användare registrerad: ' . $validatedUsername);
                
                // Rensa formuläret
                $username = '';
                $email = '';
            } else {
                $error = 'Ett fel uppstod vid registeringen. Försök igen.';
            }
        }
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0">
            <div class="card-header text-white text-center py-3" style="background: #78A2D2;">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Registrera nytt konto
                </h4>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo escape($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo escape($success); ?>
                        <hr>
                        <a href="login.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>Gå till inloggning
                        </a>
                    </div>
                <?php endif; ?>