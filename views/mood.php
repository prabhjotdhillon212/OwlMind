<?php
session_start();
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
include 'db_connection.php';

if (!isset($_SESSION['accountID'])) {
  header("Location: login.php");
  exit();
}

$accountID = $_SESSION['accountID'];

// Get student ID
$stmt = $db->prepare("SELECT studentID FROM Student WHERE accountID = ?");
$stmt->bindValue(1, $accountID);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
$studentID = $row ? $row['studentID'] : null;

if (!$studentID) {
  echo "<p class='text-danger'>Student not found.</p>";
  exit();
}

// Handle mood submission (always insert, no more update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['moodID']) && isset($_POST['confirm'])) {
  $moodID = $_POST['moodID'];
  $timestamp = date('Y-m-d H:i:s');

  // Insert new mood entry
  $insertStmt = $db->prepare("
    INSERT INTO Moodtracker (moodID, studentID, timestamp)
    VALUES (?, ?, ?)
  ");
  $insertStmt->bindValue(1, $moodID);
  $insertStmt->bindValue(2, $studentID);
  $insertStmt->bindValue(3, $timestamp);
  $insertStmt->execute();
}
?>

<!-- FullCalendar CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<!-- Custom Styles (Unchanged) -->
<style>
  body {
    background: linear-gradient(to right, #d4f1f9, #e6faff);
    font-family: 'Nunito', sans-serif;
    margin: 0;
    padding: 0;
  }

  main.wrapper {
    max-width: 1200px;
    margin: auto;
    padding: 40px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h1, h2 {
    color: #264653;
  }
</style>

<main class="wrapper">
  <h1>How are you feeling today?</h1>
  <form id="moodForm" method="POST">
    <input type="hidden" name="moodID" id="moodID">
    <input type="hidden" name="confirm" value="1">
    <div class="mood-scroll">
      <?php
        $moods = $db->query("SELECT moodID, mood FROM Moods");
        while ($row = $moods->fetchArray(SQLITE3_ASSOC)):
      ?>
        <button type="button" class="mood-button" onclick="handleMoodClick(<?= $row['moodID'] ?>, '<?= htmlspecialchars($row['mood']) ?>')">
          <?= htmlspecialchars($row['mood']) ?>
        </button>
      <?php endwhile; ?>
    </div>
  </form>

  <div class="calendar-container">
    <h2 class="calendar-heading">📅 Mood Calendar</h2>
    <div id="calendar"></div>
  </div>
</main>

<!-- FullCalendar Script -->
<script>
  function handleMoodClick(moodID, moodName) {
    if (confirm("Add this mood: " + moodName + "?")) {
      submitMood(moodID);
    }
  }

  function submitMood(moodID) {
    document.getElementById('moodID').value = moodID;
    document.getElementById('moodForm').submit();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      windowResize: function(arg) {
        // Switch to list view on small screens
        if (arg.view.calendar.el.offsetWidth < 576) {
          calendar.changeView('listWeek');
        } else {
          calendar.changeView('dayGridMonth');
        }
      },
      events: [
        <?php
          $stmt = $db->prepare("
            SELECT M.mood, MT.timestamp
            FROM Moodtracker MT
            JOIN Moods M ON M.moodID = MT.moodID
            WHERE MT.studentID = ?
            ORDER BY MT.timestamp DESC
          ");
          $stmt->bindValue(1, $studentID);
          $calendarLogs = $stmt->execute();
          while ($entry = $calendarLogs->fetchArray(SQLITE3_ASSOC)):
            $emoji = htmlspecialchars($entry['mood']);
            $date = date('Y-m-d', strtotime($entry['timestamp']));
        ?> {
          title: '<?= $emoji ?>',
          start: '<?= $date ?>'
        },
        <?php endwhile; ?>
      ]
    });
    calendar.render();
  });
</script>