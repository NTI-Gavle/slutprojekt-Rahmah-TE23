<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';

if (is_logged_in()) {
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
            <div class="card-header" style="background:#78A2D2;color:white;">Skapa konto</div>
            <div class="card-body">
                <?php if ($error): ?><div class="alert alert-danger"><?= escape($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Användarnamn</label>
                        <input type="text" name="username" class="form-control" value="<?= escape($username) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>E-post</label>
                        <input type="email" name="email" class="form-control" value="<?= escape($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Lösenord</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="progress mt-1" style="height:5px"><div id="strengthBar" class="progress-bar" style="width:0%"></div></div>
                    </div>
                    <div class="mb-3">
                        <label>Bekräfta lösenord</label>
                        <input type="password" name="confirm_password" id="confirm" class="form-control" required>
                        <small id="matchMsg" class="form-text"></small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="gdpr" required>
                        <label>Jag godkänner <a href="#" data-bs-toggle="modal" data-bs-target="#gdprModal">GDPR</a></label>
                    </div>
                    <button type="submit" id="submitBtn" class="btn w-100" style="background:#78A2D2;color:white;" disabled>Registrera</button>
                </form>
                <hr>
                <p class="text-center">Har du redan konto? <a href="login.php" style="color:#78A2D2;">Logga in</a></p>
            </div>
        </div>
    </div>
</div>

<script>
const pwd = document.getElementById('password');
const confirm = document.getElementById('confirm');
const strength = document.getElementById('strengthBar');
const matchMsg = document.getElementById('matchMsg');
const gdpr = document.getElementById('gdpr');
const submitBtn = document.getElementById('submitBtn');

pwd.addEventListener('input', () => {
    let val = 0;
    if (pwd.value.length >= 8) val += 33;
    if (/[A-Z]/.test(pwd.value)) val += 33;
    if (/[0-9]/.test(pwd.value)) val += 34;
    strength.style.width = val + '%';
    strength.style.backgroundColor = val < 33 ? '#dc3545' : (val < 66 ? '#ffc107' : '#28a745');
    checkMatch();
});
function checkMatch() {
    if (confirm.value === '') matchMsg.innerHTML = '';
    else if (pwd.value === confirm.value) matchMsg.innerHTML = '<span class="text-success">✓ Matchar</span>';
    else matchMsg.innerHTML = '<span class="text-danger">✗ Matchar inte</span>';
}
confirm.addEventListener('input', checkMatch);
gdpr.addEventListener('change', () => submitBtn.disabled = !gdpr.checked);
</script>

<?php include '../includes/footer.php'; ?>