<?php
session_start();
require_once "../inc/config.inc";

// TEMPORARY: Dev preview — remove this once login system is active
if (!isset($_SESSION['email'])) {
  $_SESSION['first_name'] = "Prabhjot";
  $_SESSION['email'] = "you@southernct.edu";
}

require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
?>
<body>

<main class="home-hero">
  <section class="hero-container landing-page fade-in">
    <h1 class="hero-heading">
      Welcome back, <span class="hero-name"><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Student'); ?></span> <span class="wave">👋</span>
    </h1>
    <p class="hero-subtext">It’s a great day to check in with yourself.</p>

    <div class="dashboard-grid">
      <a href="<?php echo BASE_URL; ?>views/journal.php" class="dash-tile">Journal</a>
      <a href="<?php echo BASE_URL; ?>views/mood.php" class="dash-tile">Log Mood</a>
      <a href="<?php echo BASE_URL; ?>views/appointment.php" class="dash-tile">Appointment</a>
    </div>
  </section>
</main>

<?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>