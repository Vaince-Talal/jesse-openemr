<?php

/**
 * custom_vitals_fragment.php
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
<div id='custom_vitals'><!--outer div-->
<?php
//retrieve most recent set of custom vitals.
// Check if table exists first
$table_exists = sqlQuery("SHOW TABLES LIKE 'form_custom_vitals'");
if (!$table_exists) {
    // Table doesn't exist, show registration message
    ?>
  <div style='padding: 20px; text-align: center;'>
    <span class='text' style='font-size: 16px; color: #666; margin-bottom: 20px; display: block;'>
      <?php echo xlt("Custom Vitals form is not installed. Please run the installation script."); ?>
    </span>
    <a href='../../forms/custom_vitals/register_now.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
      <?php echo xlt('Install Custom Vitals'); ?>
    </a>
  </div>
    <?php
} else {
    // Table exists, try to get data
    $result = sqlQuery("SELECT date, id FROM form_custom_vitals WHERE pid=? ORDER BY date DESC LIMIT 1", [$pid]);

if (!$result) { //If there are no custom vitals recorded
    ?>
  <div style='padding: 20px; text-align: center;'>
    <span class='text' style='font-size: 16px; color: #666; margin-bottom: 20px; display: block;'>
      <?php echo xlt("No custom vitals have been documented."); ?>
    </span>
    <a href='../../forms/custom_vitals/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
      <?php echo xlt('Add Custom Vitals'); ?>
    </a>
  </div>
    <?php
} else {
    ?>
  <span class='text'><b>
    <?php echo xlt('Most recent custom vitals from:') . " " . text($result['date']); ?>
  </b></span>
  <br />
  <br />
    <?php include_once($GLOBALS['incdir'] . "/forms/custom_vitals/report.php");
    custom_vitals_report('', '', 1, $result['id']);
    ?>  <span class='text'>
  <br />
  <a href='../encounter/trend_form.php?formname=custom_vitals' onclick='top.restoreSession()'><?php echo xlt('Click here to view and graph all custom vitals.');?></a>
  <br /><br />
  <a href='../../forms/custom_vitals/new.php' class='btn btn-primary' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>
    <?php echo xlt('Add Custom Vitals'); ?>
  </a>
  <br /><br />
  <form method="post" action="../../forms/custom_vitals/delete.php" style="display: inline;" onsubmit="return confirm('<?php echo xla('Are you sure you want to delete this entry?'); ?>')">
    <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>">
    <input type="hidden" name="id" value="<?php echo attr($result['id']); ?>">
    <button type="submit" class="btn btn-danger btn-sm" style="padding: 5px 10px;"><?php echo xlt('Delete Most Recent Entry'); ?></button>
  </form>
  </span><?php
    } // End of table exists check
} ?>
</div>