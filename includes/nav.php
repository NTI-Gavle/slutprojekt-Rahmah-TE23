<nav class="navbar navbar-expand-lg navbar-dark" style="background: #78A2D2;">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-dove"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto">
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Hem</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user"></i> <?= escape($_SESSION['username']) ?></a></li>
                    <?php if (is_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-cog"></i></a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>