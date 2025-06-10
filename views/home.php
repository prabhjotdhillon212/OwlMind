<<<<<<< HEAD
<?php require_once ROOT_PATH . "inc/config.inc"; ?>
=======
<?php
session_start();
require_once("../inc/config.inc");
>>>>>>> 2a59793 (Added all the new pages, dashboard)

// TEMPORARY: Dev preview — remove this once login system is active
if (!isset($_SESSION['email'])) {
  $_SESSION['first_name'] = "Prabhjot";
  $_SESSION['email'] = "you@southernct.edu";
}

require_once(ROOT_PATH . "inc/headtags.inc");
require_once(ROOT_PATH . "inc/header.inc");
?>
<body>

<main class="home-hero">
  <section class="hero-container fade-in">
    <h1 class="hero-heading">
      Welcome back, <span class="hero-name"><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Student'); ?></span> <span class="wave">👋</span>
    </h1>
    <p class="hero-subtext">It’s a great day to check in with yourself.</p>

    <div class="dashboard-grid">
      <a href="journal.php" class="dash-tile">Journal</a>
      <a href="mood.php" class="dash-tile">Log Mood</a>
      <a href="appointment.php" class="dash-tile">Appointment</a>
    </div>
  </section>
</main>

<?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>
