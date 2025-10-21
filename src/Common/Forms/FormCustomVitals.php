<?php

/**
 * FormCustomVitals represents a collection of custom vital measurements for a specific patient in the system.
 * @package openemr
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Common\Forms;

use OpenEMR\Common\ORDataObject\ORDataObject;
use OpenEMR\Common\Uuid\UuidRegistry;

class FormCustomVitals extends ORDataObject
{
    const TABLE_NAME = "form_custom_vitals";

    public $id;
    public $date;
    public $pid;
    public $user;
    public $groupname;
    public $authorized;
    public $activity;
    public $bps;
    public $bpd;
    public $pulse;
    public $respiration;
    public $oxygen_saturation;
    public $mean_arterial_pressure;
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
        if (!empty($id) && is_numeric($id)) {
            $this->id = $id;
        }
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_pid($pid)
    {
        if (!empty($pid) && is_numeric($pid)) {
            $this->pid = $pid;
        }
    }

    public function get_pid()
    {
        return $this->pid;
    }

    public function set_date($dt)
    {
        if (!empty($dt)) {
            $this->date = $dt;
        }
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_user($user)
    {
        if (!empty($user)) {
            $this->user = $user;
        }
    }

    public function get_user()
    {
        return $this->user;
    }

    public function set_groupname($groupname)
    {
        if (!empty($groupname)) {
            $this->groupname = $groupname;
        }
    }

    public function get_groupname()
    {
        return $this->groupname;
    }

    public function set_authorized($authorized)
    {
        if (!empty($authorized)) {
            $this->authorized = $authorized;
        }
    }

    public function get_authorized()
    {
        return $this->authorized;
    }

    public function set_activity($activity)
    {
        if (!empty($activity)) {
            $this->activity = $activity;
        }
    }

    public function get_activity()
    {
        return $this->activity;
    }

    public function set_bps($bps)
    {
        if (!empty($bps)) {
            $this->bps = $bps;
        }
    }

    public function get_bps()
    {
        return $this->bps;
    }

    public function set_bpd($bpd)
    {
        if (!empty($bpd)) {
            $this->bpd = $bpd;
        }
    }

    public function get_bpd()
    {
        return $this->bpd;
    }

    public function set_pulse($pulse)
    {
        if (!empty($pulse)) {
            $this->pulse = $pulse;
        }
    }

    public function get_pulse()
    {
        return $this->pulse;
    }

    public function set_respiration($respiration)
    {
        if (!empty($respiration)) {
            $this->respiration = $respiration;
        }
    }

    public function get_respiration()
    {
        return $this->respiration;
    }

    public function set_oxygen_saturation($oxygen_saturation)
    {
        if (!empty($oxygen_saturation)) {
            $this->oxygen_saturation = $oxygen_saturation;
        }
    }

    public function get_oxygen_saturation()
    {
        return $this->oxygen_saturation;
    }

    public function set_mean_arterial_pressure($mean_arterial_pressure)
    {
        if (!empty($mean_arterial_pressure)) {
            $this->mean_arterial_pressure = $mean_arterial_pressure;
        }
    }

    public function get_mean_arterial_pressure()
    {
        return $this->mean_arterial_pressure;
    }

    public function set_note($note)
    {
        if (!empty($note)) {
            $this->note = $note;
        }
    }

    public function get_note()
    {
        return $this->note;
    }

    public function set_uuid($uuid)
    {
        if (!empty($uuid)) {
            $this->uuid = $uuid;
        }
    }

    public function get_uuid()
    {
        return $this->uuid;
    }

    public function get_uuid_string()
    {
        return UuidRegistry::uuidToString($this->uuid);
    }

    public function persist()
    {
        parent::persist();
    }
}