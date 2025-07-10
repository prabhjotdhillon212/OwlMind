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
            <img src="<?php echo BASE_URL; ?>public/images/splash-blue.png" class="splash splash-top-left"
                alt="blue splash" />
            <!-- <img src="<?php echo BASE_URL; ?>public/images/splash-blue.png" class="splash splash-bottom-right" alt="yellow splash" /> -->
        </div>

        <section class="hero-container fade-in">

            <!-- Meditating Image -->
            <div class="meditating-person">
                <img class="meditating-img" src="<?php echo BASE_URL; ?>public/images/Calm.png"
                    alt="Person meditating for calmness" />
            </div>

            <!-- Greeting Box -->
            <div class="hero-greeting-box">
                <div class="main-dashboard-section">
                    <?php
                    if (isset($_SESSION['accountID'])) {                
                        $accountID = $_SESSION['accountID'];
                        $username = $_SESSION['fname'];
                        echo "<h1 class='hero-heading'>Welcome, <span class='hero-name'>$username</span>!<span class='wave'>👋</span></h1>";
                    } else {
                        echo "<h1 class='hero-heading'>Uh Oh...</h1>";
                    }
                ?>
                    <p class="hero-subtext">It’s a great day to check in with yourself.</p>
                    <div class="dashboard-grid">
                        <a href="<?php echo BASE_URL; ?>views/journal.php" class="dash-tile">Journal</a>
                        <a href="<?php echo BASE_URL; ?>views/mood.php" class="dash-tile">Mood Recorder</a>
                        <a href="<?php echo BASE_URL; ?>views/profile.php" class="dash-tile">My Profile</a>
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
                    <h3>📈 Mood Trend (Last 7 Days)</h3>
                    <div class="mood-7days">
                        <?php
        if (isset($_SESSION['accountID'])) {
            $stmt = $db->prepare("
                SELECT M.mood, DATE(MT.timestamp) AS mood_date, MAX(MT.timestamp) AS latest_time
                FROM Moodtracker MT
                JOIN Moods M ON M.moodID = MT.moodID
                WHERE MT.studentID = (
                    SELECT studentID FROM Student WHERE accountID = ?
                )
                GROUP BY mood_date
                ORDER BY mood_date DESC
                LIMIT 7
            ");
            $stmt->bindValue(1, $_SESSION['accountID'], SQLITE3_INTEGER);
            $result = $stmt->execute();

            $moodTrend = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $moodTrend[] = [
                    'date' => $row['mood_date'],
                    'mood' => htmlspecialchars($row['mood'])
                ];
            }

            if (!empty($moodTrend)) {
                echo "<ul class='mood-history'>";
                foreach (array_reverse($moodTrend) as $entry) {
                    echo "<li><strong>{$entry['date']}:</strong> {$entry['mood']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No mood data found. Start recording your moods!</p>";
            }
        } else {
            echo "<p>Please log in to see your mood trends.</p>";
        }
        ?>
                    </div>
                </div>


                <div class="home-card journal-preview">
                    <h3>📔 Your Recent Journal Entries</h3>
                    <div class="journal-recent">
                        <?php
        if (isset($_SESSION['accountID'])) {
            $stmt = $db->prepare("
                SELECT J.entry, J.timestamp 
                FROM Journal J
                WHERE J.studentID = (
                    SELECT studentID FROM Student WHERE accountID = :accountID
                )
                ORDER BY J.entryID DESC
                LIMIT 5
            ");
            $stmt->bindValue(':accountID', $_SESSION['accountID'], SQLITE3_INTEGER);
            $result = $stmt->execute();

            $journalEntries = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $dateObj = new DateTime($row['timestamp']);
                $dateObj->setTimezone(new DateTimeZone('America/New_York'));
                $formattedDate = $dateObj->format('M j, Y');

                $journalEntries[] = [
                    'date' => $formattedDate,
                    'entry' => htmlspecialchars($row['entry'])
                ];
            }

            if (!empty($journalEntries)) {
                echo "<ul class='journal-recent-list'>";
                foreach ($journalEntries as $entry) {
                    // Show a shortened preview (like first 50 characters)
                    $preview = strlen($entry['entry']) > 50 
                        ? substr($entry['entry'], 0, 50) . '...' 
                        : $entry['entry'];
                    echo "<li><strong>{$entry['date']}:</strong> {$preview}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No journal entries yet. Start writing today!</p>";
            }
        } else {
            echo "<p>Please log in to see your recent journal entries.</p>";
        }
        ?>
                    </div>
                </div>


            </div>

        </section>
    </main>

    <?php include(ROOT_PATH . "inc/footer.inc"); ?>