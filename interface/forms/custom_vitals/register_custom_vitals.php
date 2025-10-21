<?php

/**
 * register_custom_vitals.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/registry.inc.php");

// Register the custom_vitals form
$result = registerForm('custom_vitals', 0, 1, 1);

if ($result) {
    echo "Custom Vitals form registered successfully!<br>";
    
    // Update the registry entry to set patient_encounter = 1
    $registry_entry = getRegistryEntryByDirectory('custom_vitals');
    if ($registry_entry) {
        $update_result = sqlStatement("UPDATE registry SET patient_encounter = 1 WHERE directory = 'custom_vitals'");
        if ($update_result) {
            echo "Patient encounter flag set successfully!<br>";
        }
    }
    
    echo "<br>Form is now available in the encounter forms menu.";
} else {
    echo "Form registration failed or form already exists.";
}

echo "<br><br><a href='../../interface/forms_admin/forms_admin.php'>Go to Forms Administration</a>";