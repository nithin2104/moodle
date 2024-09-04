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
require_once(__DIR__ .'/classes/output/renderer.php');
require_login();
$cid = required_param('contextid', PARAM_INT);
$url = new moodle_url('/local/notes/viewnotes.php', ['contextid' => $cid]);
$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->requires->js_call_amd('local_notes/my_datatables', 'init');
$PAGE->requires->css('/local/notes/styles/style.css');
$PAGE->set_heading(get_string('pluginname', 'local_notes'));


$output = $PAGE->get_renderer('local_notes');

// Create an instance of the renderer.
$renderable = new \local_notes\output\main($cid);

echo $OUTPUT->header();

// Render the template with the data.
echo $output->render($renderable);

echo $output->footer();
