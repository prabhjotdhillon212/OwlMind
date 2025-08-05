<?php

define("BASE_URL", "/SCSU-CSC400/");
define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/SCSU-CSC400/");

if (!defined("COMPANY_NAME")) define("COMPANY_NAME", "Southern Connecticut State University");
if (!defined("COPYYEAR")) define("COPYYEAR", "2025");

if (!defined("SITE_NAME")) define("SITE_NAME", "OwlMind");

if (!defined("NAV_ITEM_ONE")) define("NAV_ITEM_ONE", "Home");
if (!defined("NAV_ITEM_THREE")) define("NAV_ITEM_THREE", "Reflections");
if (!defined("NAV_ITEM_FOUR")) define("NAV_ITEM_FOUR", "Mood Recorder");
if (!defined("NAV_ITEM_FIVE")) define("NAV_ITEM_FIVE", "Resources");
if (!defined("NAV_ITEM_SIX")) define("NAV_ITEM_SIX", "My Profile");
if (!defined("NAV_ITEM_SEVEN")) define("NAV_ITEM_SEVEN", "About Us");

date_default_timezone_set('America/New_York');

function formatDate ($var) {
    $timezone = new DateTimeZone('America/New_York');
    $format = 'n/j/Y \a\t g:i A T';
    $dateObj = new DateTime($var);
    $dateObj->setTimezone($timezone);
    $formattedDate = $dateObj->format($format);

    return $formattedDate;
}

?>
