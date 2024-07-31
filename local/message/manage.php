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
 
 global $DB;

 $PAGE->set_url("/local/message/manage.php");
 $PAGE->set_context(context_system::instance());
 $PAGE->set_title(get_string('managetitle','local_message'));
 $PAGE->set_heading(get_string('headingmanage','local_message'));
 
 $result = $DB->get_records("local_message");
 $records=array_values($result);

echo $OUTPUT->header();

$templatecontext=(object)[
    "messages" => $records,
    "btnname" => get_string('btnname','local_message'),
    "updatename" => get_string('updatename','local_message'),
    "deletename" => get_string('deletename','local_message'),  
    "createmsgurl" => new moodle_url("/local/message/edit.php"),
    // "updateurl" => new moodle_url("/local/message/update.php?id="),
    // "deleteurl" => new moodle_url("/local/message/delete.php?id")

];

echo $OUTPUT->render_from_template("local_message/manage",$templatecontext);

echo $OUTPUT->footer();