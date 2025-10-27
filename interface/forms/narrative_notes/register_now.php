<?php

/**
 * register_now.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/classes/CsrfToken.php");

use OpenEMR\Common\Csrf\CsrfUtils;

// Only check CSRF if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo xlt('Install Narrative Notes Form'); ?></title>
    <link rel="stylesheet" href="<?php echo $GLOBALS['web_root']; ?>/public/assets/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['web_root']; ?>/public/assets/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo xlt('Install Narrative Notes Form'); ?></h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Install the form
                            $sql_file = dirname(__FILE__) . '/table.sql';
                            if (file_exists($sql_file)) {
                                $sql_content = file_get_contents($sql_file);
                                $result = sqlStatement($sql_content);
                                
                                // Also register the form in the registry table
                                $registry_sql = "INSERT INTO registry (name, state, directory, sql_run, unpackaged, date, priority, category, nickname, patient_encounter, therapy_group_encounter, aco_spec) VALUES ('Narrative Notes', 1, 'narrative_notes', 1, 1, NOW(), 0, 'Clinical', '', 1, 0, 'patients|med')";
                                $registry_result = sqlStatement($registry_sql);
                                
                                if ($result && $registry_result) {
                                    echo '<div class="alert alert-success">';
                                    echo xlt('Narrative Notes form has been successfully installed and registered!');
                                    echo '</div>';
                                    echo '<a href="../../patient_file/summary/demographics.php" class="btn btn-primary">';
                                    echo xlt('Go to Patient Dashboard');
                                    echo '</a>';
                                } else {
                                    echo '<div class="alert alert-danger">';
                                    echo xlt('Error installing Narrative Notes form. Please check the database connection.');
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">';
                                echo xlt('SQL file not found. Please check the installation.');
                                echo '</div>';
                            }
                        } else {
                            ?>
                            <p><?php echo xlt('This will install the Narrative Notes form into your OpenEMR database.'); ?></p>
                            <p><?php echo xlt('The form will allow you to add daily narrative notes for patients.'); ?></p>
                            
                            <form method="post">
                                <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>" />
                                <button type="submit" class="btn btn-primary">
                                    <?php echo xlt('Install Narrative Notes Form'); ?>
                                </button>
                                <a href="../../patient_file/summary/demographics.php" class="btn btn-secondary">
                                    <?php echo xlt('Cancel'); ?>
                                </a>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
