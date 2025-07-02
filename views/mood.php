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

// Handle mood submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['moodID'])) {
  $moodID = $_POST['moodID'];
  $timestamp = date('Y-m-d H:i:s');
  $stmt = $db->prepare("INSERT INTO Moodtracker (moodID, studentID, timestamp) VALUES (?, ?, ?)");
  $stmt->bindValue(1, $moodID);
  $stmt->bindValue(2, $studentID);
  $stmt->bindValue(3, $timestamp);
  $stmt->execute();
}
?>

<!-- FullCalendar CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<!-- Custom Styles -->
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

  .mood-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 40px;
  }

  .mood-button {
    background-color: white;
    color: #264653;
    border: none;
    padding: 14px 30px;
    border-radius: 40px;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
  }

  .mood-button:hover {
    background-color: #d0f0ff;
    transform: scale(1.05);
    cursor: pointer;
  }

.calendar-container {
  background: white;
  border-radius: 20px;
  padding: 25px;
  width: 100%;
  max-width: 900px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
  margin-bottom: 60px;
}

#calendar {
  width: 100%;
  height: auto;
  min-height: 550px;
  font-size: 0.85rem;
}

  .fc-daygrid-event {
    display: inline-block !important;
  }

  .calendar-heading {
    font-size: 1.8rem;
    margin-bottom: 20px;
    text-align: center;
    color: #264653;
  }
</style>

<main class="wrapper">
  <h1>How are you feeling today?</h1>
  <form method="POST">
    <div class="mood-buttons">
      <?php
        $moods = $db->query("SELECT moodID, mood FROM Moods");
        while ($row = $moods->fetchArray(SQLITE3_ASSOC)):
      ?>
        <button type="submit" name="moodID" value="<?= $row['moodID'] ?>" class="mood-button">
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
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      aspectRatio: 2.5,
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
        ?>
        {
          title: '<?= $emoji ?>',
          start: '<?= $date ?>'
        },
        <?php endwhile; ?>
      ]
    });
    calendar.render();
  });
</script>
