<?php
// Test script to debug the General Readings controller
require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc.php");
require_once "C_FormGeneralReadings.class.php";

try {
    $c = new C_FormGeneralReadings();
    $c->setFormId(0);
    echo "Controller created successfully\n";
    
    // Test the trend_view method
    $result = $c->trend_view();
    echo "Trend view method executed successfully\n";
    echo "Result length: " . strlen($result) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
