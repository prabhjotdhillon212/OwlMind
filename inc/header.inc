<?php
require_once("config.inc");

// Only start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                <?php if (isset($auth_only_nav) && $auth_only_nav): ?>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                <?php elseif (isset($_SESSION['email'])): ?>
                    <li class="nav-item"><a class="nav-link" href="home.php"><?php echo NAV_ITEM_ONE; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="appointment.php"><?php echo NAV_ITEM_TWO; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="journal.php"><?php echo NAV_ITEM_FOUR; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="mood.php"><?php echo NAV_ITEM_FIVE; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="resources.php"><?php echo NAV_ITEM_SIX; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php"><?php echo NAV_ITEM_SEVEN; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
</header>
