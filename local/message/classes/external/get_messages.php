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

namespace local_message\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_description;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_warnings;
use stdClass;
use context_system;
use local_message\manager as manager;
/**
 * Class get_messages
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_messages extends external_api {

    /**
     * execute_paramenter function for delete record
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([]);

    }


    /**
     * Summary of execute
     * @return array[]
     */
    public static function execute() {
        global $DB;
        $result = $DB->get_records('local_message');
        return $result;
    }

    /**
     * function external_returns.
     *
     * @return external_description
     */
    public static function execute_returns(): external_description {
        return new \external_multiple_structure(
            new external_single_structure(
                [
                    'id' => new external_value(PARAM_INT, 'ID'),
                    'messagetext' => new external_value(PARAM_TEXT, 'messagetext'),
                    'messagetype' => new external_value(PARAM_INT, 'messagetype'),
                ]
            )
        );
    }
}
