<?php
require_once("../../globals.php");

echo "<h1>Checking Custom Vitals Registration</h1>";

// Check if custom_vitals is in registry
$result = sqlQuery("SELECT * FROM registry WHERE directory = 'custom_vitals'");
if ($result) {
    echo "<h2>✅ Custom Vitals is registered:</h2>";
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
} else {
    echo "<h2>❌ Custom Vitals is NOT registered!</h2>";
}

// Check if table exists
$result = sqlQuery("SHOW TABLES LIKE 'form_custom_vitals'");
if ($result) {
    echo "<h2>✅ Table form_custom_vitals exists</h2>";
} else {
    echo "<h2>❌ Table form_custom_vitals does NOT exist!</h2>";
}

// Check if there are any entries
$result = sqlQuery("SELECT COUNT(*) as count FROM form_custom_vitals WHERE pid = ?", [$GLOBALS['pid']]);
echo "<h2>Custom Vitals entries for current patient: " . $result['count'] . "</h2>";

// Show all registry entries for custom_vitals
$result = sqlStatement("SELECT * FROM registry WHERE directory LIKE '%custom%' OR directory LIKE '%vitals%'");
echo "<h2>All registry entries containing 'custom' or 'vitals':</h2>";
while ($row = sqlFetchArray($result)) {
    echo "<pre>";
    var_dump($row);
    echo "</pre>";
}
?>

