<?php include_once "../inc/config.inc"; ?>
<?php
    class MyDB extends SQLite3 {
        function __construct() {
            $this->open('C:/xampp/htdocs/SCSU-CSC400/data/db/owl_db.db');
        }
    }

    $db = new MyDB();
    if(!$db) {
        echo $db->lastErrorMsg();
    } else {
        // echo "Opened database successfully\n";
    }
    
    // toggle to suppress errors / warnings
    error_reporting(0);
?>