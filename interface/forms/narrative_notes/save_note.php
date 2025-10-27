<?php

/**
 * save_note.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("../../../library/api.inc.php");
require_once("../../../library/forms.inc.php");
require_once("../../../library/patient.inc.php");
require_once("../../../library/options.inc.php");
require_once("../../../library/formatting.inc.php");
require_once("../../../src/Common/Csrf/CsrfUtils.php");

use OpenEMR\Common\Csrf\CsrfUtils;

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request for debugging
error_log("Narrative Notes Save Request: " . print_r($_POST, true));

try {
    // Check if we have the required data
    if (!isset($_POST['note_content']) || !isset($_POST['patient_id']) || !isset($_POST['csrf_token_form'])) {
        throw new Exception('Missing required parameters');
    }

    // Verify CSRF token
    if (!CsrfUtils::verifyCsrfToken($_POST['csrf_token_form'])) {
        throw new Exception('CSRF token verification failed');
    }

    $note_content = $_POST['note_content'];
    $note_id = intval($_POST['note_id'] ?? 0);
    $patient_id = intval($_POST['patient_id']);
    $today = date('Y-m-d');

    // Validate patient ID
    if ($patient_id <= 0) {
        throw new Exception('Invalid patient ID');
    }

    // Check if table exists
    $table_check = sqlQuery("SHOW TABLES LIKE 'form_narrative_notes'");
    if (!$table_check) {
        throw new Exception('Narrative notes table does not exist. Please run the installation script.');
    }

    if ($note_id > 0) {
        // Update existing note
        $sql = "UPDATE form_narrative_notes SET 
            note_content = ?, date = NOW()
            WHERE id = ? AND pid = ? AND DATE(date) = ?";
        
        $result = sqlStatement($sql, [
            $note_content, $note_id, $patient_id, $today
        ]);
        
        if ($result !== false) {
            echo json_encode([
                'success' => true,
                'note_id' => $note_id,
                'message' => 'Note updated successfully'
            ]);
        } else {
            throw new Exception('Failed to update note');
        }
    } else {
        // Check if a note already exists for today
        $existing_note = sqlQuery("SELECT id FROM form_narrative_notes WHERE pid = ? AND DATE(date) = ?", [$patient_id, $today]);
        
        if ($existing_note) {
            // Update existing note
            $sql = "UPDATE form_narrative_notes SET 
                note_content = ?, date = NOW()
                WHERE id = ? AND pid = ?";
            
            $result = sqlStatement($sql, [
                $note_content, $existing_note['id'], $patient_id
            ]);
            
            if ($result !== false) {
                echo json_encode([
                    'success' => true,
                    'note_id' => $existing_note['id'],
                    'message' => 'Note updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update existing note');
            }
        } else {
            // Insert new note
            $sql = "INSERT INTO form_narrative_notes (
                pid, user, groupname, authorized, activity, date, note_content
            ) VALUES (?, ?, ?, ?, ?, NOW(), ?)";

            $result = sqlStatement($sql, [
                $patient_id,
                $_SESSION['authUser'] ?? 'system',
                $_SESSION['authProvider'] ?? 'default',
                1,
                1,
                $note_content
            ]);
            
            if ($result !== false) {
                $new_note_id = sqlInsertId();
                echo json_encode([
                    'success' => true,
                    'note_id' => $new_note_id,
                    'message' => 'Note created successfully'
                ]);
            } else {
                throw new Exception('Failed to create note');
            }
        }
    }
} catch (Exception $e) {
    error_log("Narrative Notes Save Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
