<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';
require_once '../database/kvitter_queries.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$likedKvitter = getUserLikedKvitter($_SESSION['user_id']);
$likedPosts = [];
if (!empty($likedKvitter)) {
    $db = getDB();
    $placeholders = implode(',', array_fill(0, count($likedKvitter), '?'));
    $stmt = $db->prepare("SELECT k.*, u.username FROM kvitter k 
                          JOIN users u ON k.user_id = u.id 
                          WHERE k.id IN ($placeholders) 
                          ORDER BY k.created_at DESC");
    $stmt->execute($likedKvitter);
    $likedPosts = $stmt->fetchAll();
}

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
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-id-card me-2"></i>Profilinformation
            </div>
            <div class="card-body text-center">
                <i class="fas fa-user-circle fa-5x" style="color: #78A2D2;"></i>
                <h4 class="mt-2"><?= htmlspecialchars($user['username']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="border rounded p-2" style="background: #f8f9fa;">
                            <h3 class="mb-0" style="color: #78A2D2;"><?= $stats['total'] ?? 0 ?></h3>
                            <small>Kvitter</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2" style="background: #f8f9fa;">
                            <h3 class="mb-0" style="color: #78A2D2;"><?= date('Y-m-d', strtotime($user['created_at'])) ?></h3>
                            <small>Medlem</small>
                        </div>
                    </div>
                </div>
                <?php if ($user['role'] === 'admin'): ?>
                    <div class="mt-3">
                        <span class="badge" style="background: #78A2D2;">Admin</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header" style="background: #dc3545; color: white;">
                <i class="fas fa-trash-alt me-2"></i>Radera konto (GDPR)
            </div>
            <div class="card-body">
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Detta kan inte ångras! Alla dina kvitter och din profil raderas permanent.
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" onsubmit="return confirm('Är du HELT säker? Detta går inte att ångra!')">
                    <div class="mb-2">
                        <label class="form-label small">Skriv <strong>"RADERA MITT KONTO"</strong> för att bekräfta</label>
                        <input type="text" name="confirm_delete" class="form-control" required>
                    </div>
                    <button type="submit" name="delete_account" class="btn btn-danger w-100">
                        <i class="fas fa-trash-alt me-2"></i>Radera permanent
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-history me-2"></i>Mina kvitter
                <span class="badge bg-light text-dark ms-2"><?= count($myKvitter) ?> st</span>
            </div>
            <div class="card-body">
                <?php if (empty($myKvitter)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-4x mb-3" style="color: #78A2D2;"></i>
                        <p>Du har inga kvitter än.</p>
                        <a href="index.php" class="btn" style="background: #78A2D2; color: white;">
                            <i class="fas fa-pen me-2"></i>Skriv ditt första kvitter
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($myKvitter as $k): ?>
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i><?= date('Y-m-d H:i', strtotime($k['created_at'])) ?>
                                </small>
                                <a href="index.php?delete=<?= $k['id'] ?>" class="text-danger" onclick="return confirm('Ta bort detta kvitter?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                            <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($k['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header" style="background: #78A2D2; color: white;">
        <i class="fas fa-heart me-2"></i>Gillade kvitter
        <span class="badge bg-light text-dark ms-2"><?= count($likedPosts) ?> st</span>
    </div>
    <div class="card-body">
        <?php if (empty($likedPosts)): ?>
            <p class="text-muted text-center py-3">
                <i class="far fa-heart fa-2x mb-2" style="color: #78A2D2;"></i><br>
                Du har inte gillat några kvitter än.
            </p>
        <?php else: ?>
            <?php foreach ($likedPosts as $like): ?>
                <div class="border-bottom mb-3 pb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong style="color: #78A2D2;">
                                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($like['username']) ?>
                            </strong>
                            <br>
                            <small class="text-muted"><?= date('Y-m-d H:i', strtotime($like['created_at'])) ?></small>
                        </div>
                        <a href="index.php" class="btn btn-sm" style="background: #78A2D2; color: white;">Visa</a>
                    </div>
                    <p class="mt-2 mb-0"><?= htmlspecialchars($like['content']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>