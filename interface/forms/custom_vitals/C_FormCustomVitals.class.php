<?php

/**
 * C_FormCustomVitals.class.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once($GLOBALS['fileroot'] . "/library/forms.inc.php");
require_once($GLOBALS['fileroot'] . "/library/patient.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Common\Forms\FormCustomVitals;
use OpenEMR\Common\Twig\TwigContainer;

class C_FormCustomVitals
{
    /**
     * @var FormCustomVitals
     */
    public $custom_vitals;

    var $template_dir;
    var $form_id;
    var $template_mod;
    var $context;

    public function __construct($template_mod = "general", $context = '')
    {
        $this->template_dir = $GLOBALS['fileroot'] . "/interface/forms/custom_vitals/templates";
        $this->form_id = 0;
        $this->template_mod = $template_mod;
        $this->context = $context;
    }

    public function setFormId($form_id)
    {
        $this->form_id = $form_id;
    }

    public function default_action()
    {
        // Check if this is being accessed via trend_form.php
        if (isset($_GET['formname']) && $_GET['formname'] == 'custom_vitals') {
            return $this->trend_view();
        }
        
        return $this->view();
    }

    public function trend_view()
    {
        // Get the most recent 30 custom vitals for this patient
        $sql = "SELECT * FROM form_custom_vitals WHERE pid = ? ORDER BY date DESC LIMIT 30";
        $results = sqlStatement($sql, [$GLOBALS['pid']]);
        
        $custom_vitals_data = [];
        while ($row = sqlFetchArray($results)) {
            $custom_vitals_data[] = $row;
        }
        
        // Reverse the array to show oldest to newest for display
        $custom_vitals_data = array_reverse($custom_vitals_data);
        
        // Define fields array like vitals does
        $customVitalsFields = [
            [
                'type' => 'textbox',
                'title' => xl('Systolic BP'),
                'input' => 'bps',
                'customVitalsValue' => 'bps',
                'unit' => 'mmHg',
                'unitLabel' => xl('mmHg'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Diastolic BP'),
                'input' => 'bpd',
                'customVitalsValue' => 'bpd',
                'unit' => 'mmHg',
                'unitLabel' => xl('mmHg'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Pulse'),
                'input' => 'pulse',
                'customVitalsValue' => 'pulse',
                'unit' => 'bpm',
                'unitLabel' => xl('bpm'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Respiration'),
                'input' => 'respiration',
                'customVitalsValue' => 'respiration',
                'unit' => 'per min',
                'unitLabel' => xl('per min'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Oxygen Saturation'),
                'input' => 'oxygen_saturation',
                'customVitalsValue' => 'oxygen_saturation',
                'unit' => '%',
                'unitLabel' => xl('%'),
                'precision' => 1,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Mean Arterial Pressure'),
                'input' => 'mean_arterial_pressure',
                'customVitalsValue' => 'mean_arterial_pressure',
                'unit' => 'mmHg',
                'unitLabel' => xl('mmHg'),
                'precision' => 1,
                'codes' => ''
            ]
        ];
        
        $data = [
            'custom_vitals_data' => $custom_vitals_data,
            'customVitalsFields' => $customVitalsFields,
            'FORM_ACTION' => $GLOBALS['web_root'],
            'DONT_SAVE_LINK' => $GLOBALS['form_exit_url'],
            'STYLE' => $GLOBALS['style'],
            'CSRF_TOKEN_FORM' => CsrfUtils::collectCsrfToken(),
            'has_id' => $this->form_id,
            'is_trend_view' => true,
            'is_edit' => true,
            'GLOBALS' => $GLOBALS
        ];
        
        $twig = (new TwigContainer($this->template_dir, $GLOBALS['kernel']))->getTwig();
        return $twig->render("custom_vitals/custom_vitals.html.twig", $data);
    }

    public function view()
    {
        $this->custom_vitals = new FormCustomVitals($this->form_id);
        
        $data = [
            'custom_vitals' => $this->custom_vitals,
            'FORM_ACTION' => $GLOBALS['web_root'],
            'DONT_SAVE_LINK' => $GLOBALS['form_exit_url'],
            'STYLE' => $GLOBALS['style'],
            'CSRF_TOKEN_FORM' => CsrfUtils::collectCsrfToken(),
            'has_id' => $this->form_id
        ];
        
        $twig = (new TwigContainer($this->template_dir, $GLOBALS['kernel']))->getTwig();
        return $twig->render("custom_vitals/custom_vitals.html.twig", $data);
    }

    public function delete_action()
    {
        // Verify CSRF token
        if (!CsrfUtils::verifyCsrfToken($_POST['csrf_token_form'])) {
            CsrfUtils::csrfNotVerified();
        }

        $id = $_POST['id'] ?? 0;
        
        if ($id > 0) {
            // Delete the custom vitals entry
            $sql = "DELETE FROM form_custom_vitals WHERE id = ? AND pid = ?";
            $result = sqlStatement($sql, [$id, $GLOBALS['pid']]);
            
            if ($result) {
                // Redirect back to trend view with success message
                $success_msg = xl("Custom Vitals entry deleted successfully.");
                $redirect_url = $GLOBALS['webroot'] . "/interface/patient_file/encounter/trend_form.php?formname=custom_vitals&success=" . urlencode($success_msg);
                header("Location: " . $redirect_url);
                exit;
            } else {
                // Redirect back to trend view with error message
                $error_msg = xl("Failed to delete Custom Vitals entry.");
                $redirect_url = $GLOBALS['webroot'] . "/interface/patient_file/encounter/trend_form.php?formname=custom_vitals&error=" . urlencode($error_msg);
                header("Location: " . $redirect_url);
                exit;
            }
        } else {
            // Redirect back to trend view with error message
            $error_msg = xl("Invalid entry ID for deletion.");
            $redirect_url = $GLOBALS['webroot'] . "/interface/patient_file/encounter/trend_form.php?formname=custom_vitals&error=" . urlencode($error_msg);
            header("Location: " . $redirect_url);
            exit;
        }
    }

    public function save_action()
    {
        // Verify CSRF token
        if (!CsrfUtils::verifyCsrfToken($_POST['csrf_token_form'])) {
            CsrfUtils::csrfNotVerified();
        }

        // Get form data
        $id = $_POST['id'] ?? 0;
        $bps = $_POST['bps'] ?? 50;
        $bpd = $_POST['bpd'] ?? 0;
        $pulse = $_POST['pulse'] ?? 0;
        $respiration = $_POST['respiration'] ?? 0;
        $oxygen_saturation = $_POST['oxygen_saturation'] ?? 0;
        $mean_arterial_pressure = $_POST['mean_arterial_pressure'] ?? 0;
        $note = $_POST['note'] ?? '';

        // Insert into database
        $sql = "INSERT INTO form_custom_vitals (
            pid, user, groupname, authorized, activity, date,
            bps, bpd, pulse, respiration, oxygen_saturation, mean_arterial_pressure, note
        ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";

        $result = sqlStatement($sql, [
            $GLOBALS['pid'], $_SESSION['authUser'], $_SESSION['authProvider'], 1, 1,
            $bps, $bpd, $pulse, $respiration, $oxygen_saturation, $mean_arterial_pressure, $note
        ]);

        if ($result) {
            // Redirect back to trend view with success message
            $success_msg = xl("Custom Vitals saved successfully!");
            $redirect_url = $GLOBALS['webroot'] . "/interface/patient_file/encounter/trend_form.php?formname=custom_vitals&success=" . urlencode($success_msg);
            header("Location: " . $redirect_url);
            exit;
        } else {
            // Redirect back with error message
            $error_msg = xl("Error saving Custom Vitals!");
            $redirect_url = $GLOBALS['webroot'] . "/interface/patient_file/encounter/trend_form.php?formname=custom_vitals&error=" . urlencode($error_msg);
            header("Location: " . $redirect_url);
            exit;
        }
    }
}
