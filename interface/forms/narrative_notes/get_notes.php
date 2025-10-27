<?php

/**
 * get_notes.php
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

// Verify CSRF token
if (!CsrfUtils::verifyCsrfToken($_GET['csrf_token_form'])) {
    echo json_encode(['success' => false, 'error' => 'CSRF token verification failed']);
    exit;
}

$patient_id = $_GET['pid'] ?? $pid;
$date = $_GET['date'] ?? date('Y-m-d');

try {
    $sql = "SELECT * FROM form_narrative_notes WHERE pid = ? AND DATE(date) = ? ORDER BY date DESC LIMIT 1";
    $note = sqlQuery($sql, [$patient_id, $date]);
    
    if ($note) {
        echo json_encode([
            'success' => true,
            'data' => $note
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => null
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
