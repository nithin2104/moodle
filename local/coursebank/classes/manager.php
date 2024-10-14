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
 * @package    local_coursebank
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_coursebank;

/**
 * Undocumented class
 */
class manager {
    /**
     * Summary of timecreated
     * @var $timecreated
     */
    private $timecreated;
    /**
     * Summary of timecreated
     * @var $timemodified
     */
    private $timemodified;
    /**
     * Summary of timecreated
     * @var $context
     */
    private $context;

    /**
     * Summary of __construct
     */
    public function __construct($contextid) {
        global $USER;
        $this->timecreated = time();
        $this->timemodified = time();
        $this->userid = $USER->id;
        $this->context = \context::instance_by_id($contextid);
    }
    /**
     * Summary of create_update
     * @param mixed $data
     * @return bool|int
     */
    public function create_update($data) {
        global $DB, $CFG, $USER;

        $context = $this->context;
        $contextuser = \context_user::instance($USER->id);
        $item = $data->coursebank;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextuser->id, 'user', 'draft', $item);
        foreach ($files as $f) {
            $file = $f;
        }
        $filename = $file->get_filename();
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $data->name = $filename;
        $data->contenttype = $extension;
        $data->itemid = $data->coursebank;
        $data->contextid = $context->id;
        $data->usercreated = $USER->id;
        $data->timecreated = $this->timecreated;
        $data->timemodified = $this->timemodified;
        $itemid = $DB->insert_record('local_coursebank', $data);
        $filerecord = [
            'contextid' => $context->id,
            'component' => 'local_coursebank',
            'filearea' => 'coursebank',
            'itemid' => $itemid,
            'filepath' => '/',
            'filename' => $filename,
        ];
        $fs->create_file_from_storedfile($filerecord, $file);
        return $itemid;
    }

    /**
     * Summary of get_coursebank_files
     * @return \stdClass[]
     */
    public function get_coursebank_files() {
        global $DB, $CFG, $SITE;
        $data = [];
        $result = $DB->get_records('local_coursebank', ['contextid' => $this->context->id]);
        foreach ($result as $rec) {
            $coursebank = new \stdClass();
            $coursebank->id = $rec->id;
            $coursebank->name = $rec->name;
            $coursebank->contextid = $rec->contextid;
            $coursebank->contenttype = $rec->contenttype;
            $coursebank->usercreated = $rec->usercreated;
            $coursebank->timecreated = $rec->timecreated;
            $coursebank->timemodified = $rec->timemodified;
            $fs = get_file_storage();
            $files = $fs->get_area_files($rec->contextid, 'local_coursebank', 'coursebank', $rec->id);
            if ($files) {
                foreach ($files as $file) {
                    $fileurl = \moodle_url::make_pluginfile_url( $file->get_contextid(),
                                                                $file->get_component(),
                                                                $file->get_filearea(),
                                                                $file->get_itemid(),
                                                                $file->get_filepath(),
                                                                $file->get_filename());
                    $coursebank->file = $fileurl;
                    $ex = ['doc', 'jpeg', 'jpg', 'mp4', 'pdf', 'png', 'xlsx'];
                    if (in_array($coursebank->contenttype, $ex)) {

                        $coursebank->icon = new \moodle_url($CFG->wwwroot.'/local/coursebank/pix/'.$coursebank->contenttype.'.png');
                    } else {
                        $coursebank->icon = new \moodle_url($CFG->wwwroot.'/local/coursebank/pix/all.png');
                    }
                }
            }
            $data[] = $coursebank;
        }
        return $data;

    }
    /**
     * Summary of get_coursecontent_by_id
     * @param mixed $id
     * @return \stdClass
     */
    public function get_coursecontent_by_id($id) {
        global $DB, $CFG, $SITE;
        $data = new \stdClass();
        $rec = $DB->get_record('local_coursebank', ['id' => $id]);
        $data->id = $rec->id;
        $data->contextid = $rec->contextid;
        $data->contenttype = $rec->contenttype;
        $data->usercreated = $rec->usercreated;
        $data->timecreated = $rec->timecreated;
        $data->timemodified = $rec->timemodified;
        $fs = get_file_storage();
        $files = $fs->get_area_files($rec->contextid, 'local_coursebank', 'coursebank', $rec->id);
        if ($files) {
            foreach ($files as $file) {
                $fileurl = \moodle_url::make_pluginfile_url($file->get_contextid(),
                                                            $file->get_component(),
                                                            $file->get_filearea(),
                                                            $file->get_itemid(),
                                                            $file->get_filepath(),
                                                            $file->get_filename());
                $data->file = $fileurl;
                $data->name = $file->get_filename();
                $extensions = ['doc', 'jpeg', 'jpg', 'mp4', 'pdf', 'png', 'xlsx'];
                if (in_array($data->contenttype, $extensions)) {

                    $data->icon = new \moodle_url($CFG->wwwroot.'/local/coursebank/pix/'.$data->contenttype.'.png');
                } else {
                    $data->icon = new \moodle_url($CFG->wwwroot.'/local/coursebank/pix/all.png');
                }
            }
        }
        return $data;
    }
}
