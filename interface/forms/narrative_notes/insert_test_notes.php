<?php

/**
 * insert_test_notes.php - Insert test narrative notes for performance testing
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("../../../library/api.inc.php");

// Check if table exists
$table_check = sqlQuery("SHOW TABLES LIKE 'form_narrative_notes'");
if (!$table_check) {
    die("Table 'form_narrative_notes' does not exist. Please run the installation script first.");
}

// Get patient ID from query parameter or use default
$patient_id = isset($_GET['pid']) ? intval($_GET['pid']) : 1;

// Number of notes to insert
$num_notes = isset($_GET['count']) ? intval($_GET['count']) : 1000;

echo "<!DOCTYPE html><html><head><title>Insert Test Notes</title></head><body style='font-family: Arial; padding: 20px;'>";
echo "<h2>Insert Test Narrative Notes</h2>";
echo "<p>Patient ID: " . htmlspecialchars($patient_id) . "</p>";
echo "<p>Number of notes to insert: " . htmlspecialchars($num_notes) . "</p>";

// Start timer
$start_time = microtime(true);

// Sample note content options
$sample_notes = [
    "Patient appeared well today. No new complaints. Vital signs stable.",
    "Follow-up visit for chronic condition. Patient reports feeling better.",
    "Routine check-up completed. All systems normal.",
    "Patient complaint of headache. Assessed and monitored.",
    "Medication review conducted. Dosages adjusted as needed.",
    "Patient showed improvement in symptoms. Plan to continue current treatment.",
    "Lab results reviewed with patient. Everything within normal range.",
    "Patient education provided regarding condition management.",
    "Follow-up scheduled for next week. Patient advised to return if symptoms worsen.",
    "Insurance authorization obtained. Proceeding with recommended treatment plan."
];

$success_count = 0;
$fail_count = 0;

echo "<div id='progress' style='margin: 20px 0;'>";
echo "<p>Inserting notes... This may take a moment.</p>";
echo "</div>";

// Flush output
if (ob_get_level()) {
    ob_end_flush();
}

// Insert notes in batches
$batch_size = 100;
$num_batches = ceil($num_notes / $batch_size);

for ($batch = 0; $batch < $num_batches; $batch++) {
    $batch_notes = min($batch_size, $num_notes - ($batch * $batch_size));
    
    // Start transaction
    sqlStatement("START TRANSACTION");
    
    $sql = "INSERT INTO form_narrative_notes (pid, user, groupname, authorized, activity, date, note_content) VALUES ";
    $values = [];
    $params = [];
    
    for ($i = 0; $i < $batch_notes; $i++) {
        // Generate random date within last 6 months
        $random_days = rand(0, 180);
        $random_date = date('Y-m-d H:i:s', strtotime("-$random_days days"));
        
        // Get random note content
        $random_note = $sample_notes[array_rand($sample_notes)];
        
        // Add some randomness to the note
        $note_with_number = $random_note . " (Note #" . ($batch * $batch_size + $i + 1) . ")";
        
        $values[] = "(?, ?, ?, 1, 1, ?, ?)";
        $params[] = $patient_id;
        $params[] = 'test_user';
        $params[] = 'test_group';
        $params[] = $random_date;
        $params[] = $note_with_number;
    }
    
    $sql .= implode(", ", $values);
    
    try {
        $result = sqlStatement($sql, $params);
        if ($result) {
            sqlStatement("COMMIT");
            $success_count += $batch_notes;
            echo "<script>document.getElementById('progress').innerHTML += '<p>Batch " . ($batch + 1) . "/$num_batches: $batch_notes notes inserted successfully</p>';</script>";
        } else {
            sqlStatement("ROLLBACK");
            $fail_count += $batch_notes;
            echo "<script>document.getElementById('progress').innerHTML += '<p style=\"color: red;\">Batch " . ($batch + 1) . "/$num_batches: Failed to insert notes</p>';</script>";
        }
    } catch (Exception $e) {
        sqlStatement("ROLLBACK");
        $fail_count += $batch_notes;
        echo "<script>document.getElementById('progress').innerHTML += '<p style=\"color: red;\">Batch " . ($batch + 1) . "/$num_batches: Error - " . htmlspecialchars($e->getMessage()) . "</p>';</script>";
    }
    
    // Flush output to show progress
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    } else {
        flush();
    }
}

// End timer
$end_time = microtime(true);
$elapsed_time = $end_time - $start_time;

echo "<div style='margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;'>";
echo "<h3>Results</h3>";
echo "<p><strong>Successfully inserted:</strong> " . $success_count . " notes</p>";
echo "<p><strong>Failed:</strong> " . $fail_count . " notes</p>";
echo "<p><strong>Time elapsed:</strong> " . number_format($elapsed_time, 2) . " seconds</p>";
echo "<p><strong>Rate:</strong> " . number_format($success_count / $elapsed_time, 2) . " notes/second</p>";
echo "</div>";

echo "<h3>Next Steps</h3>";
echo "<p><a href='../../patient_file/summary/demographics.php?set_pid=" . htmlspecialchars($patient_id) . "' class='btn btn-primary' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>Go to Patient Dashboard to Test Pagination</a></p>";
echo "<p><a href='?pid=" . htmlspecialchars($patient_id) . "&count=1000' style='color: #007bff;'>Insert Another 1000 Notes</a></p>";
echo "</body></html>";
?>

<script>
// Auto-scroll to bottom to see progress
window.onload = function() {
    setTimeout(function() {
        window.scrollTo(0, document.body.scrollHeight);
    }, 100);
};
</script>
