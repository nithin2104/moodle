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
 * TODO describe file recordform
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_message\form\record as record;
use local_message\manager as manager;
require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');

global $CFG, $DB, $USER;

require_login();

$url = new moodle_url('/local/message/recordform.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading($SITE->fullname);

$mform = new record();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/message/manage.php'));
} else if ($data = $mform->get_data()) {

    if ($data) {

        $importid = csv_import_reader::get_new_iid('message');
        $cir = new csv_import_reader($importid, 'message');
        $content = $mform->get_file_content('uploadrecords');
        $tempfile = tempnam(make_temp_directory('/csvimport'), 'tmp');
        if (!$fp = fopen($tempfile, 'w+b')) {
            $cir->_error = get_string('cannotsavedata', 'error');
            @unlink($tempfile);
            return false;
        }
        fwrite($fp, $content);
        fseek($fp, 0);
        $count = 1;
        $dataobj = [];
        while (($fgetdata = fgetcsv($fp, 1000, ",")) != false) {
            if ($count == 1) {
                $columns = $fgetdata;
                $count = 2;
            } else {
                $data = new stdClass();
                $data->firstname = $fgetdata[0];
                $data->lastname = $fgetdata[1];
                $data->userid = $USER->id;
                $data->timecreated = time();
                $data->timeupdated = time();
                $dataobj[] = $data;
            }
        }
        // $data = (new manager)->get_message_records(16);
        // print_object($dataobj);die;
        (new manager)->upload_bulk_user_records($dataobj);
    }
    redirect(new moodle_url('/local/message/manage.php'));

} else {
    $mform->set_data($data);
}



echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
