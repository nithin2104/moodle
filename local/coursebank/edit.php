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
 * Create or update contents through the specific content type editor
 *
 * @package    local_coursebank
 * @copyright  2020 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$contextid = required_param('contextid', PARAM_INT);
$pluginname = required_param('plugin', PARAM_PLUGIN);
$id = optional_param('id', null, PARAM_INT);
$library = optional_param('library', null, PARAM_RAW);

$context = context::instance_by_id($contextid, MUST_EXIST);

require_capability('local/coursebank:access', $context);

$returnurl = new \moodle_url('/local/coursebank/view.php', ['contextid' => $contextid, 'id' => $id]);

if (!empty($id)) {
    $record = $DB->get_record('local_coursebank', ['id' => $id], '*', MUST_EXIST);
    $heading = $record->contenttype;
    $contenttypename = $record->contenttype;
    $breadcrumbtitle = get_string('edit');
} else {
    $contenttypename = "contenttype_$pluginname";
    $heading = get_string('addinganew', 'moodle', get_string('description', $contenttypename));
    $breadcrumbtitle = get_string('add');
}

$values = [
    'contextid' => $contextid,
    'plugin' => $pluginname,
    'id' => $id,
    'heading' => $heading,
    'library' => $library,
];

$title = get_string('coursebank', 'local_coursebank');
\core_contentbank\helper::get_page_ready($context, $title, true);
if ($PAGE->course) {
    require_login($PAGE->course->id);
}

if ($context->contextlevel == CONTEXT_COURSECAT) {
    $PAGE->set_primary_active_tab('home');
}

$pageurl = new \moodle_url('/local/coursebank/edit.php', $values);
$PAGE->set_url(new \moodle_url('/local/coursebank/edit.php', $values));
if ($context->id == \context_system::instance()->id) {
    $PAGE->set_context(context_course::instance($context->id));
} else {
    $PAGE->set_context($context);
}
if (!empty($record)) {
    $PAGE->navbar->add($record->name, new \moodle_url(
        '/local\coursebank/view.php',
        ['contextid' => $contextid, 'id' => $id]
    ));
}
$PAGE->navbar->add($breadcrumbtitle);
$PAGE->set_title($title);
$PAGE->set_pagelayout('incourse');
$PAGE->set_secondary_active_tab('coursebank');

$editorform = new local_coursebank\form\editor($pageurl, [
    'contextid' => $contextid,
    'plugin' => $pluginname,
    'id' => $id,
    'heading' => $heading,
    'library' => $library,
]);

if ($editorform->is_cancelled()) {
    if (empty($id)) {
        $returnurl = new \moodle_url('/local/coursebank/index.php', ['contextid' => $context->id]);
    }
    redirect($returnurl);
} else if ($data = $editorform->get_data()) {
    $data->contextid = $contextid;
    $id = $editorform->save_content($data);
    // Just in case we've created a new content.
    $returnurl->param('id', $id);
    redirect($returnurl);
}

echo $OUTPUT->header();
$editorform->display();
echo $OUTPUT->footer();
