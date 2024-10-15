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
 * TODO describe file view
 *
 * @package    local_coursebank
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_coursebank\manager as manager;
require('../../config.php');

require_login();

$contextid    = optional_param('contextid', \context_system::instance()->id, PARAM_INT);
$id    = required_param('id', PARAM_INT);
$action    = optional_param('action', '', PARAM_TEXT);
$context = context::instance_by_id($contextid, MUST_EXIST);


$url = new moodle_url('/local/coursebank/view.php', ['contextid' => $contextid, 'id' => $id]);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$title = get_string('coursebank', 'local_coursebank');
\local_coursebank\helper::get_page_ready($context, $title);
if ($PAGE->course) {
    require_login($PAGE->course->id);
}

if ($contextid == \context_system::instance()->id) {
    $PAGE->set_context(context_course::instance($contextid));
} else {
    $PAGE->set_context($context);
}

if ($context->contextlevel == CONTEXT_COURSECAT) {
    $PAGE->set_primary_active_tab('home');
}

$PAGE->set_title($title);
$PAGE->add_body_class('limitedwidth');
$PAGE->set_pagetype('coursebank');
$PAGE->set_secondary_active_tab('coursebank');

if ($action == 'delete') {
    $DB->delete_records('local_coursebank', ['id' => $id]);
    redirect(new moodle_url('/local/coursebank/index.php', ['contextid' => $contextid]), 'Deleted successfully...');
}
$result = (new manager($contextid))->get_coursecontent_by_id($id);

$templatecontext = [
    'actions' => $result,
];
echo $OUTPUT->header();
echo $OUTPUT->heading($title, 2);

echo $OUTPUT->render_from_template('local_coursebank/view', $templatecontext);
echo $OUTPUT->footer();
