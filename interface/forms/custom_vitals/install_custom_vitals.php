<?php

/**
 * install_custom_vitals.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/registry.inc.php");

echo "<h2>Installing Custom Vitals Component</h2>";

// Step 1: Create the database table
echo "<h3>Step 1: Creating Database Table</h3>";
$sql_file = __DIR__ . "/table.sql";
if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);
    $sql_statements = explode(";", $sql_content);
    
    foreach ($sql_statements as $sql) {
        $sql = trim($sql);
        if (!empty($sql)) {
            try {
                sqlStatement($sql);
                echo "✓ Executed SQL statement successfully<br>";
            } catch (Exception $e) {
                echo "✗ SQL Error: " . $e->getMessage() . "<br>";
            }
        }
    }
    echo "Database table creation completed.<br><br>";
} else {
    echo "✗ SQL file not found: $sql_file<br><br>";
}

// Step 2: Register the form
echo "<h3>Step 2: Registering Form</h3>";
$result = registerForm('custom_vitals', 1, 1, 1);

if ($result) {
    echo "✓ Custom Vitals form registered successfully!<br>";
    
    // Update the registry entry to set patient_encounter = 1
    $registry_entry = getRegistryEntryByDirectory('custom_vitals');
    if ($registry_entry) {
        $update_result = sqlStatement("UPDATE registry SET patient_encounter = 1 WHERE directory = 'custom_vitals'");
        if ($update_result) {
            echo "✓ Patient encounter flag set successfully!<br>";
        }
    }
} else {
    echo "✗ Form registration failed or form already exists.<br>";
}

echo "<br><h3>Installation Complete!</h3>";
echo "<p>The Custom Vitals component is now installed and should appear in:</p>";
echo "<ul>";
echo "<li>Encounter forms menu</li>";
echo "<li>Patient summary dashboard</li>";
echo "<li>Forms administration</li>";
echo "</ul>";

echo "<br><a href='../../interface/forms_admin/forms_admin.php'>Go to Forms Administration</a> | ";
echo "<a href='../../interface/patient_file/summary/demographics.php'>Go to Patient Summary</a>";
