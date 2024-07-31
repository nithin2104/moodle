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

 require_once(__DIR__ ."/../../config.php");
 require_once($CFG->dirroot . "/local/message/classes/form/edit.php");

 global $DB;

 $PAGE->set_url("/local/message/edit.php");
 $PAGE->set_context(context_system::instance());
 $PAGE->set_title(get_string('edittitle','local_message'));
 $PAGE->set_heading(get_string('headingedit','local_message'));

$mform=new edit();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot ."/local/message/manage.php",get_string('cancelform','local_message') );
} else if ($fromform = $mform->get_data()) {
    $recordtoinsert = new stdClass();    
    $recordtoinsert->messagetext = $fromform->messagetext;
    $recordtoinsert->messagetype = $fromform->messagetype;
    $DB->insert_record('local_message', $recordtoinsert);
    redirect($CFG->wwwroot ."/local/message/manage.php",get_string('submitform','local_message'). $fromform->messagetext);
    
} else {
    $mform->set_data($fromform);
    // Display the form.
    // $mform->display();
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();