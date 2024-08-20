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
 * index file
 *
 * @package   local_viewts
 * @copyright 2024 Nithin Kumar nithin54k@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/viewts/studentlist.php'));
$PAGE->set_title(get_string('pluginname', 'local_viewts'));
$PAGE->set_heading(get_string('stdheading', 'local_viewts'));

require_login();

if (isguestuser()) {
    throw new moodle_exception('Guest not allowed.');
}

$previewnode = $PAGE->navigation->add(
    get_string('pluginname', 'local_viewts'),
    new moodle_url('/local/viewts/index.php'),
    navigation_node::TYPE_CONTAINER
);
$thingnode = $previewnode->add(
    get_string('coursestd', 'local_viewts'),
    new moodle_url('/local/viewts/studentslist.php'),
);
$thingnode->make_active();
$sql = "SELECT c.id, c.fullname, c.shortname FROM {course} c
        JOIN {enrol} e ON e.courseid = c.id
        JOIN {user_enrolments} ue ON ue.enrolid = e.id WHERE ue.userid = :userid and (c.visible <> :visible and c.id <> :cid);";
$courses = $DB->get_records_sql($sql, ['userid' => $USER->id, 'visible' => 0, 'cid' => 1]);
$tempcontext = (object) [
    'courses' => array_values($courses),
];
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_viewts/students', $tempcontext);
echo $OUTPUT->footer();