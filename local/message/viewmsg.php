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
 * TODO describe file viewmsg
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . "/../../config.php");

global $DB;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url("/local/message/viewmsg.php");

$PAGE->set_title(get_string('viewtitle', 'local_message'));
$PAGE->set_heading(get_string('viewtitle', 'local_message'));

require_login();
if (isguestuser()) {
    throw new moodle_exception('Guest users not allowed');
}

if (has_capability('local/message:manage', $context)) {
    $manage = true;
} else {
    $manage = false;
}
$result = $DB->get_records("local_message");
$records = array_values($result);

echo $OUTPUT->header();

$templatecontext = [
    "messages" => $records,
    'manage' => $manage,
    "actionpage" => get_string('actionpage', 'local_message'),
    "updatename" => get_string('updatename', 'local_message'),
    "deletename" => get_string('deletename', 'local_message'),
    "manageurl" => new moodle_url('/local/message/manage.php'),
];

echo $OUTPUT->render_from_template("local_message/viewmsg", $templatecontext);

echo $OUTPUT->footer();
