<?php
require_once '../config/session.php';
require_once '../includes/functions.php';
require_once '../database/user_queries.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

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
                $success = "Användaren '{$user['username']}' har tagits bort. Alla hens kvitter är också borta.";
                $users = getAllUsers();
                $totalUsers = count($users);
                $totalKvitter = array_sum(array_column($users, 'kvitter_count'));
            } else {
                $error = 'Kunde inte ta bort användaren.';
            }
        } else {
            $error = 'Kan inte ta bort admin-användare.';
        }
    } else {
        $error = 'Du kan inte ta bort ditt eget konto här. Gå till profilsidan.';
    }
}
$title = 'Adminpanel';
include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header" style="background: #78A2D2; color: white;">
                </i>Adminpanel - Användarhantering
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white" style="background: #78A2D2;">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x mb-2"></i>
                                <h2 class="mb-0"><?= $totalUsers ?></h2>
                                <p>Registrerade användare</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white" style="background: #5a8bc2;">
                            <div class="card-body text-center">
                                <i class="fas fa-comments fa-3x mb-2"></i>
                                <h2 class="mb-0"><?= $totalKvitter ?></h2>
                                <p>Totalt antal kvitter</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white" style="background: #3a6ba2;">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-3x mb-2"></i>
                                <h2 class="mb-0"><?= round($totalKvitter / max($totalUsers, 1), 1) ?></h2>
                                <p>Kvitter per användare</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Användarnamn</th>
                                <th>E-post</th>
                                <th>Roll</th>
                                <th>Registrerad</th>
                                <th>Kvitter</th>
                                <th>Åtgärd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= $u['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($u['username']) ?></strong>
                                        <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                            <span class="badge" style="background: #78A2D2;">Dig</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <?php if ($u['role'] === 'admin'): ?>
                                            <span class="badge" style="background: #dc3545;"><i class="fas fa-crown"></i> Admin</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: #28a745;"><i class="fas fa-user"></i> Användare</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $u['kvitter_count'] ?> st</span>
                                    </td>
                                    <td>
                                        <?php if ($u['role'] !== 'admin' && $u['id'] != $_SESSION['user_id']): ?>
                                            <a href="?delete_user=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Ta bort användaren <?= htmlspecialchars($u['username']) ?> permanent? Alla hens kvitter kommer också att raderas.')">
                                                <i class="fas fa-trash-alt"></i> Ta bort
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-lock"></i> Kan ej tas bort</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($users)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>Inga användare hittades i databasen.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
       
        <div class="card shadow mt-4" style="border-left: 4px solid #78A2D2;">
            <div class="card-body">
                <i class="fas fa-shield-alt me-2" style="color: #78A2D2;"></i>
                <strong>Admin-info</strong>
                <p class="small mb-0 mt-1">Som admin kan du ta bort alla användare (förutom dig själv och andra admin). När en användare tas bort raderas automatiskt alla deras kvitter från databasen.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>