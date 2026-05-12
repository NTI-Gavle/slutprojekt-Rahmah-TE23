<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = getUserByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Fel e-post eller lösenord.';
    }
}
$title = 'Logga in';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header text-center py-3" style="background: #78A2D2; color: white;">
                <i class="fas fa-sign-in-alt fa-2x"></i>
                <h4 class="mt-2 mb-0">Logga in</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">E-postadress</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lösenord</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background: #78A2D2; color: white;">
                        <i class="fas fa-sign-in-alt me-2"></i>Logga in
                    </button>
                </form>
                <hr>
                <p class="text-center mb-0">
                    Har du inget konto? 
                    <a href="register.php" style="color: #78A2D2;">Registrera dig här</a>
                </p>
            </div>
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>