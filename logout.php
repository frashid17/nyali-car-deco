<?php
session_start();           // Start session if it hasn't started
session_unset();           // Clear all session variables
session_destroy();         // End session completely

header("Location: /nyali_car_deco/index.php");  // Redirect to login page
exit;
