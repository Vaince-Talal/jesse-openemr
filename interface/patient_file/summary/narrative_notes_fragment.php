<?php

/**
 * narrative_notes_fragment.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

use OpenEMR\Common\Csrf\CsrfUtils;

require_once("../../globals.php");

// Only check CSRF if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

?>
<div id='narrative_notes'><!--outer div-->
<?php
// Check if table exists first
$table_exists = sqlQuery("SHOW TABLES LIKE 'form_narrative_notes'");
if (!$table_exists) {
    // Table doesn't exist, show registration message
    ?>
  <div style='padding: 20px; text-align: center;'>
    <span class='text' style='font-size: 16px; color: #666; margin-bottom: 20px; display: block;'>
      <?php echo xlt("Narrative Notes form is not installed. Please run the installation script."); ?>
    </span>
    <a href='../../forms/narrative_notes/register_now.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
      <?php echo xlt('Install Narrative Notes'); ?>
    </a>
  </div>
    <?php
} else {
    // Table exists, get today's note or create a blank one
    $today = date('Y-m-d');
    $result = sqlQuery("SELECT id, note_content FROM form_narrative_notes WHERE pid=? AND DATE(date) = ? ORDER BY date DESC LIMIT 1", [$pid, $today]);
    
    $note_id = $result['id'] ?? 0;
    $note_content = $result['note_content'] ?? '';
    ?>
    
    <div class="container-fluid">
        <!-- Hidden CSRF token for JavaScript -->
        <input type="hidden" id="narrative_notes_csrf" value="<?php echo CsrfUtils::collectCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-12">
                <h6 class="mb-2">
                    <i class="fa fa-sticky-note"></i> <?php echo xlt('Daily Narrative Notes'); ?>
                    <small class="text-muted"><?php echo xlt('Auto-saves as you type'); ?></small>
                </h6>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <textarea 
                        class="form-control" 
                        id="narrative_note_content" 
                        rows="8" 
                        placeholder="<?php echo xla('Enter your daily notes here...'); ?>"
                        style="resize: vertical; min-height: 150px;"
                        data-note-id="<?php echo attr($note_id); ?>"
                        data-patient-id="<?php echo attr($pid); ?>"
                    ><?php echo text($note_content); ?></textarea>
                    <small class="form-text text-muted">
                        <?php echo xlt('Notes are automatically saved and reset daily. Last saved:'); ?> 
                        <span id="last_saved_time"><?php echo xlt('Never'); ?></span>
                        <span id="saving_indicator" style="display: none; color: #007bff;">
                            <i class="fa fa-spinner fa-spin"></i> <?php echo xlt('Saving...'); ?>
                        </span>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button type="button" class="btn btn-info btn-sm" onclick="viewHistoricalNotes()">
                        <i class="fa fa-history"></i> <?php echo xlt('View Historical Notes'); ?>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="saveNotes()">
                        <i class="fa fa-save"></i> <?php echo xlt('Save Now'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historical Notes Panel -->
    <div id="historicalNotesPanel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 80%; max-height: 80%; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                <h5 style="margin: 0;"><?php echo xlt('Historical Narrative Notes'); ?></h5>
                <button type="button" onclick="closeHistoricalNotes()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            </div>
            <div class="form-group">
                <label><?php echo xlt('View By Date'); ?>:</label>
                <input type="text" class="form-control datepicker" id="date_picker" placeholder="<?php echo xla('Select date to view notes'); ?>" style="margin-bottom: 10px;" />
            </div>
            <div class="form-group">
                <label><?php echo xlt('Or View All Notes'); ?>:</label>
                <button type="button" class="btn btn-primary btn-sm" onclick="loadAllNotes()">
                    <i class="fa fa-list"></i> <?php echo xlt('Show All Notes'); ?>
                </button>
            </div>
            <div id="historical_notes_content">
                <p class="text-muted"><?php echo xlt('Select a date or click "Show All Notes" to view aggregated notes.'); ?></p>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" class="btn btn-secondary" onclick="closeHistoricalNotes()"><?php echo xlt('Close'); ?></button>
            </div>
        </div>
    </div>

    </div>
    <?php
}
?>
</div>
