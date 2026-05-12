<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/kvitter_queries.php';

$title = 'Hem';
$error = $success = '';

if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';
    if (createKvitter($_SESSION['user_id'], $content)) {
        $success = 'Kvitter publicerat!';
    } else {
        $error = 'Kunde inte publicera.';
    }
}

if (isset($_GET['delete']) && isset($_SESSION['user_id'])) {
    $id = (int)$_GET['delete'];
    $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    if (deleteKvitter($id, $_SESSION['user_id'], $isAdmin)) {
        $success = 'Kvitter borttaget.';
    }
}

$kvitter = getAllKvitter();
include '../includes/header.php';
?>

<div class="row">
    <!-- Vänster kolumn - Canvas klocka -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-clock me-2"></i>Digital klocka
            </div>
            <div class="card-body text-center">
                <canvas id="clockCanvas" width="200" height="200"></canvas>
                <p class="mt-2 small text-muted">Ritad i Canvas med JavaScript</p>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="card shadow mt-4" style="border-top: 4px solid #78A2D2;">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-3x" style="color: #78A2D2;"></i>
                    <h5 class="mt-2"><?= htmlspecialchars($_SESSION['username']) ?></h5>
                    <small class="text-muted">Inloggad</small>
                    <hr>
                    <a href="profile.php" class="btn btn-sm" style="background: #78A2D2; color: white;">Min profil</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mittenkolumn - Flöde -->
    <div class="col-lg-6">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="card shadow mb-4">
                <div class="card-header" style="background: #78A2D2; color: white;">
                    <i class="fas fa-pen me-2"></i>Skapa nytt kvitter
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <textarea name="content" class="form-control" rows="3" maxlength="280" placeholder="Vad händer? (max 280 tecken)" required></textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <small id="charCount" class="text-muted">0/280</small>
                            <button type="submit" class="btn" style="background: #78A2D2; color: white;">
                                <i class="fas fa-paper-plane me-2"></i>Publicera
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-stream me-2"></i>Senaste kvittren
            </div>
            <div class="card-body">
                <?php if (empty($kvitter)): ?>
                    <p class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-2" style="color: #78A2D2;"></i><br>
                        Inga kvitter än. Bli först med att skriva något!
                    </p>
                <?php else: ?>
                    <?php foreach ($kvitter as $k): ?>
                        <div class="border-bottom mb-3 pb-3 kvitter-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong style="color: #78A2D2;">
                                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($k['username']) ?>
                                    </strong>
                                    <?php if ($k['role'] === 'admin'): ?>
                                        <span class="badge" style="background: #78A2D2; font-size: 10px;">Admin</span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i><?= date('Y-m-d H:i', strtotime($k['created_at'])) ?>
                                    </small>
                                </div>
                                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $k['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                                    <a href="?delete=<?= $k['id'] ?>" class="text-danger" onclick="return confirm('Ta bort detta kvitter?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($k['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Högerkolumn - Information -->
    <div class="col-lg-3">
        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-info-circle me-2"></i>Om Kvitter
            </div>
            <div class="card-body">
                <p>En plats för korta meddelanden.<br>Registrera dig och börja kvittra!</p>
                <hr>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> Skapa kvitter</li>
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> Ta bort dina inlägg</li>
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> Admin-hantering</li>
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> GDPR-säkert</li>
                </ul>
            </div>
        </div>
        
        <div class="card shadow mt-4" style="background: #FEFFAF;">
            <div class="card-body">
                <i class="fas fa-lightbulb me-2" style="color: #78A2D2;"></i>
                <strong>Tips!</strong>
                <p class="small mb-0 mt-1">Du kan skriva upp till 280 tecken per kvitter.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Teckenräknare
const textarea = document.querySelector('textarea[name="content"]');
const charCount = document.getElementById('charCount');
if (textarea) {
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count + '/280';
        if (count > 250) {
            charCount.style.color = '#dc3545';
        } else if (count > 200) {
            charCount.style.color = '#ffc107';
        } else {
            charCount.style.color = '#6c757d';
        }
    });
}
</script>

<?php include '../includes/footer.php'; ?>