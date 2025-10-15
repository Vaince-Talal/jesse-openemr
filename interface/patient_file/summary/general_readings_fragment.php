<?php

/**
 * general_readings_fragment.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");

use OpenEMR\Common\Csrf\CsrfUtils;

// Only check CSRF if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

?>
<div id='general_readings'><!--outer div-->
<?php
//retrieve most recent set of general readings.
$result = sqlQuery("SELECT date, id FROM form_general_readings WHERE pid=? ORDER BY date DESC LIMIT 1", [$pid]);

if (!$result) { //If there are no general readings recorded
    ?>
  <div style='padding: 20px; text-align: center;'>
    <span class='text' style='font-size: 16px; color: #666; margin-bottom: 20px; display: block;'>
      <?php echo xlt("No general readings have been documented."); ?>
    </span>
    <a href='../../forms/general_readings/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
      <?php echo xlt('Add General Readings'); ?>
    </a>
  </div>
    <?php
} else {
    ?>
  <span class='text'><b>
    <?php echo xlt('Most recent general readings from:') . " " . text($result['date']); ?>
  </b></span>
  <br />
  <br />
    <?php include_once($GLOBALS['incdir'] . "/forms/general_readings/report.php");
    general_readings_report('', '', 1, $result['id']);
    ?>  <span class='text'>
  <br />
  <a href='../encounter/trend_form.php?formname=general_readings' onclick='top.restoreSession()'><?php echo xlt('Click here to view and graph all general readings.');?></a>
  <br /><br />
  <a href='../../forms/general_readings/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
    <?php echo xlt('Add General Readings'); ?>
  </a>
  </span><?php
} ?>
</div>
