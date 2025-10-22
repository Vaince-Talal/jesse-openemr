<?php
/**
 * Simple test script to generate test data for both forms
 * Run this through the web interface
 */

// Set up session
session_start();
$_SESSION['authUser'] = 'admin';
$_SESSION['authProvider'] = 'Default';
$_SESSION['site_id'] = 'default';

require_once("interface/globals.php");
require_once("library/sql.inc");

echo "<h2>Large Dataset Test</h2>";
echo "<p>Generating test data for both General Readings and Custom Vitals...</p>";

// Configuration
$num_entries = 100; // Start with 100 entries
$patient_id = 1;
$start_date = '2021-01-01';

echo "<p>Entries per form: $num_entries</p>";
echo "<p>Patient ID: $patient_id</p>";
echo "<p>Start Date: $start_date</p>";

// Generate General Readings entries
echo "<h3>General Readings</h3>";
$general_readings_count = 0;
for ($i = 0; $i < $num_entries; $i++) {
    $date = date('Y-m-d H:i:s', strtotime($start_date . " +$i days"));
    
    $walking = rand(0, 100);
    $walking_distance = rand(0, 10);
    $walking_time = rand(0, 120);
    $walking_calories = rand(0, 500);
    $walking_notes = "Test entry $i";
    
    $sql = "INSERT INTO form_general_readings (
        pid, user, groupname, authorized, activity, date,
        walking, walking_units, walking_distance, walking_distance_units,
        walking_time, walking_time_units, walking_calories, walking_calories_units,
        walking_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $result = sqlStatement($sql, [
        $patient_id, 'admin', 'Default', 1, 1, $date,
        $walking, 'steps', $walking_distance, 'miles',
        $walking_time, 'minutes', $walking_calories, 'kcal',
        $walking_notes
    ]);
    
    if ($result) {
        $general_readings_count++;
    }
    
    if ($i % 20 == 0) {
        echo "<p>General Readings: $i entries processed</p>";
        flush();
    }
}

echo "<p><strong>General Readings: $general_readings_count entries created</strong></p>";

// Generate Custom Vitals entries
echo "<h3>Custom Vitals</h3>";
$custom_vitals_count = 0;
for ($i = 0; $i < $num_entries; $i++) {
    $date = date('Y-m-d H:i:s', strtotime($start_date . " +$i days"));
    
    $bps = rand(50, 300);
    $bpd = rand(30, 200);
    $pulse = rand(30, 300);
    $respiration = rand(5, 60);
    $oxygen_saturation = rand(50, 100);
    $mean_arterial_pressure = rand(20, 150);
    $note = "Test vitals entry $i";
    
    $sql = "INSERT INTO form_custom_vitals (
        pid, user, groupname, authorized, activity, date,
        bps, bpd, pulse, respiration, oxygen_saturation, mean_arterial_pressure, note
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $result = sqlStatement($sql, [
        $patient_id, 'admin', 'Default', 1, 1, $date,
        $bps, $bpd, $pulse, $respiration, $oxygen_saturation, $mean_arterial_pressure, $note
    ]);
    
    if ($result) {
        $custom_vitals_count++;
    }
    
    if ($i % 20 == 0) {
        echo "<p>Custom Vitals: $i entries processed</p>";
        flush();
    }
}

echo "<p><strong>Custom Vitals: $custom_vitals_count entries created</strong></p>";

// Summary
echo "<h3>Test Complete</h3>";
echo "<p>General Readings entries: $general_readings_count</p>";
echo "<p>Custom Vitals entries: $custom_vitals_count</p>";
echo "<p>Total entries: " . ($general_readings_count + $custom_vitals_count) . "</p>";

echo "<h3>Next Steps</h3>";
echo "<p>1. <a href='interface/patient_file/summary/demographics.php?set_pid=1'>Test Dashboard Loading</a></p>";
echo "<p>2. <a href='interface/patient_file/encounter/trend_form.php?formname=general_readings'>Test General Readings Trends</a></p>";
echo "<p>3. <a href='interface/patient_file/encounter/trend_form.php?formname=custom_vitals'>Test Custom Vitals Trends</a></p>";
?>