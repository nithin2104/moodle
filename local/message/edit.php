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
use local_message\form\edit as edit;
require_once(__DIR__ . "/../../config.php");
// require_once($CFG->dirroot . "/local/message/classes/form/edit.php");


require_login();
global $DB, $USER;

$PAGE->set_url("/local/message/edit.php");
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title(get_string('edittitle', 'local_message'));
$PAGE->set_heading(get_string('headingedit', 'local_message'));

$mform = new edit();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . "/local/message/manage.php", get_string('cancelform', 'local_message'));
} else if ($fromform = $mform->get_data()) {
    $recordtoinsert = new stdClass();
    $recordtoinsert->messagetext = $fromform->messagetext;
    $recordtoinsert->messagetype = $fromform->messagetype;
    $fromform->id = $DB->insert_record('local_message', $recordtoinsert);
    // Trigger message created event.
    $params = [
        'objectid' => $fromform->id,
        'context' => $context,
        'userid' => $USER->id,
        'other' => [
            'username' => $USER->username,
        ],
    ];
    $event = \local_message\event\message_created::create($params);
    $event->add_record_snapshot('local_message', $fromform);
    $event->trigger();
    // Sending email to admin about message creation.
    $userfrom = core_user::get_noreply_user();
    $messagebody = "Message : $fromform->messagetext \n Message type : $fromform->messagetype \n \n Best Regards, \n Moodle.";
    // email_to_user($USER, $userfrom, 'Message App', $messagebody);
    $message = new \core\message\message();
    $message->component = 'local_message';
    $message->name = 'submitted';
    $message->userfrom = $USER;
    $message->userto = get_admin();
    $message->subject = 'Message Submitted By '. fullname($USER);
    $message->fullmessage = $messagebody;
    $message->fullmessageformat = FORMAT_PLAIN;
    $message->notification = 1;
    message_send($message);

    redirect($CFG->wwwroot . "/local/message/viewmsg.php", get_string('submitform', 'local_message') . $fromform->messagetext);
} else {
    $mform->set_data($fromform);
    // Display the form.
    // $mform->display();.
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
