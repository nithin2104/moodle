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
 * Goals hierarchy
 *
 * This file defines the current version of the local_message Moodle code being used.
 * This is compared against the values stored in the database to determine
 * whether upgrades should be performed (see lib/db/*.php)
 *
 * @package    local_message
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message\form;

use core_form\dynamic_form;
use moodle_url;
use context;
use context_system;
use local_message\manager as manager;

/**
 * Custom goal class.
 */
class uploadrecords_form extends dynamic_form
{

    /**
     * Form defination
     */
    public function definition()
    {
        global $CFG, $DB, $USER;

        $mform = $this->_form; // Don't forget the underscore!
        $id = $this->optional_param('id', 0, PARAM_INT);
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement(
            'filemanager',
            'uploadrecords',
            'uploadrecords',
            null,
            [
                'subdirs' => 0,
                'maxbytes' => $maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => 50,
                'accepted_types' => ['csv'],
            ]
        );
        $mform->setType('uploadrecords', PARAM_FILE);

        $mform->addElement('hidden', 'status');
        $mform->setType('int', PARAM_INT);
        // Set type of element.
    }

    /**
     * Perform some moodle validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files)
    {
        global $DB;
        $errors = parent::validation($data, $files);

        return $errors;
    }
    /**
     * Returns context where this form is used
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context
    {
        return context_system::instance();
    }

    /**
     * Checks if current user has access to this form, otherwise throws exception
     */
    protected function check_access_for_dynamic_submission(): void
    {
        require_capability('local/greetings:viewmessages', $this->get_context_for_dynamic_submission());
    }

    /**
     * Process dynamic submission
     */
    public function process_dynamic_submission()
    {
        global $CFG, $DB, $USER;
        require_once($CFG->libdir . '/adminlib.php');
        require_once($CFG->libdir . '/csvlib.class.php');
        $data = $this->get_data();
        if ($data) {
            $importid = \csv_import_reader::get_new_iid('message');
            $cir = new \csv_import_reader($importid, 'message');
            $content = $this->get_file_content('uploadrecords');
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

                    $data = new \stdClass();
                    $data->firstname = $fgetdata[0];
                    $data->lastname = $fgetdata[1];
                    $data->userid = $USER->id;
                    $data->timecreated = time();
                    $data->timeupdated = time();
                    $dataobj[] = $data;
                }
            }
            // $data = (new manager)->get_message_records(16);
            // print_object(empty($data->description));die;

            (new manager)->upload_bulk_user_records($dataobj);
        }
    }

    /**
     * Set form data for dynamic submission.
     */
    public function set_data_for_dynamic_submission(): void
    {

    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url
    {

        return new moodle_url(
            '/local/message/manage.php',
        );
    }
}
