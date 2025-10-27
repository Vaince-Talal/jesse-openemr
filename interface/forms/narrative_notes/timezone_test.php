<?php
// Check current timezone
echo "Current timezone: " . date_default_timezone_get() . "\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";
echo "Current date: " . date('Y-m-d') . "\n";

// Test Toronto timezone
date_default_timezone_set('America/Toronto');
echo "Toronto timezone: " . date_default_timezone_get() . "\n";
echo "Toronto time: " . date('Y-m-d H:i:s') . "\n";
echo "Toronto date: " . date('Y-m-d') . "\n";
?>
