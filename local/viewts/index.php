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
$PAGE->set_url(new moodle_url('/local/viewts/index.php'));
$PAGE->set_title(get_string('pluginname', 'local_viewts'));
$PAGE->set_heading(get_string('mainheading', 'local_viewts'));

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
    get_string('pluginname', 'local_viewts'),
    new moodle_url('/local/viewts/index.php'),

);
$thingnode->make_active();

$viewstudent = has_capability('local/viewts:viewstudents', $context);
$viewcourses = has_capability('local/viewts:viewcourses', $context);

$sql = "SELECT distinct(roleid)  FROM {role_assignments} WHERE userid = :userid and (roleid = :role1 or roleid = :role2);";
$userroleid = $DB->get_records_sql($sql, ['userid' => $USER->id, 'role1' => 5, 'role2' => 3]);
$userroleid = array_values($userroleid);
$et = $st = 0;
if (sizeof($userroleid) == 2 ) {
    $et = $userroleid[0]->roleid;
    $st = $userroleid[1]->roleid;
} else if ($userroleid[0]->roleid == 3 ) {
    $et = $userroleid[0]->roleid;
} else {
    $st = $userroleid[0]->roleid;
}
echo $OUTPUT->header();

if ($et != 0) {

    echo html_writer::link(new moodle_url('/local/viewts/studentslist.php'), get_string('studentslist', 'local_viewts'), ['role' => 'button']);
    echo "<br>";
}

if ($st != 0 || $et != 0) {
    
    echo html_writer::link(new moodle_url('/local/viewts/courselist.php'), get_string('courseslist', 'local_viewts'), ['role' => 'button']);
}

echo $OUTPUT->footer();