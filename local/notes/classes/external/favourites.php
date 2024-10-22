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

namespace local_notes\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_description;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_warnings;

/**
 * Class favourites
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class favourites extends external_api {

    /**
     * execute_paramenter function for delete record
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'Id of the record'),
                'cid' => new external_value(PARAM_INT, 'context Id of the record'),
                'fav' => new external_value(PARAM_BOOL, 'favourites'),
            ]
            );

    }

    /**
     * Summary of execute
     * @param mixed $id
     * @param mixed $fav
     */
    public static function execute($id, $cid, $fav) {
        global $USER;
        $contextuser = \context_user::instance($USER->id);
        $context = \context::instance_by_id($cid);
        self::validate_parameters(
            self::execute_parameters(),
            [
                'id' => $id,
                'cid' => $cid,
                'fav' => $fav,
            ]
        );
        $ufservice = \core_favourites\service_factory::get_service_for_user_context($contextuser);

        if ($fav) {
            $favourite = $ufservice->create_favourite('local_notes', 'message', $id, $context);
            return ['fav' => $favourite];
        } else {
            $ufservice->delete_favourite('local_notes', 'message', $id, $context);
            return ['delfav' => $id];
        }
    }

    /**
     * function external_returns.
     *
     * @return external_description
     */
    public static function execute_returns(): external_description {
        return new external_single_structure([
            'warnings' => new external_warnings(),
        ]);
    }
}
