<?php

/**
 * general_readings save.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc.php");
require_once "C_FormGeneralReadings.class.php";

$c = new C_FormGeneralReadings();
$c->setFormId($_POST['id'] ?? 0);
echo $c->save_action();