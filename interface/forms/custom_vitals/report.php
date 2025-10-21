<?php

/**
 * custom_vitals report.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/clinical_rules.php");

use OpenEMR\Common\Forms\FormCustomVitals;

// Handle PDF/HTML export requests
if (isset($_GET['format']) && isset($_GET['pid'])) {
    $format = $_GET['format'];
    $pid = $_GET['pid'];
    
    // Get all custom vitals for the patient
    $sql = "SELECT * FROM form_custom_vitals WHERE pid = ? ORDER BY date DESC";
    $results = sqlStatement($sql, [$pid]);
    
    $custom_vitals_data = [];
    while ($row = sqlFetchArray($results)) {
        $custom_vitals_data[] = $row;
    }
    
    $patient_data = getPatientData($pid);
    $patient_name = $patient_data['fname'] . " " . $patient_data['lname'];
    
    if ($format === 'pdf') {
        // Generate PDF report
        header('Content-Type: application/pdf');
        echo "<!DOCTYPE html><html><head><title>Custom Vitals Report</title></head><body>";
        echo "<h1>Custom Vitals Report for " . htmlspecialchars($patient_name) . "</h1>";
        echo "<p>Generated on: " . date('Y-m-d H:i:s') . "</p>";
        
        if (!empty($custom_vitals_data)) {
            echo "<table border='1' cellpadding='5' style='width: 100%; border-collapse: collapse;'>";
            echo "<tr style='background-color: #f0f0f0;'>";
            echo "<th>Date</th><th>Systolic BP</th><th>Diastolic BP</th><th>Pulse</th><th>Respiration</th><th>O2 Sat</th><th>MAP</th><th>Notes</th>";
            echo "</tr>";
            
            foreach ($custom_vitals_data as $reading) {
                echo "<tr>";
                echo "<td class='date-col'>" . htmlspecialchars($reading['date']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['bps']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['bpd']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['pulse']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['respiration']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['oxygen_saturation']) . "</td>";
                echo "<td class='number-col'>" . htmlspecialchars($reading['mean_arterial_pressure']) . "</td>";
                echo "<td class='note-col'>" . htmlspecialchars($reading['note'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No custom vitals have been documented.</p>";
        }
        
        echo "<script>window.print();</script>";
        echo "</body></html>";
        exit;
    } elseif ($format === 'html') {
        // Generate HTML report
        header('Content-Type: text/html; charset=utf-8');
        echo "<!DOCTYPE html><html><head><title>Custom Vitals Report</title></head><body>";
        echo "<h1>Custom Vitals Report for " . htmlspecialchars($patient_name) . "</h1>";
        echo "<p>Generated on: " . date('Y-m-d H:i:s') . "</p>";
        
        if (!empty($custom_vitals_data)) {
            foreach ($custom_vitals_data as $reading) {
                echo "<h3>Date: " . htmlspecialchars($reading['date']) . "</h3>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><td>Systolic BP:</td><td>" . htmlspecialchars($reading['bps']) . " mmHg</td></tr>";
                echo "<tr><td>Diastolic BP:</td><td>" . htmlspecialchars($reading['bpd']) . " mmHg</td></tr>";
                echo "<tr><td>Pulse:</td><td>" . htmlspecialchars($reading['pulse']) . " bpm</td></tr>";
                echo "<tr><td>Respiration:</td><td>" . htmlspecialchars($reading['respiration']) . " per min</td></tr>";
                echo "<tr><td>Oxygen Saturation:</td><td>" . htmlspecialchars($reading['oxygen_saturation']) . " %</td></tr>";
                echo "<tr><td>Mean Arterial Pressure (MAP):</td><td>" . htmlspecialchars($reading['mean_arterial_pressure']) . " mmHg</td></tr>";
                if (!empty($reading['note'])) {
                    echo "<tr><td>Notes:</td><td>" . htmlspecialchars($reading['note']) . "</td></tr>";
                }
                echo "</table><br>";
            }
        } else {
            echo "<p>No custom vitals have been documented.</p>";
        }
        
        echo "</body></html>";
        exit;
    }
}

function custom_vitals_report($pid, $encounter, $cols, $id, $print = true)
{
    $data = formFetch("form_custom_vitals", $id);
    $custom_vitals = "";
    
    if ($data) {
        $custom_vitals .= "<table class='table table-striped'>";
        
        // Show specific vitals fields with proper labels and units
        $vitals_fields = [
            'bps' => ['label' => 'Systolic BP', 'unit' => 'mmHg'],
            'bpd' => ['label' => 'Diastolic BP', 'unit' => 'mmHg'],
            'pulse' => ['label' => 'Pulse', 'unit' => 'bpm'],
            'respiration' => ['label' => 'Respiration', 'unit' => 'per min'],
            'oxygen_saturation' => ['label' => 'Oxygen Saturation', 'unit' => '%'],
            'mean_arterial_pressure' => ['label' => 'Mean Arterial Pressure (MAP)', 'unit' => 'mmHg'],
            'note' => ['label' => 'Notes', 'unit' => '']
        ];
        
        foreach ($vitals_fields as $field => $info) {
            $value = $data[$field] ?? '';
            if ($field === 'note') {
                // Show notes even if empty
                $custom_vitals .= "<tr><td><strong>" . xlt($info['label']) . ":</strong></td><td>" . text($value) . "</td></tr>";
            } elseif ($value !== '' && $value !== '0' && $value !== 0) {
                // Show vitals values only if they have meaningful data
                $custom_vitals .= "<tr><td><strong>" . xlt($info['label']) . ":</strong></td><td>" . text($value) . " " . xlt($info['unit']) . "</td></tr>";
            }
        }
        
        $custom_vitals .= "</table>";
    } else {
        // Show message when no readings exist
        $custom_vitals .= "<div style='padding: 20px; text-align: center;'>";
        $custom_vitals .= "<p style='font-size: 16px; color: #666; margin-bottom: 20px;'>";
        $custom_vitals .= xlt('No custom vitals have been documented.');
        $custom_vitals .= "</p>";
        $custom_vitals .= "<a href='../../forms/custom_vitals/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>";
        $custom_vitals .= xlt('Add Custom Vitals');
        $custom_vitals .= "</a>";
        $custom_vitals .= "</div>";
    }

    if ($print) {
        echo $custom_vitals;
    } else {
        return $custom_vitals;
    }
}