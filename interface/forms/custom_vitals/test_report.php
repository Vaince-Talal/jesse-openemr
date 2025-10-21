<?php
require_once("../../globals.php");
require_once("report.php");

// Test the custom_vitals_report function
echo "<h1>Testing Custom Vitals Report</h1>";

// Get the most recent custom vitals entry
$result = sqlQuery("SELECT date, id FROM form_custom_vitals WHERE pid=? ORDER BY date DESC LIMIT 1", [$GLOBALS['pid']]);

if ($result) {
    echo "<h2>Found entry with ID: " . $result['id'] . "</h2>";
    echo "<h3>Date: " . $result['date'] . "</h3>";
    
    // Test the report function
    echo "<h3>Report Output:</h3>";
    custom_vitals_report('', '', 1, $result['id']);
} else {
    echo "<h2>No custom vitals entries found</h2>";
}

// Test formFetch function
echo "<h3>Testing formFetch:</h3>";
$data = formFetch("form_custom_vitals", $result['id'] ?? 0);
echo "<pre>";
var_dump($data);
echo "</pre>";
?>

