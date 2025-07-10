<?php
// inc/header.inc

// Ensure ROOT_PATH is defined. This handles cases where header.inc might be included
// directly or from a different context than config.inc.
// DIR is a magic constant that points to the directory of the current file (inc/).
// So, DIR . "/config.inc" means inc/config.inc relative to inc/.
// This makes sure config.inc is loaded if ROOT_PATH isn't set yet.
if (!defined("ROOT_PATH")) {
    require_once __DIR__ . "/config.inc"; // Use __DIR__ for current file's directory
}

// Start session if it's not already active. This is important for $_SESSION variables.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>vendor/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/responsive.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>vendor/fontawesome/css/all.min.css">

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>img/favicon.png">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <?php if (isset($auth_only_nav) && $auth_only_nav): // Logic for specific pages like signup/login ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/signup.php">Sign Up</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/login.php">Log In</a></li>
                    <?php elseif (isset($_SESSION['email'])): // Logic for authenticated users ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/home.php"><?php echo NAV_ITEM_ONE; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/journal.php"><?php echo NAV_ITEM_THREE; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/mood.php"><?php echo NAV_ITEM_FOUR; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/resources.php"><?php echo NAV_ITEM_FIVE; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/profile.php"><?php echo NAV_ITEM_SIX; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/about.php"><?php echo NAV_ITEM_SEVEN; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/logout.php">Logout</a></li>
                    <?php endif; ?>
          <?php if (isset($auth_only_nav) && $auth_only_nav): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/signup.php">Sign Up</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/login.php">Log In</a></li>
          <?php elseif (isset($_SESSION['email'])): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/home.php"><?php echo NAV_ITEM_ONE; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/journal.php"><?php echo NAV_ITEM_THREE; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/mood.php"><?php echo NAV_ITEM_FOUR; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/resources.php"><?php echo NAV_ITEM_FIVE; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/profile.php"><?php echo NAV_ITEM_SIX; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/about.php"><?php echo NAV_ITEM_SEVEN; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>views/logout.php">Logout</a></li>
          <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
</header>