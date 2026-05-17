<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = $success = '';
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!validateUsername($username)) {
        $error = 'Användarnamn: 3-50 tecken (a-z, A-Z, 0-9, _)';
    } elseif (!validateEmail($email)) {
        $error = 'Ogiltig e-postadress';
    } elseif (!validatePassword($password)) {
        $error = 'Lösenord: minst 8 tecken, en stor bokstav och en siffra';
    } elseif ($password !== $confirm) {
        $error = 'Lösenorden matchar inte';
    } elseif (getUserByUsername($username)) {
        $error = 'Användarnamnet är upptaget';
    } elseif (getUserByEmail($email)) {
        $error = 'E-postadressen är redan registrerad';
    } else {
        if (createUser($username, $email, $password)) {
            $success = 'Registrering lyckades! <a href="login.php">Logga in här</a>';
            $username = $email = '';
        } else {
            $error = 'Något gick fel, försök igen.';
        }
    }
}
$title = 'Registrera';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-center py-3" style="background: #78A2D2; color: white;">
                <h4 class="mt-2 mb-0">Skapa nytt konto</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Användarnamn</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
                        <small class="form-text">3-50 tecken. Endast bokstäver, siffror och understreck.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-post</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lösenord</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="progress mt-2" style="height: 5px;">
                            <div id="strengthBar" class="progress-bar" style="width: 0%; background: #78A2D2;"></div>
                        </div>
                        <small id="strengthText" class="form-text"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bekräfta lösenord</label>
                        <input type="password" name="confirm_password" id="confirm" class="form-control" required>
                        <small id="matchMsg" class="form-text"></small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="gdpr" required>
                        <label class="form-check-label">
                            Jag godkänner <a href="#" data-bs-toggle="modal" data-bs-target="#gdprModal" style="color: #78A2D2;">GDPR-villkoren</a>
                        </label>
                    </div>
                    <button type="submit" id="submitBtn" class="btn w-100" style="background: #78A2D2; color: white;" disabled>Registrera
                    </button>
                </form>
                <hr>
                <p class="text-center mb-0">
                    Har du redan ett konto? 
                    <a href="login.php" style="color: #78A2D2;">Logga in här</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>