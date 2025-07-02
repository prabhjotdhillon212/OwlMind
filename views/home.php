<?php
session_start();
require_once "../inc/config.inc";

require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";

include 'db_connection.php';

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
            <img class="meditating-img" src="<?php echo BASE_URL; ?>public/images/Mindful.png" alt="Person meditating for calmness" />
        </div>

        <!-- Greeting Box -->
        <div class="hero-greeting-box">
            <div class="main-dashboard-section">
                <?php
                    if (isset($_SESSION['accountID'])) {                
                        $accountID = $_SESSION['accountID'];

                        try {
                            $sql = $db->prepare("SELECT fname FROM Student WHERE accountID='$accountID'");
                            $result = $sql->execute();

                            $user = $result->fetchArray(SQLITE3_ASSOC);
                            if($user) {
                                $username = $user['fname'];
                                echo "<h1 class='hero-heading'>Welcome, <span class='hero-name'>$username</span>!<span class='wave'>👋</span></h1>";
                            } else {
                                echo "<h1 class='hero-heading'>User not found :( ";
                            }
                        } catch (SQLite3Exception $e) {
                            echo "Error: ". $e->getMessage();
                        }
                    } else {
                        echo "<h1 class='hero-heading'  >Uh Oh...</h1>";
                    }
                ?>
                <p class="hero-subtext">It’s a great day to check in with yourself.</p>
                <div class="dashboard-grid">
                    <a href="<?php echo BASE_URL; ?>views/journal.php" class="dash-tile">Journal</a>
                    <a href="<?php echo BASE_URL; ?>views/mood.php" class="dash-tile">Log Mood</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="home-extras">
            <div class="home-card tip-card">
                <h3>Daily Mental Health Tip</h3>
                <p>Take a deep breath. You’re stronger than you think.</p>
            </div>

            <div class="home-card mood-trend">
                <h3> Mood Trend</h3>
                <p>Last 7 days mood check</p>
            </div>

            <div class="home-card journal-preview">
                <h3>Your Recent Journal Entries</h3>
            </div>
        </div>

    </section>
</main>

<?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>