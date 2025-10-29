<?php

/**
 * get_all_notes.php
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
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$offset = ($page - 1) * $limit;

try {
    // Get total count first
    $count_sql = "SELECT COUNT(*) as total FROM form_narrative_notes WHERE pid = ?";
    $count_result = sqlQuery($count_sql, [$patient_id]);
    $total_notes = $count_result['total'] ?? 0;
    
    // Get notes for this patient, ordered by date DESC with pagination
    $sql = "SELECT * FROM form_narrative_notes WHERE pid = ? ORDER BY date DESC LIMIT ? OFFSET ?";
    $results = sqlStatement($sql, [$patient_id, $limit, $offset]);
    
    $notes = [];
    while ($row = sqlFetchArray($results)) {
        $notes[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $notes,
        'total' => $total_notes,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => ceil($total_notes / $limit)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
