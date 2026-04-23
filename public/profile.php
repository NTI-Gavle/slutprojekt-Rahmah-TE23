<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';
require_once '../database/kvitter_queries.php';

require_login();
$user = getUserById($_SESSION['user_id']);
$myKvitter = getUserKvitter($_SESSION['user_id']);
$stats = getUserStats($_SESSION['user_id']);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $confirm = trim($_POST['confirm_delete']);
    if ($confirm === 'RADERA MITT KONTO') {
        deleteUser($_SESSION['user_id']);
        session_destroy();
        header('Location: index.php?message=Kontot raderat');
        exit;
    } else {
        $error = 'Felaktig bekräftelsetext.';
    }
}
$title = 'Min profil';
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">Profil</div>
            <div class="card-body text-center">
                <i class="fas fa-user-circle fa-5x" style="color:#78A2D2;"></i>
                <h4><?= escape($user['username']) ?></h4>
                <p><?= escape($user['email']) ?></p>
                <hr>
                <p>Medlem sedan: <?= date('Y-m-d', strtotime($user['created_at'])) ?></p>
                <p>Kvitter: <?= $stats['total'] ?? 0 ?></p>
                <?php if ($user['role'] === 'admin'): ?>
                    <span class="badge bg-danger">Admin</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header bg-danger text-white">Radera konto (GDPR)</div>
            <div class="card-body">
                <?php if ($error): ?><div class="alert alert-danger"><?= escape($error) ?></div><?php endif; ?>
                <form method="POST" onsubmit="return confirm('Är du HELT säker? Detta går inte att ångra.')">
                    <div class="mb-2">
                        <label>Skriv <strong>RADERA MITT KONTO</strong></label>
                        <input type="text" name="confirm_delete" class="form-control" required>
                    </div>
                    <button type="submit" name="delete_account" class="btn btn-danger w-100">Radera permanent</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">Mina kvitter</div>
            <div class="card-body">
                <?php if (empty($myKvitter)): ?>
                    <p class="text-muted">Du har inga kvitter än. <a href="index.php">Skriv ditt första</a></p>
                <?php else: ?>
                    <?php foreach ($myKvitter as $k): ?>
                        <div class="border-bottom mb-3 pb-2">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?= $k['created_at'] ?></small>
                                <a href="index.php?delete=<?= $k['id'] ?>" class="text-danger" onclick="return confirm('Ta bort?')">🗑️</a>
                            </div>
                            <p><?= escape($k['content']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>