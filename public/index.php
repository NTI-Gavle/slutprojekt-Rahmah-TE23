<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/kvitter_queries.php';

$title = 'Hem';
$error = $success = '';

// SKAPA NYTT KVITTER
if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
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

// TA BORT KVITTER - Här är ADMIN-LOGIKEN!
if (isset($_GET['delete']) && is_logged_in()) {
    $id = (int)$_GET['delete'];
    
    // is_admin() gör att admin ALLTID kan ta bort oavsett vem som skrivit
    if (deleteKvitter($id, $_SESSION['user_id'], is_admin())) {
        $success = 'Kvitter borttaget.';
    } else {
        $error = 'Kunde inte ta bort kvittret.';
    }
}

$kvitter = getAllKvitter();
include '../includes/header.php';
?>

<div class="row">
    <!-- Vänster kolumn - Klocka -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">
                <i class="fas fa-clock me-2"></i>Digital klocka
            </div>
            <div class="card-body text-center">
                <canvas id="clockCanvas" width="200" height="200"></canvas>
            </div>
        </div>
        <?php if (is_logged_in()): ?>
            <div class="card shadow mt-4" style="border-top: 4px solid #78A2D2;">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-3x" style="color:#78A2D2;"></i>
                    <h5 class="mt-2"><?= escape($_SESSION['username']) ?></h5>
                    <small class="text-muted">Inloggad</small>
                    <?php if (is_admin()): ?>
                        <div class="mt-2">
                            <span class="badge" style="background:#78A2D2;">👑 Admin</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- MITTEN - Flöde -->
    <div class="col-lg-6">
        <?php if (is_logged_in()): ?>
            <div class="card shadow mb-4">
                <div class="card-header" style="background:#78A2D2;color:white;">
                    <i class="fas fa-pen me-2"></i>Skapa nytt kvitter
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= escape($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= escape($success) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <textarea name="content" class="form-control" rows="3" maxlength="280" placeholder="Vad händer? (max 280 tecken)" required></textarea>
                        <button class="btn mt-2 w-100" style="background:#78A2D2;color:white;">
                            <i class="fas fa-paper-plane me-2"></i>Publicera
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">
                <i class="fas fa-stream me-2"></i>Senaste kvittren
            </div>
            <div class="card-body">
                <?php foreach ($kvitter as $k): ?>
                    <div class="border-bottom mb-3 pb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong style="color:#78A2D2;">
                                    <i class="fas fa-user-circle"></i> <?= escape($k['username']) ?>
                                </strong>
                                <?php if ($k['role'] === 'admin'): ?>
                                    <span class="badge" style="background:#78A2D2; font-size:10px;">Admin</span>
                                <?php endif; ?>
                                <br>
                                <small class="text-muted"><?= date('Y-m-d H:i', strtotime($k['created_at'])) ?></small>
                            </div>
                            <!-- 🔑 VIKTIGT: Admin ser 🗑️ på ALLA inlägg -->
                            <?php if (is_logged_in() && ($_SESSION['user_id'] == $k['user_id'] || is_admin())): ?>
                                <a href="?delete=<?= $k['id'] ?>" class="text-danger" onclick="return confirm('Ta bort detta kvitter?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <p class="mt-2 mb-0"><?= escape($k['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Höger kolumn - Info -->
    <div class="col-lg-3">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">
                <i class="fas fa-info-circle me-2"></i>Om Kvitter
            </div>
            <div class="card-body">
                <p>En plats för korta meddelanden.<br>Registrera dig och börja kvittra!</p>
                <hr>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check" style="color:#78A2D2;"></i> Skapa kvitter</li>
                    <li><i class="fas fa-check" style="color:#78A2D2;"></i> Ta bort dina inlägg</li>
                    <li><i class="fas fa-check" style="color:#78A2D2;"></i> Admin-hantering</li>
                    <li><i class="fas fa-check" style="color:#78A2D2;"></i> GDPR-säkert</li>
                </ul>
                <?php if (is_admin()): ?>
                    <hr>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-crown"></i> Du är inloggad som Admin.<br>
                        Du kan ta bort ALLA användares inlägg!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>