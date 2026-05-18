<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/kvitter_queries.php';

$title = 'Hem';
$error = $success = '';


if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
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


if (isset($_GET['like']) && isset($_SESSION['user_id'])) {
    $kvitterId = (int)$_GET['like'];
    if (!hasUserLiked($_SESSION['user_id'], $kvitterId)) {
        addLike($_SESSION['user_id'], $kvitterId);
    }
    header('Location: index.php');
    exit;
}

if (isset($_GET['unlike']) && isset($_SESSION['user_id'])) {
    $kvitterId = (int)$_GET['unlike'];
    removeLike($_SESSION['user_id'], $kvitterId);
    header('Location: index.php');
    exit;
}


$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$kvitter = getAllKvitterWithLikes($userId);

include '../includes/header.php';
?>

<div class="row">
    
    <div class="col-lg-3 mb-4">
        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                <i class="fas fa-clock me-2"></i>Digital klocka
            </div>
            <div class="card-body text-center">
                <canvas id="clockCanvas" width="200" height="200"></canvas>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="card shadow mt-4">
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
                            <button type="submit" class="btn" style="background: #78A2D2; color: white;">Publicera</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">Senaste kvittren</div>
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
                            
                            <p class="mt-2 mb-2"><?= nl2br(htmlspecialchars($k['content'])) ?></p>
                            
                            
                            <div class="d-flex align-items-center mt-2">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <?php if ($k['user_liked'] > 0): ?>
                                        <a href="?unlike=<?= $k['id'] ?>" class="text-danger me-2" style="text-decoration: none;">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="?like=<?= $k['id'] ?>" class="text-muted me-2" style="text-decoration: none;">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted me-2">
                                        <i class="far fa-heart"></i>
                                    </span>
                                <?php endif; ?>
                                <span class="small" style="color: #78A2D2;">
                                    <?= $k['likes_count'] ?> 
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

   
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
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> Gilla andras inlägg</li>
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> Admin-hantering</li>
                    <li><i class="fas fa-check" style="color: #78A2D2;"></i> GDPR-säkert</li>
                </ul>
            </div>
        </div>
        
        
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
