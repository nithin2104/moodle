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
 * TODO describe file viewnotes
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

global $DB, $PAGE, $CFG;

$contextid = optional_param('contextid', '', PARAM_INT);
$context = context::instance_by_id($contextid);

if ($context->contextlevel == 50) {
    $course = $DB->get_record('course', ['id' => $context->instanceid]);
    require_login($course);
    $PAGE->set_heading($course->fullname. " : " .get_string('usernotes', 'local_notes'));
} else {
    $course = $DB->get_record('course_modules', ['id' => $context->instanceid]);
    $ccourse = $DB->get_record('course', ['id' => $course->course]);
    require_login($ccourse, false, $course);
    $PAGE->activityheader->disable();
}

$url = new moodle_url('/local/notes/viewnotes.php', ['contextid' => $contextid]);
$PAGE->set_url($url);
$PAGE->set_pagelayout('incourse');
$PAGE->add_body_class('limitedwidth');
$PAGE->requires->js_call_amd('local_notes/my_datatables', 'init');
$PAGE->requires->css('/local/message/styles/style.css');

$renderable = new \local_notes\output\main($contextid);
$output = $PAGE->get_renderer('local_notes');

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('usernotes', 'local_notes'));

echo $output->render($renderable);

echo html_writer::link(
    new moodle_url('/local/notes/index.php', ['contextid' => $contextid]),
    get_string('postnotes', 'local_notes'), ['role' => 'button']
);

echo $output->footer();
