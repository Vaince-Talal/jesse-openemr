<?php

/**
 * FormNarrativeNotes represents a collection of narrative notes for a specific patient in the system.
 * @package openemr
 * @link      http://www.open-emr.org
 * @author    Custom Component
 * @copyright Copyright (c) 2024 Custom Component
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Common\Forms;

use OpenEMR\Common\ORDataObject\ORDataObject;
use OpenEMR\Common\Uuid\UuidRegistry;

class FormNarrativeNotes extends ORDataObject
{
    const TABLE_NAME = "form_narrative_notes";

    public $id;
    public $date;
    public $pid;
    public $user;
    public $groupname;
    public $authorized;
    public $activity;
    public $note_content;
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

    public function set_date($date)
    {
        $this->date = $date;
    }

    public function get_date()
    {
        return $this->date;
    }

    public function set_note_content($note_content)
    {
        $this->note_content = $note_content;
    }

    public function get_note_content()
    {
        return $this->note_content;
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

    public function set_uuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function get_uuid()
    {
        return $this->uuid;
    }
}
