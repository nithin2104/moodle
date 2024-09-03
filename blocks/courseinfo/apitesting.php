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
 * TODO describe file apitesting
 *
 * @package    block_courseinfo
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
// Configuration.
define('MOODLE_URL', 'http://localhost/moodle/webservice/rest/server.php');
define('TOKEN', 'd8011bd4fa28ce6e2416873fb7244bab');

// Function to call Moodle API.
/**
 * Summary of call_moodle_api
 * @param mixed $function
 * @param mixed $params
 * @throws \Exception
 * @return mixed
 */
function call_moodle_api($function, $params) {
    $url = MOODLE_URL . '?wstoken=' . TOKEN . '&wsfunction=' . $function . '&moodlewsrestformat=json';
    $response = file_get_contents($url . '&' . http_build_query($params));

    if ($response === false) {
        throw new Exception('API request failed');
    }

    return json_decode($response, true);
}

// Function to get user courses.
/**
 * Summary of get_user_courses
 * @param mixed $userid
 * @return mixed
 */
function get_user_courses($userid) {
    $params = array('userid' => $userid);
    return call_moodle_api('core_enrol_get_users_courses', $params);
}


