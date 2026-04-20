<?php
/**
 * Navigation menu
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: #78A2D2;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-dove me-2"></i>KVITTER
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Hem</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> <?php echo escape($_SESSION['username']); ?></a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php"><i class="fas fa-cog"></i></a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>