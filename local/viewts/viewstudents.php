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
$PAGE->set_url(new moodle_url('/local/viewts/viewstudents.php'));
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
    navigation_node::TYPE_CONTAINER
);
$thingnode2 = $thingnode->add(
    get_string('viewstd', 'local_viewts'),
    new moodle_url('/local/viewts/viewstudents.php'),
);
$thingnode2->make_active();
$cid = required_param('id', PARAM_INT);

$contextid = CONTEXT_COURSE::instance($cid);
$contid = (int) $contextid->id;


$sql = "SELECT u.id, u.firstname, u.lastname 
        FROM {user} u 
        JOIN {role_assignments} re ON u.id = re.userid 
        JOIN {user_enrolments} ue ON u.id = ue.userid 
        JOIN {enrol} e ON ue.enrolid = e.id WHERE e.courseid = :cid and (re.roleid = :roleid and re.contextid = :contextid);";
$courseusers = $DB->get_records_sql($sql, ['roleid' => 5, 'cid' => $cid, 'contextid' => $contid]);

$tempcontext = (object) [
    'courseusers' => array_values($courseusers),
];
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_viewts/courseusers', $tempcontext);
echo $OUTPUT->footer();