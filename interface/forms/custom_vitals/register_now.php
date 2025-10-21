<?php
require_once("../../globals.php");

echo "<h1>Registering Custom Vitals Form</h1>";

// Check if already registered
$result = sqlQuery("SELECT * FROM registry WHERE directory = 'custom_vitals'");
if ($result) {
    echo "<h2>Form is already registered:</h2>";
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
} else {
    echo "<h2>Registering form...</h2>";
    
    // Register the form
    $sql = "INSERT INTO registry (name, state, directory, sql_run, unpackaged, patient_encounter) VALUES (?, ?, ?, ?, ?, ?)";
    $result = sqlStatement($sql, [
        'Custom Vitals Form',
        1,
        'custom_vitals',
        1,
        1,
        1
    ]);
    
    if ($result) {
        echo "<h2>✅ Form registered successfully!</h2>";
    } else {
        echo "<h2>❌ Failed to register form!</h2>";
    }
}

// Check if table exists
$result = sqlQuery("SHOW TABLES LIKE 'form_custom_vitals'");
if ($result) {
    echo "<h2>✅ Table form_custom_vitals exists</h2>";
} else {
    echo "<h2>❌ Table form_custom_vitals does NOT exist!</h2>";
    echo "<h3>Creating table...</h3>";
    
    // Create the table
    $sql = "CREATE TABLE IF NOT EXISTS `form_custom_vitals` (
        `id`                bigint(20)      NOT NULL auto_increment,
        `uuid`              binary(16)      DEFAULT NULL,
        `date`              datetime        default NULL,
        `pid`               bigint(20)      default 0,
        `user`              varchar(255)    default NULL,
        `groupname`         varchar(255)    default NULL,
        `authorized`        tinyint(4)      default 0,
        `activity`          tinyint(4)      default 0,
        `bps`               FLOAT(5,2)      default 0,
        `bpd`               FLOAT(5,2)      default 0,
        `pulse`             FLOAT(5,2)      default 0,
        `respiration`       FLOAT(5,2)      default 0,
        `oxygen_saturation` FLOAT(5,2)      default 0,
        `mean_arterial_pressure` FLOAT(5,2) default 0,
        `note`              VARCHAR(255)    default NULL,
        PRIMARY KEY (id),
        UNIQUE KEY `uuid` (uuid)
    ) ENGINE=InnoDB";
    
    $result = sqlStatement($sql);
    if ($result) {
        echo "<h2>✅ Table created successfully!</h2>";
    } else {
        echo "<h2>❌ Failed to create table!</h2>";
    }
}

echo "<h2>Registration complete!</h2>";
echo "<p><a href='../../patient_file/summary/demographics.php'>Go back to patient summary</a></p>";
?>

