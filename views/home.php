<?php
session_start();
require_once "../inc/config.inc";

require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
?>
<body>

<main class="home-hero">

    <!-- Global Splashes -->
    <div class="site-splashes">
        <img src="<?php echo BASE_URL; ?>public/images/splash-blue.png" class="splash splash-top-left" alt="blue splash" />
        <!-- <img src="<?php echo BASE_URL; ?>public/images/splash-blue.png" class="splash splash-bottom-right" alt="yellow splash" /> -->
    </div>

    <section class="hero-container fade-in">

        <!-- Meditating Image -->
        <div class="meditating-person">
            <img class="meditating-img" src="<?php echo BASE_URL; ?>public/images/Meditate.jpg" alt="Person meditating for calmness" />
        </div>

        <!-- Greeting Box -->
        <div class="hero-greeting-box">
            <div class="main-dashboard-section">
                <h1 class="hero-heading">
                    Welcome back, <span class="hero-name"><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Student'); ?></span> <span class="wave">👋</span>
                </h1>
                <p class="hero-subtext">It’s a great day to check in with yourself.</p>
                <div class="dashboard-grid">
                    <a href="<?php echo BASE_URL; ?>views/journal.php" class="dash-tile">Journal</a>
                    <a href="<?php echo BASE_URL; ?>views/mood.php" class="dash-tile">Log Mood</a>
                    <a href="<?php echo BASE_URL; ?>views/appointment.php" class="dash-tile">Appointment</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="home-extras">
            <div class="home-card tip-card">
                <h3>💡 Daily Mental Health Tip</h3>
                <p>"Take a deep breath. You’re stronger than you think."</p>
            </div>

            <div class="home-card mood-trend">
                <h3>📈 Mood Trend</h3>
                <p>Last 7 days mood check (placeholder)</p>
                <img class="mood-graph" src="<?php echo BASE_URL; ?>assets/mood-graph-placeholder.png" alt="Mood graph chart preview" />
            </div>

            <div class="home-card journal-preview">
                <h3>📔 Your Recent Journal Entries</h3>
                <ul>
                    <li>Today felt really overwhelming...</li>
                    <li>I handled that test better than expected.</li>
                </ul>
            </div>

            <div class="home-card appointment-card yellow-splash">
                <h3>🗓️ Next Appointment</h3>
                <p>Thursday @ 2:00 PM</p>
            </div>
        </div>

    </section>
</main>

<?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>
