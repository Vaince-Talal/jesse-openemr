<?php

/**
 * custom_vitals save.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc.php");
require_once "C_FormCustomVitals.class.php";

$c = new C_FormCustomVitals();
$c->setFormId($_POST['id'] ?? 0);
echo $c->save_action();