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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['moodID']) && isset($_POST['confirm'])) {
    $moodID = intval($_POST['moodID']);
    $timestamp = date('Y-m-d H:i:s');

    $insertStmt = $db->prepare("
        INSERT INTO Moodtracker (moodID, studentID, timestamp)
        VALUES (?, ?, ?)
    ");
    $insertStmt->bindValue(1, $moodID, SQLITE3_INTEGER);
    $insertStmt->bindValue(2, $studentID, SQLITE3_INTEGER);
    $insertStmt->bindValue(3, $timestamp, SQLITE3_TEXT);
    $insertStmt->execute();
}
?>

<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

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

  .mood-scroll {
    display: flex;
    overflow-x: auto;
    gap: 1rem;
    padding: 10px;
    margin-bottom: 30px;
    width: 100%;
    max-width: 800px;
    scroll-behavior: smooth;
  }

  .mood-scroll::-webkit-scrollbar {
    height: 8px;
  }
  .mood-scroll::-webkit-scrollbar-thumb {
    background: #bde0fe; /* light pastel blue */
    border-radius: 4px;
  }

  .mood-button {
    border: none;
    padding: 14px 30px;
    border-radius: 40px;
    font-size: 1.1rem;
    font-weight: 500;
    color: #264653;
    background-color: #f1f1f1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
    flex: 0 0 auto;
    cursor: pointer;
  }

  .mood-button:hover {
    filter: brightness(0.95);
    transform: scale(1.05);
  }

  /* Mood-specific*/
  .mood-happy      { background-color: #d4edda; color: #155724; } /* soft green */
  .mood-sad        { background-color: #d1ecf1; color: #0c5460; } /* soft blue */
  .mood-angry      { background-color: #f8d7da; color: #721c24; } /* soft red */
  .mood-excited    { background-color: #fff3cd; color: #856404; } /* soft yellow */
  .mood-calm       { background-color: #e2f0f1; color: #0b5351; } /* soft teal */
  .mood-anxious    { background-color: #ede7f6; color: #4527a0; } /* soft purple */
  .mood-motivated  { background-color: #fff8e1; color: #8d6e63; } /* soft amber */
  .mood-tired      { background-color: #eceff1; color: #37474f; } /* soft gray */
  .mood-stressed   { background-color: #fdecea; color: #b71c1c; } /* soft coral */
  .mood-grateful   { background-color: #f3e5f5; color: #6a1b9a; } /* soft pink-purple */

  .calendar-container {
    background: white;
    border-radius: 20px;
    padding: 25px;
    width: 100%;
    max-width: 900px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 60px;
  }

  #calendar {
    width: 100%;
    min-height: 550px;
    font-size: 0.85rem;
  }

  .calendar-heading {
    font-size: 1.8rem;
    margin-bottom: 20px;
    text-align: center;
    color: #264653;
  }
</style>

<body>
  <main class="wrapper bg-white shadow rounded-4" style="margin-top: 5%;">
    <h1 class="fw-bold mb-2">How are you feeling today?</h1>
    <form id="moodForm" method="POST">
      <input type="hidden" name="moodID" id="moodID">
      <input type="hidden" name="confirm" value="1">
      <div class="mood-scroll">
        <?php
        // Predefined moodID to CSS class mapping
        $moodClasses = [
          1 => 'mood-happy',
          2 => 'mood-sad',
          3 => 'mood-angry',
          4 => 'mood-excited',
          5 => 'mood-calm',
          6 => 'mood-anxious',
          7 => 'mood-motivated',
          8 => 'mood-tired',
          9 => 'mood-stressed',
          10 => 'mood-grateful'
        ];

        $moods = $db->query("SELECT moodID, mood FROM Moods");
        while ($row = $moods->fetchArray(SQLITE3_ASSOC)):
          $moodID = $row['moodID'];
          $mood = $row['mood'];
          $moodClass = $moodClasses[$moodID] ?? '';
        ?>
          <button type="button" class="mood-button <?= $moodClass ?>"
                onclick="handleMoodClick(<?= $moodID ?>, '<?= addslashes($mood) ?>')">
            <?= $mood ?>
          </button>
        <?php endwhile; ?>
      </div>
    </form>

    <div class="calendar-container">
      <h2 class="calendar-heading">📅 Mood Calendar</h2>
      <div id="calendar"></div>
    </div>
  </main>
  <div class="push"></div>
  <?php include(ROOT_PATH . "inc/footer.inc"); ?>
  <script>
    function handleMoodClick(moodID, moodName) {
      if (confirm("Add this mood: " + moodName + "?")) {
        document.getElementById('moodID').value = moodID;
        document.getElementById('moodForm').submit();
      }
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
              $emoji = addslashes($entry['mood']);
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>