<?php

/**
 * custom_vitals view.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/clinical_rules.php");

use OpenEMR\Common\Forms\FormCustomVitals;

$custom_vitals = new FormCustomVitals($formid);
$custom_vitals->setFormId($formid);

$controller = new C_FormCustomVitals();
$controller->setFormId($formid);

echo $controller->view();