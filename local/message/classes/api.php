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

/**
 * Class api
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message;

define('MOODLE_URL', 'http://localhost/moodle/webservice/rest/server.php');
define('TOKEN', 'fec45e9104617ff4b31e1f6657447a0c');

/**
 * Summary of get_messages api
 */
class api {

    /**
     * Summary of call_moodle_api
     * @param mixed $function
     * @param mixed $params
     * @return mixed
     */
    public function call_moodle_api($function, $params) {
        $url = MOODLE_URL . '?wstoken=' . TOKEN . '&wsfunction=' . $function . '&moodlewsrestformat=json';
        $response = file_get_contents($url . '&' . http_build_query($params));
        return json_decode($response, true);
    }

    /**
     * Summary of get_messages
     * @return mixed
     */
    public function get_messages() {
        $messages = $this->call_moodle_api('local_message_get_messages', []);
        return $messages;
    }

}
