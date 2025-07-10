<?php

// Dynamically build BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Automatically detect project folder name (SCSU-CSC400)
$projectFolder = "/SCSU-CSC400";

// BASE_URL: e.g., http://localhost/SCSU-CSC400/
define("BASE_URL", $protocol . "://" . $host . $projectFolder . "/");

// ROOT_PATH: absolute server path to project root (with trailing slash)
define("ROOT_PATH", realpath(__DIR__ . "/../") . DIRECTORY_SEPARATOR);

// Constants
if (!defined("COMPANY_NAME")) define("COMPANY_NAME", "Southern Connecticut State University");
if (!defined("COPYYEAR")) define("COPYYEAR", "2025");
if (!defined("SITE_NAME")) define("SITE_NAME", "OwlMind");

// Nav items
if (!defined("NAV_ITEM_ONE")) define("NAV_ITEM_ONE", "Home");
if (!defined("NAV_ITEM_THREE")) define("NAV_ITEM_THREE", "Journal");
if (!defined("NAV_ITEM_FOUR")) define("NAV_ITEM_FOUR", "Mood Recorder");
if (!defined("NAV_ITEM_FIVE")) define("NAV_ITEM_FIVE", "Resources");
if (!defined("NAV_ITEM_SIX")) define("NAV_ITEM_SIX", "My Profile");
if (!defined("NAV_ITEM_SEVEN")) define("NAV_ITEM_SEVEN", "About Us");

?>
