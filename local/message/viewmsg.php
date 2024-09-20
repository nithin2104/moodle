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

global $DB, $USER;

$context = context_system::instance();
$usercontext = context_user::instance($USER->id);
$PAGE->set_context($context);
$PAGE->set_url("/local/message/viewmsg.php");

$PAGE->set_title(get_string('viewtitle', 'local_message'));
$PAGE->set_heading(get_string('viewtitle', 'local_message'));
$PAGE->requires->jquery();
$PAGE->requires->js_call_amd('local_message/my_datatables', 'init');
$PAGE->requires->css('/local/message/styles/style.css');
require_login();
if (isguestuser()) {
    throw new moodle_exception('Guest users not allowed');
}

if (has_capability('local/message:manage', $context)) {
    $manage = true;
} else {
    $manage = false;
}

$ufservice = \core_favourites\service_factory::get_service_for_user_context($usercontext);
list($favsql, $favparams) = $ufservice->get_join_sql_by_type('local_message', 'message', 'favalias', 'c.id');
$sql = "SELECT m.id, m.messagetext, m.messagetype, f.id as fid
        FROM {local_message} m
        LEFT JOIN {favourite} f ON f.itemid = m.id AND f.userid = $USER->id AND f.component = 'local_message'
        ORDER BY CASE WHEN f.id IS NULL THEN m.id ELSE f.id END DESC";

$result = $DB->get_records_sql($sql);
$records = array_values($result);

$api = new local_message\api();


// $result = $api->get_messages();


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
