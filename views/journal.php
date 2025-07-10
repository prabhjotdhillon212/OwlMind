<?php
session_start();
date_default_timezone_set('America/New_York');
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
include 'db_connection.php';

$saveError = false;

if (!isset($_SESSION['accountID'])) {
    header("Location: login.php");
    exit();
}

$accountID = $_SESSION['accountID'];

try {
    $stmt = $db->prepare("SELECT studentID, fname FROM Student WHERE accountID = :accountID");
    $stmt->bindValue(':accountID', $accountID, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $studentID = $row['studentID'];
        if (!isset($_SESSION['fname'])) {
            $_SESSION['fname'] = $row['fname'];
        }
    } else {
        echo "<p class='text-danger text-center fw-bold'>No student profile found. <a href='logout.php'>Log out?</a></p>";
        exit();
    }
} catch (SQLite3Exception $e) {
    echo "<p class='text-danger text-center fw-bold'>DB Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_entry_id'])) {
        $entryIDToDelete = (int)$_POST['delete_entry_id'];
        try {
            $stmt = $db->prepare("DELETE FROM Journal WHERE entryID = :entryid AND studentID = :studentID");
            $stmt->bindValue(':entryid', $entryIDToDelete, SQLITE3_INTEGER);
            $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
            $stmt->execute();

            if ($db->changes() > 0) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
                exit();
            } else {
                $saveError = true;
            }
        } catch (SQLite3Exception $e) {
            $saveError = true;
            echo "<p class='text-danger'>❌ DB Error on delete: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } elseif (!empty($_POST['entry'])) {
        $entry = htmlspecialchars(trim($_POST['entry']));
        $timestamp = date('Y-m-d H:i:s');
        $defaultTitle = "";

        try {
            $stmt = $db->prepare("INSERT INTO Journal (studentID, entry, timestamp, title) VALUES (:studentID, :entry, :timestamp, :title)");
            $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
            $stmt->bindValue(':entry', $entry, SQLITE3_TEXT);
            $stmt->bindValue(':timestamp', $timestamp, SQLITE3_TEXT);
            $stmt->bindValue(':title', $defaultTitle, SQLITE3_TEXT);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?saved=1");
                exit();
            } else {
                $saveError = true;
            }
        } catch (SQLite3Exception $e) {
            $saveError = true;
            echo "<p class='text-danger'>❌ DB Error on insert: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// LIMIT number of reflections shown initially
$limit = 5;
$stmt = $db->prepare("SELECT * FROM Journal WHERE studentID = :studentID ORDER BY entryID DESC LIMIT :limit");
$stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
$stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
$entries = $stmt->execute();
?>

<style>
.reflection-list-container {
    overflow-y: auto;
    max-height: 500px;
    padding-right: 15px;
}
.reflection-list-container::-webkit-scrollbar,
.reflection-text::-webkit-scrollbar {
    width: 6px;
}
.reflection-list-container::-webkit-scrollbar-thumb,
.reflection-text::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}
.reflection-list-container:hover::-webkit-scrollbar-thumb,
.reflection-text:hover::-webkit-scrollbar-thumb {
    background: #888;
}
.reflection-list-container,
.reflection-text {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 transparent;
}
.delete-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 2px 6px;
    font-size: 0.75rem;
    line-height: 1;
}
.journal-form,
.journal-entries {
    align-self: flex-start;
}

.reflection-list-container {
    max-height: 500px;
    overflow-y: auto;
}
</style>

<main class="d-flex justify-content-center align-items-start p-5" style="min-height: 100vh;">
  <section class="d-flex flex-wrap gap-4" style="max-width: 1100px; width: 100%;">

    <div class="journal-form flex-fill bg-white p-4 shadow rounded-4" style="min-width: 300px;">
      <h1 class="fw-bold mb-2" style="color:#1d3557;">Daily Reflections</h1>
      <p class="text-muted mb-3">Take a moment and write how you’re feeling today.</p>

      <?php if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
        <p class="text-success fw-semibold">✅ Reflection saved successfully!</p>
      <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <p class="text-success fw-semibold">🗑️ Reflection removed successfully!</p>
      <?php elseif ($saveError): ?>
        <p class="text-danger fw-semibold">❌ Something went wrong. Please try again.</p>
      <?php endif; ?>

      <form method="POST" action="journal.php" class="d-flex flex-column gap-3">
        <textarea name="entry" class="form-control" placeholder="Start writing your thoughts..." rows="6" required></textarea>
        <button type="submit" class="btn btn-custom">💾 Save Reflection</button>
      </form>
    </div>

    <div class="journal-entries flex-fill d-flex flex-column bg-white p-4 shadow rounded-4" style="min-width: 300px;">
      <h2 class="fw-bold mb-3">Your Reflections</h2>
      
      <div class="reflection-list-container" id="reflection-list">
        <?php
        $hasEntries = false;
        while ($row = $entries->fetchArray(SQLITE3_ASSOC)):
            $hasEntries = true;
            $dateObj = new DateTime($row['timestamp']);
            $dateObj->setTimezone(new DateTimeZone('America/New_York'));
            $formattedDate = $dateObj->format('n/j/Y \a\t g:i A T');
        ?>
            <div class="mb-3 p-3 border-start border-4 rounded-3 shadow-sm position-relative" style="border-color: #56cfe1; background: #fff;">
                <p class="text-muted mb-2" style="font-size: 0.9rem;">🕒 <?= htmlspecialchars($formattedDate) ?></p>
                <div class="reflection-text" style="max-height: 200px; overflow-y: auto; word-wrap: break-word;">
                    <p class="mb-0" style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($row['entry'])) ?></p>
                </div>
                <form method="POST" action="journal.php" onsubmit="return confirm('Are you sure you want to permanently delete this reflection?');">
                    <input type="hidden" name="delete_entry_id" value="<?= htmlspecialchars((string)$row['entryID']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 delete-btn">🗑️</button>
                </form>
            </div>
        <?php endwhile; ?>

        <?php if (!$hasEntries): ?>
            <p class="text-muted fst-italic text-center">No reflections saved yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php include(ROOT_PATH . "inc/footer.inc"); ?>
