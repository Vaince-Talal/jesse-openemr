<?php

/**
 * C_FormGeneralReadings.class.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once($GLOBALS['fileroot'] . "/library/forms.inc.php");
require_once($GLOBALS['fileroot'] . "/library/patient.inc.php");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Common\Forms\FormGeneralReadings;
use OpenEMR\Common\Twig\TwigContainer;

class C_FormGeneralReadings
{
    /**
     * @var FormGeneralReadings
     */
    public $general_readings;

    var $template_dir;
    var $form_id;
    var $template_mod;
    var $context;

    public function __construct($template_mod = "general", $context = '')
    {
        $this->template_dir = $GLOBALS['fileroot'] . "/interface/forms/general_readings/templates";
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
        if (isset($_GET['formname']) && $_GET['formname'] == 'general_readings') {
            return $this->trend_view();
        }
        
        return $this->view();
    }

    public function trend_view()
    {
        // Get all general readings for this patient
        $sql = "SELECT * FROM form_general_readings WHERE pid = ? ORDER BY date ASC LIMIT 7";
        $results = sqlStatement($sql, [$GLOBALS['pid']]);
        
        $general_readings_data = [];
        while ($row = sqlFetchArray($results)) {
            $general_readings_data[] = $row;
        }
        
        // Define fields array like vitals does
        $generalReadingsFields = [
            [
                'type' => 'textbox',
                'title' => xl('Daily Fluid Intake'),
                'input' => 'daily_fluid_intake',
                'generalReadingsValue' => 'daily_fluid_intake',
                'unit' => 'ml',
                'unitLabel' => xl('ml'),
                'precision' => 2,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Daily Protein Intake'),
                'input' => 'daily_protein_intake',
                'generalReadingsValue' => 'daily_protein_intake',
                'unit' => 'grams',
                'unitLabel' => xl('grams'),
                'precision' => 2,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Shower'),
                'input' => 'shower',
                'generalReadingsValue' => 'shower',
                'unit' => 'scale',
                'unitLabel' => xl('0-5'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Sponge Bath'),
                'input' => 'sponge_bath',
                'generalReadingsValue' => 'sponge_bath',
                'unit' => 'scale',
                'unitLabel' => xl('0-5'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Walking'),
                'input' => 'walking',
                'generalReadingsValue' => 'walking',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('AM Fasting Glucose'),
                'input' => 'am_fasting_glucose',
                'generalReadingsValue' => 'am_fasting_glucose',
                'unit' => 'mg/dL',
                'unitLabel' => xl('mg/dL'),
                'precision' => 2,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('HS Fasting Glucose'),
                'input' => 'hs_fasting_glucose',
                'generalReadingsValue' => 'hs_fasting_glucose',
                'unit' => 'mg/dL',
                'unitLabel' => xl('mg/dL'),
                'precision' => 2,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Energy'),
                'input' => 'energy',
                'generalReadingsValue' => 'energy',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Sleep Pattern'),
                'input' => 'sleep_pattern',
                'generalReadingsValue' => 'sleep_pattern',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Stress Level/Mood'),
                'input' => 'stress_level',
                'generalReadingsValue' => 'stress_level',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Pain'),
                'input' => 'pain',
                'generalReadingsValue' => 'pain',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Abdominal Pain'),
                'input' => 'abdominal_pain',
                'generalReadingsValue' => 'abdominal_pain',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Appetite'),
                'input' => 'appetite',
                'generalReadingsValue' => 'appetite',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Bowel Movements'),
                'input' => 'bowel_movements',
                'generalReadingsValue' => 'bowel_movements',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ],
            [
                'type' => 'textbox',
                'title' => xl('Fatigue'),
                'input' => 'fatigue',
                'generalReadingsValue' => 'fatigue',
                'unit' => 'scale',
                'unitLabel' => xl('0-10'),
                'precision' => 0,
                'codes' => ''
            ]
        ];
        
        $data = [
            'general_readings_data' => $general_readings_data,
            'generalReadingsFields' => $generalReadingsFields,
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
        return $twig->render("general_readings/general_readings.html.twig", $data);
    }

    public function view()
    {
        $this->general_readings = new FormGeneralReadings($this->form_id);
        
        $data = [
            'general_readings' => $this->general_readings,
            'FORM_ACTION' => $GLOBALS['web_root'],
            'DONT_SAVE_LINK' => $GLOBALS['form_exit_url'],
            'STYLE' => $GLOBALS['style'],
            'CSRF_TOKEN_FORM' => CsrfUtils::collectCsrfToken(),
            'has_id' => $this->form_id
        ];
        
        $twig = (new TwigContainer($this->template_dir, $GLOBALS['kernel']))->getTwig();
        return $twig->render("general_readings/general_readings.html.twig", $data);
    }

    public function save_action()
    {
        // Verify CSRF token
        if (!CsrfUtils::verifyCsrfToken($_POST['csrf_token_form'])) {
            CsrfUtils::csrfNotVerified();
        }

        // Get form data
        $id = $_POST['id'] ?? 0;
        $daily_fluid_intake = $_POST['daily_fluid_intake'] ?? 0;
        $daily_protein_intake = $_POST['daily_protein_intake'] ?? 0;
        $shower = $_POST['shower'] ?? 0;
        $sponge_bath = $_POST['sponge_bath'] ?? 0;
        $walking = $_POST['walking'] ?? 0;
        $am_fasting_glucose = $_POST['am_fasting_glucose'] ?? 0;
        $hs_fasting_glucose = $_POST['hs_fasting_glucose'] ?? 0;
        $energy = $_POST['energy'] ?? 0;
        $sleep_pattern = $_POST['sleep_pattern'] ?? 0;
        $stress_level = $_POST['stress_level'] ?? 0;
        $pain = $_POST['pain'] ?? 0;
        $abdominal_pain = $_POST['abdominal_pain'] ?? 0;
        $appetite = $_POST['appetite'] ?? 0;
        $bowel_movements = $_POST['bowel_movements'] ?? 0;
        $fatigue = $_POST['fatigue'] ?? 0;
        $note = $_POST['note'] ?? '';

        // Insert into database
        $sql = "INSERT INTO form_general_readings (
            pid, user, groupname, authorized, activity, date,
            daily_fluid_intake, daily_protein_intake, shower, sponge_bath, walking,
            am_fasting_glucose, hs_fasting_glucose, energy, sleep_pattern, stress_level,
            pain, abdominal_pain, appetite, bowel_movements, fatigue, note
        ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $result = sqlStatement($sql, [
            $GLOBALS['pid'], $_SESSION['authUser'], $_SESSION['authProvider'], 1, 1,
            $daily_fluid_intake, $daily_protein_intake, $shower, $sponge_bath, $walking,
            $am_fasting_glucose, $hs_fasting_glucose, $energy, $sleep_pattern, $stress_level,
            $pain, $abdominal_pain, $appetite, $bowel_movements, $fatigue, $note
        ]);

        if ($result) {
            return "<script>alert('General Readings saved successfully!'); window.location.href='" . $GLOBALS['form_exit_url'] . "';</script>";
        } else {
            return "<script>alert('Error saving General Readings!'); window.history.back();</script>";
        }
    }
}