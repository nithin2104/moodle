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
 * Class manager
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message;

use core_form\dynamic_form;
use dml_exception;
use moodle_url;
use context;
use context_system;
use stdClass;

/**
 * Summary of manager
 */
class manager {
    /**
     * Summary of timecreated
     * @var $timecreated
     */
    private $timecreated;
    /**
     * Summary of timecreated
     * @var $timeupdated
     */
    private $timeupdated;
    /**
     * Summary of timecreated
     * @var $userid
     */
    private $userid;
    /**
     * Summary of timecreated
     * @var $context
     */
    private $context;

    /**
     * Summary of __construct
     */
    public function __construct() {
        global $USER;
        $this->timecreated = time();
        $this->timeupdated = time();
        $this->userid = $USER->id;
        $this->context = context_system::instance();
    }
    /**
     * Summary of create_update
     * @param object $data
     * @return void
     */
    public function create_update($data) {
        global $DB, $CFG, $USER;

        $context = $this->context;
        $textfileoptions = [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => 10,
            'maxbytes' => $CFG->maxbytes,
            'context' => $context,

        ];
        $item = $data->profile;
        file_save_draft_area_files($item, $context->id, 'local_message', 'profile', $item,
        ['subdirs' => '', 'maxfiles' => 10], null, true);

        if ($data->id > 0) {
            $data->timeupdated = $this->timeupdated;
            $data->userid = $this->userid;
            $data = file_postupdate_standard_editor($data, 'description', $textfileoptions, $context, 'local_message',
                                                    'description', $data->id);
            $DB->update_record('local_message_crud', $data);

        } else {
            $data->timecreated = $this->timecreated;
            $data->timeupdated = $this->timeupdated;
            $data->userid = $this->userid;
            $data->id = $DB->insert_record('local_message_crud', $data);
            $data = file_postupdate_standard_editor(
                $data,
                'description',
                $textfileoptions,
                $context,
                'local_message_crud',
                'description',
                $data->id
            );

        }
        $DB->update_record('local_message_crud', $data);

    }

    /**
     * Summary of upload_bulk_user_records
     * @param stdClass[] $data
     * @return void
     */
    public function upload_bulk_user_records($data) {
        global $DB;
        $DB->insert_records('local_message_crud', $data);
    }

    /**
     * Summary of upload_update_user_records
     * @param mixed $params
     * @return void
     */
    public function upload_update_user_records($params) {
        global $DB;
        $sql = "UPDATE {local_message_crud} SET firstname = :firstname, lastname = :lastname, timeupdated = :timeupdated
                            WHERE userid = :userid";
        $DB->execute($sql, $params);
    }
    /**
     * Summary of get_records
     * @param int $id
     * @return \stdClass
     */
    public function get_message_records($id) {
        global $DB;

        return $DB->get_record('local_message_crud', ['id' => $id]);
    }
    /**
     * Summary of get_details
     * @return array
     */
    public function get_details() {
        global $DB, $USER;
        $context = context_system::instance();
        $result = $DB->get_records('local_message_crud');
        $resultset = [];
        foreach ($result as $rec) {
            $viewrec = new stdClass();
            $viewrec->id = $rec->id;
            $profileid = $rec->profile;
            $viewrec->fullname = $rec->firstname.' '.$rec->lastname;
            $viewrec->userid = $rec->userid;
            if ($rec->userid == $USER->id && (has_capability('local/message:manage', $context) ||
                                                has_capability('local/message:own', $context))) {
                $viewrec->manage = true;

            }
            $viewrec->timecreated = $rec->timecreated;
            $viewrec->timeupdated = $rec->timeupdated;
            $viewrec->description = $rec->description;
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_message', 'profile', $profileid);
            // print_object($files);die;
            if ($files) {
                foreach ($files as $file) {
                    $fileurl = moodle_url::make_pluginfile_url( $file->get_contextid(),
                                                                $file->get_component(),
                                                                $file->get_filearea(),
                                                                $file->get_itemid(),
                                                                $file->get_filepath(),
                                                                $file->get_filename());
                    $viewrec->profile = $fileurl;
                }
            }
            $resultset[] = $viewrec;
        }
        return $resultset;
    }

    /**
     * Summary of view_details
     * @param mixed $id
     * @return array
     */
    public function view_details($id) {
        global $DB;

        return $DB->get_records('local_message_crud', ['id' => $id]);
    }

    /**
     * Summary of delete_record_id
     * @param int $id
     * @return bool
     */
    public function delete_record_id($id) {
        global $DB;
        $DB->delete_records('local_message_crud', ['id' => $id]);
        return true;
    }

}
