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
 * Folder module version information
 *
 * @package   local_message
 * @copyright 2009 Petr Skoda  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\manager as manager;

/**
 * Summary of local_message_pluginfile
 * @param mixed $course
 * @param mixed $cm
 * @param mixed $context
 * @param mixed $filearea
 * @param mixed $args
 * @param mixed $forcedownload
 * @param array $options
 */
function local_message_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_message', $filearea, $args[0], '/', $args[1]);

    send_stored_file($file);
}

/**
 * local_message_output_fragment_message
 *
 * @param mixed $args
 * @return mixed
 */
function local_message_output_fragment_message($args) {
    global $DB, $OUTPUT;

    $id = $args['id'];
    $details = (new manager())->view_details($id);

    $contextdetails = [
        'details' => array_values($details),
    ];
    return $OUTPUT->render_from_template('local_message/details', $contextdetails);

}


