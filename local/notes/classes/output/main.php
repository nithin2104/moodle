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
 * Class main
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_notes\output;
use renderable;
use templatable;
use renderer_base;

/**
 * Summary of main
 */
class main implements renderable, templatable {


    /**
     * Summary of cid
     * @var $cid
     */
    public $cid;

    /**
     * Summary of __construct
     * @param mixed $cid
     */
    public function __construct($cid) {
        $this->cid = $cid;
    }


    /**
     * Summary of export_for_template
     * @param \renderer_base $output
     * @return \stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $DB;
        $data = new \stdClass();

        $sql = "SELECT n.id, u.firstname, u.lastname, n.usernotes, n.userid, n.timecreated
                FROM {local_user_notes} n
                LEFT JOIN {user} u ON u.id = n.userid
                WHERE n.linkcontextid = $this->cid
                ORDER BY timecreated DESC";
        $result = $DB->get_records_sql($sql);
        $data->notes = array_values($result);
        return $data;
    }
}
