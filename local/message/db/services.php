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
 * External functions and service declaration for Messages plugin
 *
 * Documentation: {@link https://moodledev.io/docs/apis/subsystems/external/description}
 *
 * @package    local_message
 * @category   webservice
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_message_delete_record' => [
        'classname' => 'local_message\external\delete_record',
        'methodsname' => 'execute',
        'classpath' => 'local/messgae/classes/external/delete_record.php',
        'description' => 'Delete Record',
        'type' => 'read',
        'ajax' => true,
    ],
    'local_message_favourites' => [
        'classname' => 'local_message\external\favourites',
        'methodsname' => 'execute',
        'classpath' => 'local/messgae/classes/external/favourites.php',
        'description' => 'Add Delete favourites',
        'type' => 'read',
        'ajax' => true,
    ],
    'local_message_get_messages' => [
        'classname' => 'local_message\external\get_messages',
        'methodsname' => 'execute',
        'classpath' => 'local/messgae/classes/external/get_messages.php',
        'description' => 'Add Delete favourites',
        'type' => 'read',
        'ajax' => true,
    ],
];
