<?php

/**
 * FormGeneralReadings represents a collection of general reading measurements for a specific patient in the system.
 * @package openemr
 * @link      http://www.open-emr.org
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Common\Forms;

use OpenEMR\Common\ORDataObject\ORDataObject;
use OpenEMR\Common\Uuid\UuidRegistry;

class FormGeneralReadings extends ORDataObject
{
    const TABLE_NAME = "form_general_readings";

    public $id;
    public $date;
    public $pid;
    public $user;
    public $groupname;
    public $authorized;
    public $activity;
    public $daily_fluid_intake;
    public $daily_protein_intake;
    public $shower;
    public $sponge_bath;
    public $walking;
    public $am_fasting_glucose;
    public $hs_fasting_glucose;
    public $energy;
    public $sleep_pattern;
    public $stress_level;
    public $pain;
    public $abdominal_pain;
    public $appetite;
    public $bowel_movements;
    public $fatigue;
    public $note;
    public $uuid;

    /**
     * Constructor sets all Form attributes to their default value
     */
    public function __construct($id = "", $_prefix = "")
    {
        parent::__construct();
        if ($id > 0) {
            $this->id = $id;
        } else {
            $id = "";
            $this->date = $this->get_date();
        }

        $this->_table = self::TABLE_NAME;
        $this->activity = 1;
        $this->pid = $GLOBALS['pid'];
        if (!empty($id)) {
            $this->populate();
        }
    }

    public function populate()
    {
        parent::populate();
    }

    public function toString($html = false)
    {
        $string = "\n" . "ID: " . $this->id . "\n";
        return $html ? nl2br($string) : $string;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_pid($pid)
    {
        $this->pid = $pid;
    }

    public function get_pid()
    {
        return $this->pid;
    }

    public function set_date($date)
    {
        $this->date = $date;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_user($user)
    {
        $this->user = $user;
    }

    public function get_user()
    {
        return $this->user;
    }

    public function set_groupname($groupname)
    {
        $this->groupname = $groupname;
    }

    public function get_groupname()
    {
        return $this->groupname;
    }

    public function set_authorized($authorized)
    {
        $this->authorized = $authorized;
    }

    public function get_authorized()
    {
        return $this->authorized;
    }

    public function set_activity($activity)
    {
        $this->activity = $activity;
    }

    public function get_activity()
    {
        return $this->activity;
    }

    public function set_daily_fluid_intake($daily_fluid_intake)
    {
        $this->daily_fluid_intake = $daily_fluid_intake;
    }

    public function get_daily_fluid_intake()
    {
        return $this->daily_fluid_intake;
    }

    public function set_daily_protein_intake($daily_protein_intake)
    {
        $this->daily_protein_intake = $daily_protein_intake;
    }

    public function get_daily_protein_intake()
    {
        return $this->daily_protein_intake;
    }

    public function set_shower($shower)
    {
        $this->shower = $shower;
    }

    public function get_shower()
    {
        return $this->shower;
    }

    public function set_sponge_bath($sponge_bath)
    {
        $this->sponge_bath = $sponge_bath;
    }

    public function get_sponge_bath()
    {
        return $this->sponge_bath;
    }

    public function set_walking($walking)
    {
        $this->walking = $walking;
    }

    public function get_walking()
    {
        return $this->walking;
    }

    public function set_am_fasting_glucose($am_fasting_glucose)
    {
        $this->am_fasting_glucose = $am_fasting_glucose;
    }

    public function get_am_fasting_glucose()
    {
        return $this->am_fasting_glucose;
    }

    public function set_hs_fasting_glucose($hs_fasting_glucose)
    {
        $this->hs_fasting_glucose = $hs_fasting_glucose;
    }

    public function get_hs_fasting_glucose()
    {
        return $this->hs_fasting_glucose;
    }

    public function set_energy($energy)
    {
        $this->energy = $energy;
    }

    public function get_energy()
    {
        return $this->energy;
    }

    public function set_sleep_pattern($sleep_pattern)
    {
        $this->sleep_pattern = $sleep_pattern;
    }

    public function get_sleep_pattern()
    {
        return $this->sleep_pattern;
    }

    public function set_stress_level($stress_level)
    {
        $this->stress_level = $stress_level;
    }

    public function get_stress_level()
    {
        return $this->stress_level;
    }

    public function set_pain($pain)
    {
        $this->pain = $pain;
    }

    public function get_pain()
    {
        return $this->pain;
    }

    public function set_abdominal_pain($abdominal_pain)
    {
        $this->abdominal_pain = $abdominal_pain;
    }

    public function get_abdominal_pain()
    {
        return $this->abdominal_pain;
    }

    public function set_appetite($appetite)
    {
        $this->appetite = $appetite;
    }

    public function get_appetite()
    {
        return $this->appetite;
    }

    public function set_bowel_movements($bowel_movements)
    {
        $this->bowel_movements = $bowel_movements;
    }

    public function get_bowel_movements()
    {
        return $this->bowel_movements;
    }

    public function set_fatigue($fatigue)
    {
        $this->fatigue = $fatigue;
    }

    public function get_fatigue()
    {
        return $this->fatigue;
    }

    public function set_note($note)
    {
        $this->note = $note;
    }

    public function get_note()
    {
        return $this->note;
    }

    public function set_uuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function get_uuid()
    {
        return $this->uuid;
    }
}
