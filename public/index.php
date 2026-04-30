<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/kvitter_queries.php';

$title = 'Hem';
$error = $success = '';

if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $error = 'Säkerhetsfel. Försök igen.';
    } else {
        $content = $_POST['content'] ?? '';
        if (createKvitter($_SESSION['user_id'], $content)) {
            $success = 'Kvitter publicerat!';
        } else {
            $error = 'Kunde inte publicera.';
        }
    }
}

if (isset($_GET['delete']) && is_logged_in()) {
    $id = (int)$_GET['delete'];
    if (deleteKvitter($id, $_SESSION['user_id'], is_admin())) {
        $success = 'Kvitter borttaget.';
    }
}

$kvitter = getAllKvitter();
include '../includes/header.php';
?>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;"> Digital klocka</div>
            <div class="card-body text-center">
                <canvas id="clockCanvas" width="200" height="200"></canvas>
            </div>
        </div>
        <?php if (is_logged_in()): ?>
            <div class="card shadow mt-4">
                <div class="card-body">
                    <i class="fas fa-user-circle fa-2x"></i> Hej <?= escape($_SESSION['username']) ?>!
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-6">
        <?php if (is_logged_in()): ?>
            <div class="card shadow mb-4">
                <div class="card-header" style="background:#78A2D2;color:white;"> Skapa inlägg</div>
                <div class="card-body">
                    <?php if ($error): ?><div class="alert alert-danger"><?= escape($error) ?></div><?php endif; ?>
                    <?php if ($success): ?><div class="alert alert-success"><?= escape($success) ?></div><?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <textarea name="content" class="form-control" rows="3" maxlength="280" placeholder="Vad händer? (max 280 tecken)" required></textarea>
                        <button class="btn mt-2 w-100" style="background:#78A2D2;color:white;">Publicera</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;"> Senaste inlägg</div>
            <div class="card-body">
                <?php foreach ($kvitter as $k): ?>
                    <div class="border-bottom mb-3 pb-2">
                        <div class="d-flex justify-content-between">
                            <strong style="color:#78A2D2;"><?= escape($k['username']) ?></strong>
                            <?php if (is_logged_in() && ($_SESSION['user_id'] == $k['user_id'] || is_admin())): ?>
                                <a href="?delete=<?= $k['id'] ?>" class="text-danger" onclick="return confirm('Ta bort?')">Radera</a>
                            <?php endif; ?>
                        </div>
                        <p><?= escape($k['content']) ?></p>
                        <small class="text-muted"><?= $k['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">ℹ️ Om Kvitter</div>
            <div class="card-body">
                <p>Dela korta tankar. Registrera dig och börja kvittra!</p>
                <hr>
                <ul class="list-unstyled">
                    <li>✓ Skapa inlägg</li>
                    <li>✓ Ta bort dina inlägg</li>
                    <li>✓ Admin hanterar användare</li>
                    <li>✓ GDPR-säkert</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>