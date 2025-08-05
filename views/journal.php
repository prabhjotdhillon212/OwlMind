<?php
session_start();

include 'db_connection.php';

$saveError = false;

if (!isset($_SESSION['accountID'])) {
    header("Location: login.php");
    exit();
}

$account_id = $_SESSION['accountID'];
$student_id = $_SESSION['studentID'];

try {
    $stmt = $db->prepare("SELECT studentID, fname FROM Student WHERE accountID = :accountID");
    $stmt->bindValue(':accountID', $account_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
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
        echo $entryIDToDelete;
        try {
            $query = "DELETE FROM Journal WHERE entryID = :entryid AND studentID = :studentID";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':entryid', $entryIDToDelete, SQLITE3_INTEGER);
            $stmt->bindValue(':studentID', $student_id, SQLITE3_INTEGER);
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
        $title = $_POST['title'];

        try {
            $query = "INSERT INTO Journal (studentID, entry, timestamp, title) VALUES (:studentID, :entry, :timestamp, :title)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':studentID', $student_id, SQLITE3_INTEGER);
            $stmt->bindValue(':entry', $entry, SQLITE3_TEXT);
            $stmt->bindValue(':timestamp', $timestamp, SQLITE3_TEXT);
            $stmt->bindValue(':title', $title, SQLITE3_TEXT);

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

/* Populate a table element with all the journal entries */
$query = "SELECT entryID, title, timestamp, entry FROM Journal WHERE studentID = :studentID ORDER BY timestamp DESC";
$stmt = $db->prepare($query);
$stmt->bindValue(':studentID', $student_id, SQLITE3_INTEGER);
$entries = $stmt->execute();

// Export using previous query //
if (isset($_POST['export_data'])) {
    
    $output = '';

    $entryarray = [];
    while ($row = $entries->fetchArray(SQLITE3_ASSOC)) {
        formatDate($row['timestamp']);
        $entryarray[] = $row;
        foreach ($entryarray as &$row) {
            unset($row['entryID']); // remove entryID from the exported file.
            formatDate($row['timestamp']);
        }       

        $output .= implode("\n", $row) . "\n\n";
    }

    echo $output;

    $exportFileName = 'reflections.txt';

    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $exportFileName . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Content-Length: ' . strlen($output));

    exit();
}

// In order for file export to work, these must be included here: //
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";

?>

<body>
	<main class="d-flex justify-content-center align-items-start p-5" style="min-height: 100vh; height: 100%;">
	  <section id="journal-create" style="min-width: 75%; margin-bottom: 20px;">
		<div class="journal-form flex-fill bg-white p-4 shadow rounded-4" style="margin-bottom: 5%;">
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
            <input type="text" name="title" class="form-control" placeholder="Title (e.g. 'Feeling anxious')" required>
			<textarea name="entry" class="form-control" placeholder="Start writing your thoughts..." rows="15" required></textarea>
			<button type="submit" class="btn btn-custom">💾 Save Reflection</button>
		  </form>
		</div>
        <div class="journal-entries flex-fill d-flex flex-column bg-white p-4 shadow rounded-4" style="min-width: 100%; margin-bottom: 5%;">
            <div style="margin-bottom: 5%;">
                <h2 class="fw-bold mb-3" style="width: 50%; display: inline; border-bottom: none;"><?php echo $_SESSION['fname']; ?>'s Reflections Archive</h2>
                <form action="" method="POST" class="export-form">
                    <input type="hidden" name="export_data">
                    <button name="export-data" class="btn export-btn">Export</button>
                </form>
            </div>
            <?php if ($row = $entries->fetchArray(SQLITE3_ASSOC)): ?>                  
                <?php do { 
                    $formattedDate = formatDate($row['timestamp']);
                ?>
                <div class="mb-3 p-3 border-start border-4 rounded-3 shadow-sm position-relative" style="border-color: #56cfe1; background: #fff;">
			        <p class="text-muted mb-2" style="font-size: 0.9rem;">🕒 <?= htmlspecialchars($formattedDate) ?></p>
				    <div class="reflection-text" style="max-height: 200px; overflow-y: auto; word-wrap: break-word;">
                        <p class="mb-0" style="white-space: pre-wrap;"><b><?= nl2br(htmlspecialchars($row['title'])) ?></b></p>
				    </div>
                    <button onclick='on(<?php echo json_encode($row); ?>)' class='btn read-btn btn-sm'>📖</button>
                    <form method="POST" action="journal.php" onsubmit="return confirm('Are you sure you want to permanently delete this reflection?');">
				        <input type="hidden" name="delete_entry_id" value="<?= htmlspecialchars((string)$row['entryID']) ?>">
					    <button type="submit" class="btn btn-sm btn-outline-danger border-0 delete-btn">🗑️</button>
				    </form>
			    </div>
                <?php } while ($row = $entries->fetchArray(SQLITE3_ASSOC)); ?>  
                <?php else: ?>
                    <p class="text-muted fst-italic">No reflections saved yet.</p>
                <?php endif; ?>                                          
                                
                <div  id="journal-overlay">
                    <div id="journal-overlay-content">
                        <p><b><span id="overlaytitle"></span></b></p>
                        <p><span id="overlaydate"></span></p>
                        <p><span id="overlayentry"></span></p>                        
                        <button onclick='off()' class='close-btn btn' style='margin-left: 2%; margin-bottom: 10%;'>❌</button>
                    </div>
                </div>
            </div>
        </div>
	  </section>
	</main>
    <div class="push"></div>
	<?php include(ROOT_PATH . "inc/footer.inc"); ?>
    <script>
        const journaloverlay = document.getElementById("journal-overlay");
        function on(entry) {
            
            const entrydate = new Date(entry.timestamp);

            const options = {
                month: "numeric",
                day: "numeric",
                year: "numeric",
                hour: "numeric",
                minute: "numeric",
                hour12: true, // Use 12-hour format with AM/PM
                timeZone: "America/New_York", // Specify the time zone (EDT)
                timeZoneName: "short" // Display the time zone abbreviation (e.g., EDT)
            }

            const formattedDate = new Intl.DateTimeFormat("en-US", options).format(entrydate);

            journaloverlay.style.display = "block";
            document.getElementById('overlaytitle').textContent = entry.title;
            document.getElementById('overlaydate').textContent = formattedDate;
            document.getElementById('overlayentry').textContent = entry.entry;
        }

        function off() {
            journaloverlay.style.display = "none";
        }
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>