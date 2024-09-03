<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_message\event;

/**
 * Event message_created
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class message_created extends \core\event\base {

    /**
     * Set basic properties for the event.
     */
    protected function init() {
        $this->data['objecttable'] = 'local_message';
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Summary of get_name
     * @return string
     */
    public static function get_name() {
        return get_string('eventmessagecreated', 'local_message');
    }

    /**
     * Summary of get_description
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' has added a record with id '$this->objectid' in the local_messages.";
    }

    /**
     * Summary of get_url
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/local/messgae/edit.php');
    }
}
