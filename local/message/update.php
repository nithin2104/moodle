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

require_once(__DIR__ . "/../../config.php");
require_once($CFG->dirroot . "/local/message/classes/form/edit.php");

require_login();
global $DB, $USER, $result;

$PAGE->set_url("/local/message/update.php");
$id = optional_param("id", 0, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('updatetitle', 'local_message'));
$PAGE->set_heading(get_string('headingupdate', 'local_message'));



$result = $DB->get_record('local_message', ['id' => $id]);

$mform = new edit();


if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . "/local/message/manage.php", get_string('cancelform', 'local_message'));
} else if ($fromform = $mform->get_data()) {

    $updaterecord = new stdClass();
    $updaterecord->id = $fromform->id;
    $updaterecord->messagetext = $fromform->messagetext;
    $updaterecord->messagetype = $fromform->messagetype;

    $DB->update_record('local_message', $updaterecord, false);
    redirect($CFG->wwwroot . "/local/message/viewmsg.php", get_string('updatedform', 'local_message') . $fromform->messagetext);

} else {
    $mform->set_data($fromform);

}



echo $OUTPUT->header();


if ($result->messagetype === '0') {
    $mform->set_data(['id' => $result->id, 'messagetext' => $result->messagetext, 'messagetype' => '0']);
} else if ($result->messagetype === '1') {
    $mform->set_data(['id' => $result->id, 'messagetext' => $result->messagetext, 'messagetype' => '1']);
} else if ($result->messagetype === '2') {
    $mform->set_data(['id' => $result->id, 'messagetext' => $result->messagetext, 'messagetype' => '2']);
} else {
    $mform->set_data(['id' => $result->id, 'messagetext' => $result->messagetext, 'messagetype' => '3']);
}

$mform->display();

echo $OUTPUT->footer();
