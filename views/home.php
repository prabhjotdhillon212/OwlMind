<?php
session_start();
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
include 'db_connection.php';

$student_id = $_SESSION['studentID'] ?? '';
$username = $_SESSION['fname'] ?? '';

if ($student_id) {
    include 'auto_notifs.php';
}

function loadMessagesFromFile() {
    $path = ROOT_PATH . 'data/tips.txt';
    $file = fopen($path, "r");
    $messages = [];
    while ($data = fgets($file)) {
        $messages[] = $data;
    }
    fclose($file);
    return $messages;
}

function getRandomNo($messages) {
    $path = ROOT_PATH . "data/randomNo.txt";
    $msg_file_len = count($messages);
    $file = fopen($path, "r");
    $data = fread($file, $msg_file_len);
    fclose($file);
    srand(mktime(0, 0, 0));
    if ($data) {
        $parts = explode('=', $data);
        if ($parts[0] != date("Y-m-d")) {
            $randomNo = rand(0, $msg_file_len - 1);
            overWrite($randomNo);
        } else {
            $randomNo = $parts[1];
        }
    } else {
        $randomNo = rand(0, $msg_file_len - 1);
        overWrite($randomNo);
    }
    return $randomNo;
}

function overWrite(int $randomNo) {
    $path = ROOT_PATH . "data/randomNo.txt";
    $file = fopen($path, "w+");
    $data = date("Y-m-d") . '=' . $randomNo;
    fwrite($file, $data);
    fclose($file);
}

// Dismiss one or all notifications
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dismiss_notif'])) {
        $notifID = (int) $_POST['dismiss_notif'];
        $stmt = $db->prepare("UPDATE NotifsTable SET isRead = 1 WHERE studentID = :sid AND notifsmsgID = :nid");
        $stmt->bindValue(':sid', $student_id, SQLITE3_INTEGER);
        $stmt->bindValue(':nid', $notifID, SQLITE3_INTEGER);
        $stmt->execute();
    } elseif (isset($_POST['dismiss_all'])) {
        $stmt = $db->prepare("UPDATE NotifsTable SET isRead = 1 WHERE studentID = :sid");
        $stmt->bindValue(':sid', $student_id, SQLITE3_INTEGER);
        $stmt->execute();
    }
    header("Location: home.php");
    exit();
}

$messages_from_file = loadMessagesFromFile();
$key = getRandomNo($messages_from_file);
$full_tip = $messages_from_file[$key];

// Journal
$data = [];
try {
    $stmt = $db->prepare("SELECT title, strftime('%m-%d-%Y', timestamp) AS time FROM Journal WHERE studentID = :sid ORDER BY timestamp DESC LIMIT 3");
    $stmt->bindValue(':sid', $student_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = ['title' => $row['title'], 'timestamp' => $row['time']];
    }
} catch (\Throwable $e) {
    echo "<div class='alert alert-danger'>Error loading journal: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Notifications
$notifMessages = [];
try {
    $query = "SELECT N.notifmsg, N.notifmsgID, T.notiftype 
              FROM NotifsTable NT 
              JOIN Notification N ON N.notifmsgID = NT.notifsmsgID
              JOIN NotifType T ON T.notiftypeID = N.notiftypeID
              WHERE NT.studentID = :sid AND NT.isRead = 0";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':sid', $student_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $notifMessages[] = [
            'msg' => $row['notifmsg'],
            'id' => $row['notifmsgID'],
            'type' => $row['notiftype']
        ];
    }
} catch (\Throwable $e) {
    echo "<div class='alert alert-danger'>Error loading notifications: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<body>
<main class="home-hero">
    <div class="site-splashes">
        <img src="<?= BASE_URL; ?>public/images/splash-blue.png" class="splash splash-top-left" alt="blue splash" />
    </div>

    <section class="hero-container fade-in">
        <div class="meditating-person">
            <img class="meditating-img" src="<?= BASE_URL; ?>public/images/Owl.png" alt="Owl" />
        </div>

        <div class="hero-greeting-box">
            <div class="main-dashboard-section">
                <h1 class='hero-heading'>
                    Welcome, <span class='hero-name'><?= htmlspecialchars($username) ?></span>!<span class='wave'>👋</span>
                </h1>
                <p class="hero-subtext">It’s a great day to check in with yourself.</p>

                <?php if (!empty($notifMessages)): ?>
                    <?php foreach ($notifMessages as $notif): ?>
                        <form method="POST" action="home.php">
                            <div class="alert alert-info d-flex justify-content-between align-items-center fw-semibold">
                                🛎️ [<?= htmlspecialchars($notif['type']) ?>] <?= htmlspecialchars($notif['msg']) ?>
                                <input type="hidden" name="dismiss_notif" value="<?= $notif['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-secondary ms-3">Dismiss</button>
                            </div>
                        </form>
                    <?php endforeach; ?>
                    <form method="POST" action="home.php" class="mt-2">
                        <button type="submit" name="dismiss_all" class="btn btn-danger btn-sm">Dismiss All</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-secondary fw-semibold">No new notifications.</div>
                <?php endif; ?>

                <div class="dashboard-grid">
                    <a href="<?= BASE_URL; ?>views/journal.php" class="dash-tile">Reflections</a>
                    <a href="<?= BASE_URL; ?>views/mood.php" class="dash-tile">Mood Recorder</a>
                    <a href="<?= BASE_URL; ?>views/profile.php" class="dash-tile">My Profile</a>
                </div>
            </div>
        </div>

        <div class="home-extras">
            <div class="home-card tip-card">
                <h3>💡 Daily Mental Health Message:</h3>
                <p style='font-weight: bold;'><?= htmlspecialchars($full_tip) ?></p><br>
                <p style="font-style: italic;">source: <a href="https://www.embracehealth.com/blog/50-quotes-about-mental-health" target="_blank">Embrace Health</a></p>
            </div>

            <div class="home-card mood-trend">
                <h3>🌟 <?= htmlspecialchars($username) ?>'s Recent Moods:</h3>
                <table>
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT M.mood, DATE(MT.timestamp) AS mood_date 
                                              FROM Moodtracker MT JOIN Moods M ON M.moodID = MT.moodID 
                                              WHERE MT.studentID = :sid ORDER BY mood_date DESC LIMIT 3");
                        $stmt->bindValue(':sid', $student_id, SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $moodTrend = [];
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            $moodTrend[] = ['date' => $row['mood_date'], 'mood' => htmlspecialchars($row['mood'])];
                        }
                        if (!empty($moodTrend)) {
                            echo "<tr><th>Mood:</th><th>Entered On:</th></tr>";
                            foreach ($moodTrend as $entry) {
                                echo "<tr><td>{$entry['mood']}</td><td>{$entry['date']}</td></tr>";
                            }
                        } else {
                            echo "<p>No moods recorded yet...</p>";
                        }
                    } catch (\Throwable $e) {
                        echo "<div class='text-danger'>Error loading moods: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </table>
            </div>

            <div class="home-card journal-preview">
                <h3>📔 <?= htmlspecialchars($username) ?>'s Recent Reflections:</h3>
                <table>
                    <?php
                    if (!empty($data)) {
                        echo "<tr><th>Title:</th><th>Entered On:</th></tr>";
                        foreach ($data as $reflect) {
                            echo "<tr><td>{$reflect['title']}</td><td>{$reflect['timestamp']}</td></tr>";
                        }
                    } else {
                        echo "<p>No reflections entered yet...</p>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
</main>

<?php include(ROOT_PATH . "inc/footer.inc"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
