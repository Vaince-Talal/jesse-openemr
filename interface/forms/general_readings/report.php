<?php

/**
 * general_readings report.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(__DIR__ . "/../../globals.php");
require_once($GLOBALS["srcdir"] . "/api.inc.php");
require_once($GLOBALS['fileroot'] . "/library/patient.inc.php");

// Handle PDF/HTML export requests
if (isset($_GET['format']) && isset($_GET['pid'])) {
    $format = $_GET['format'];
    $pid = $_GET['pid'];
    
    // Get all general readings for the patient
    $sql = "SELECT * FROM form_general_readings WHERE pid = ? ORDER BY date DESC";
    $results = sqlStatement($sql, [$pid]);
    
    $general_readings_data = [];
    while ($row = sqlFetchArray($results)) {
        $general_readings_data[] = $row;
    }
    
    $patient_data = getPatientData($pid);
    $patient_name = $patient_data['fname'] . " " . $patient_data['lname'];
    
    if ($format === 'pdf') {
        // Generate simple PDF using HTML output with print styles
        header('Content-Type: text/html; charset=utf-8');
        echo "<!DOCTYPE html><html><head><title>General Readings Report</title>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 10px; font-size: 10px; }
            table { border-collapse: collapse; width: 100%; margin-top: 20px; table-layout: fixed; }
            th, td { border: 1px solid #ddd; padding: 4px; text-align: left; font-size: 9px; }
            th { background-color: #f2f2f2; font-weight: bold; }
            h1 { color: #333; font-size: 16px; margin-bottom: 10px; }
            .date-col { width: 80px; }
            .number-col { width: 60px; }
            .note-col { width: 120px; }
            @media print { 
                body { margin: 0; }
                @page { size: A4 landscape; margin: 0.5in; }
            }
        </style></head><body>";
        echo "<h1>General Readings Report for " . htmlspecialchars($patient_name) . "</h1>";
        echo "<p>Generated on: " . date('Y-m-d H:i:s') . "</p>";
        
        if (!empty($general_readings_data)) {
            echo "<table>";
            echo "<tr>
                <th class='date-col'>Date</th>
                <th class='number-col'>Fluid (ml)</th>
                <th class='number-col'>Protein (g)</th>
                <th class='number-col'>Shower</th>
                <th class='number-col'>Sponge</th>
                <th class='number-col'>Walking</th>
                <th class='number-col'>AM Glucose</th>
                <th class='number-col'>HS Glucose</th>
                <th class='number-col'>Energy</th>
                <th class='number-col'>Sleep</th>
                <th class='number-col'>Stress</th>
                <th class='number-col'>Pain</th>
                <th class='number-col'>Ab Pain</th>
                <th class='number-col'>Appetite</th>
                <th class='number-col'>Bowel</th>
                <th class='number-col'>Fatigue</th>
                <th class='note-col'>Notes</th>
            </tr>";
            
            foreach ($general_readings_data as $reading) {
                echo "<tr>";
                echo "<td class='date-col'>" . htmlspecialchars($reading['date']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['daily_fluid_intake']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['daily_protein_intake']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['shower']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['sponge_bath']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['walking']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['am_fasting_glucose']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['hs_fasting_glucose']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['energy']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['sleep_pattern']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['stress_level']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['pain']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['abdominal_pain']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['appetite']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['bowel_movements']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['fatigue']) . "</td>";
                echo "<td class='note-col'>" . htmlspecialchars($reading['note'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No general readings have been documented.</p>";
        }
        
        echo "<script>window.print();</script>";
        echo "</body></html>";
        exit;
    } elseif ($format === 'html') {
        // Generate HTML report
        header('Content-Type: text/html; charset=utf-8');
        echo "<!DOCTYPE html><html><head><title>General Readings Report</title></head><body>";
        echo "<h1>General Readings Report for " . htmlspecialchars($patient_name) . "</h1>";
        echo "<p>Generated on: " . date('Y-m-d H:i:s') . "</p>";
        
        if (!empty($general_readings_data)) {
            foreach ($general_readings_data as $reading) {
                echo "<h3>Date: " . htmlspecialchars($reading['date']) . "</h3>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><td>Daily Fluid Intake:</td><td>" . htmlspecialchars($reading['daily_fluid_intake']) . " ml</td></tr>";
                echo "<tr><td>Daily Protein Intake:</td><td>" . htmlspecialchars($reading['daily_protein_intake']) . " grams</td></tr>";
                echo "<tr><td>Shower:</td><td>" . htmlspecialchars($reading['shower']) . "/5</td></tr>";
                echo "<tr><td>Sponge Bath:</td><td>" . htmlspecialchars($reading['sponge_bath']) . "/5</td></tr>";
                echo "<tr><td>Walking:</td><td>" . htmlspecialchars($reading['walking']) . "/10</td></tr>";
                echo "<tr><td>AM Fasting Glucose:</td><td>" . htmlspecialchars($reading['am_fasting_glucose']) . " mg/dL</td></tr>";
                echo "<tr><td>HS Fasting Glucose:</td><td>" . htmlspecialchars($reading['hs_fasting_glucose']) . " mg/dL</td></tr>";
                echo "<tr><td>Energy:</td><td>" . htmlspecialchars($reading['energy']) . "/10</td></tr>";
                echo "<tr><td>Sleep Pattern:</td><td>" . htmlspecialchars($reading['sleep_pattern']) . "/10</td></tr>";
                echo "<tr><td>Stress Level/Mood:</td><td>" . htmlspecialchars($reading['stress_level']) . "/10</td></tr>";
                echo "<tr><td>Pain:</td><td>" . htmlspecialchars($reading['pain']) . "/10</td></tr>";
                echo "<tr><td>Abdominal Pain:</td><td>" . htmlspecialchars($reading['abdominal_pain']) . "/10</td></tr>";
                echo "<tr><td>Appetite:</td><td>" . htmlspecialchars($reading['appetite']) . "/10</td></tr>";
                echo "<tr><td>Bowel Movements:</td><td>" . htmlspecialchars($reading['bowel_movements']) . "/10</td></tr>";
                echo "<tr><td>Fatigue:</td><td>" . htmlspecialchars($reading['fatigue']) . "/10</td></tr>";
                if (!empty($reading['note'])) {
                    echo "<tr><td>Notes:</td><td>" . htmlspecialchars($reading['note']) . "</td></tr>";
                }
                echo "</table><br>";
            }
        } else {
            echo "<p>No general readings have been documented.</p>";
        }
        
        echo "</body></html>";
        exit;
    }
}

function general_readings_report($pid, $encounter, $cols, $id, $print = true)
{
    $count = 0;
    $data = formFetch("form_general_readings", $id);
    $patient_data = getPatientData($GLOBALS['pid']);
    $patient_age = getPatientAge($patient_data['DOB']);

    $general_readings = "";
    if ($data) {
        $general_readings .= "<table><tr>";

        foreach ($data as $key => $value) {
            if (
                $key == "uuid" ||
                $key == "id" || $key == "pid" ||
                $key == "user" || $key == "groupname" ||
                $key == "authorized" || $key == "activity" ||
                $key == "date" || $value == "" ||
                $value == "0000-00-00 00:00:00"
            ) {
                // skip certain data
                continue;
            }

            if ($value == "on") {
                $value = "yes";
            }

            $key = ucwords(str_replace("_", " ", $key));

            $general_readings .= "<td><div class='font-weight-bold d-inline-block'>" . xlt($key) . ": </div></td><td><div class='text' style='display:inline-block'>" . text($value) . "</div></td>";

            $count++;

            if ($count == $cols) {
                $count = 0;
                $general_readings .= "</tr><tr>\n";
            }
        }

        $general_readings .= "</tr></table>";
    } else {
        // Show message when no readings exist
        $general_readings .= "<div style='padding: 20px; text-align: center;'>";
        $general_readings .= "<p style='font-size: 16px; color: #666; margin-bottom: 20px;'>";
        $general_readings .= xlt('No general readings have been documented.');
        $general_readings .= "</p>";
        $general_readings .= "<a href='../../forms/general_readings/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>";
        $general_readings .= xlt('Add General Readings');
        $general_readings .= "</a>";
        $general_readings .= "</div>";
    }

    if ($print) {
        echo $general_readings;
    } else {
        return $general_readings;
    }
}