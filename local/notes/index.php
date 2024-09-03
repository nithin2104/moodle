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
 * TODO describe file index
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
use local_notes\form\notes_form as notes_form;
require_login();
$cid = required_param('contextid', PARAM_INT);
$url = new moodle_url('/local/notes/index.php', ['contextid' => $cid]);
$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('pluginname', 'local_notes'));

$mform = new notes_form(new moodle_url('/local/notes/index.php', ['contextid' => $cid]));
if ($data = $mform->get_data()) {
    $textfileoptions = [
        'trusttext' => true,
        'subdirs' => true,
        'maxfiles' => -1,
        'maxbytes' => $CFG->maxbytes,
        'context' => $context,

    ];
    $data = file_postupdate_standard_editor(
        $data,
        'usernotes',
        $textfileoptions,
        $context,
        'local_notes',
        'usernotes',
        $data->usernotes_editor['itemid']
    );
    $data->itemid = $data->usernotes_editor['itemid'];
    $data->linkcontextid = $cid;
    $data->userid = $USER->id;
    $data->timecreated = time();

    $data->id = $DB->insert_record('local_user_notes', $data);
    redirect(new moodle_url('/local/notes/index.php', ['contextid' => $cid]));
}


echo $OUTPUT->header();
$mform->display();
echo html_writer::link(
    new moodle_url('/local/notes/viewnotes.php', ['contextid' => $cid]), get_string('viewcontextnotes', 'local_notes'),
    ['role' => 'button']
);
echo $OUTPUT->footer();
