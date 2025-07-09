<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../inc/email_service.php';

if (sendWelcomeEmail('dhillonp2@southernct.edu', 'Prabhjot Dhillon')) {
    echo "✅ Test email sent successfully.";
} else {
    echo "❌ Failed to send test email. Check PHP error logs for details.";
}
?>
