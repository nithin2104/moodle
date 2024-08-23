<?php
use function PHPUnit\Framework\throwException;
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
 * Folder module version information
 *
 * @package   local_message
 * @copyright 2009 Petr Skoda  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . "/../../config.php");

use local_message\manager as manager;

global $DB;

$PAGE->set_url("/local/message/manage.php");
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->requires->js_call_amd('local_message/messageform', 'init');

$PAGE->set_title(get_string('managetitle', 'local_message'));
$PAGE->set_heading(get_string('headingmanage', 'local_message'));
require_login();

if (isguestuser()) {
    throw new moodle_exception('Guest users not allowed!');
}

if (has_capability('local/message:manage', $context)) {
    $manage = true;
} else {
    $manage = false;
}
$result = (new manager)->get_details();

$templatecontext = [
    "details" => array_values($result),
    'manage' => $manage,
    "createmsg" => get_string('createmsg', 'local_message'),
    "viewmsg" => get_string('viewmsg', 'local_message'),
    "createmsgurl" => new moodle_url("/local/message/edit.php"),
    "viewmsgurl" => new moodle_url("/local/message/viewmsg.php"),
];
echo $OUTPUT->header();

echo $OUTPUT->render_from_template("local_message/manage", $templatecontext);

echo $OUTPUT->footer();
