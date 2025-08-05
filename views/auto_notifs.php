<?php
$notifMessages = [
    ['msg' => "Have you checked in with your mood today?", 'type' => 3],
    ['msg' => "Did you do your journal reflection today?", 'type' => 3]
];

$intervalHours = 6;
$lastPath = ROOT_PATH . 'data/last_notif.txt';
$now = time();

// Load last sent time and last used index
$lastSent = 0;
$lastIndex = -1;
if (file_exists($lastPath)) {
    [$lastSent, $lastIndex] = explode('|', file_get_contents($lastPath) ?: "0|-1");
    $lastSent = (int)$lastSent;
    $lastIndex = (int)$lastIndex;

    if (($now - $lastSent) < ($intervalHours * 3600)) {
        return; // Skip if not enough time passed
    }
}

// Rotate to next message
$nextIndex = ($lastIndex + 1) % count($notifMessages);
$notifText = $notifMessages[$nextIndex]['msg'];
$notifTypeID = $notifMessages[$nextIndex]['type'];

// Insert into Notification table if not already there
$stmt = $db->prepare("INSERT OR IGNORE INTO Notification (notifmsg, notiftypeID) VALUES (:msg, :type)");
$stmt->bindValue(':msg', $notifText, SQLITE3_TEXT);
$stmt->bindValue(':type', $notifTypeID, SQLITE3_INTEGER);
$stmt->execute();

$notifID = $db->lastInsertRowID();
if (!$notifID) {
    $q = $db->prepare("SELECT notifmsgID FROM Notification WHERE notifmsg = :msg");
    $q->bindValue(':msg', $notifText, SQLITE3_TEXT);
    $notifID = $q->execute()->fetchArray(SQLITE3_ASSOC)['notifmsgID'] ?? null;
}
if (!$notifID) return;

// Assign to all students
$result = $db->query("SELECT studentID FROM Student");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $sid = $row['studentID'];
    $exists = $db->querySingle("SELECT COUNT(*) FROM NotifsTable WHERE studentID = '$sid' AND notifsmsgID = '$notifID'");
    if (!$exists) {
        $link = $db->prepare("INSERT INTO NotifsTable (studentID, notifsmsgID, isRead) VALUES (:sid, :nid, 0)");
        $link->bindValue(':sid', $sid, SQLITE3_INTEGER);
        $link->bindValue(':nid', $notifID, SQLITE3_INTEGER);
        $link->execute();
    }
}

// Save last run timestamp and used message index
file_put_contents($lastPath, "$now|$nextIndex");
