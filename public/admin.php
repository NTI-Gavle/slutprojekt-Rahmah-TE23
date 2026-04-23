<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';

require_admin();

$users = getAllUsers();
$totalUsers = count($users);
$totalKvitter = array_sum(array_column($users, 'kvitter_count'));

$error = $success = '';
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    if ($id != $_SESSION['user_id']) {
        $user = getUserById($id);
        if ($user && $user['role'] !== 'admin') {
            if (deleteUser($id)) {
                $success = "Användaren {$user['username']} borttagen.";
                $users = getAllUsers(); 
            } else {
                $error = 'Kunde inte ta bort.';
            }
        } else {
            $error = 'Kan inte ta bort admin eller dig själv.';
        }
    } else {
        $error = 'Du kan inte ta bort ditt eget konto här. Gå till profilen.';
    }
    
}
$title = 'Adminpanel';
include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header" style="background:#78A2D2;color:white;">Adminpanel – Användarhantering</div>
            <div class="card-body">
                <?php if ($error): ?><div class="alert alert-danger"><?= escape($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= escape($success) ?></div><?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body"> Användare: <?= $totalUsers ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info">
                            <div class="card-body">Totalt kvitter: <?= $totalKvitter ?></div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>ID</th><th>Användarnamn</th><th>E-post</th><th>Roll</th><th>Registrerad</th><th>Kvitter</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= escape($u['username']) ?></td>
                                <td><?= escape($u['email']) ?></td>
                                <td><?= $u['role'] === 'admin' ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-secondary">User</span>' ?></td>
                                <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                                <td><?= $u['kvitter_count'] ?></td>
                                <td>
                                    <?php if ($u['role'] !== 'admin' && $u['id'] != $_SESSION['user_id']): ?>
                                        <a href="?delete_user=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ta bort användaren permanent?')">🗑️</a>
                                    <?php else: ?>
                                        <span class="text-muted">–</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>