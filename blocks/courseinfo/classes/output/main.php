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
 * @package    block_courseinfo
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_courseinfo\output;
defined('MOODLE_INTERNAL') || die;
require_once(__DIR__.'/../api.php');
require_once($CFG->libdir.'/completionlib.php');
use block_courseinfo\manager as manager;
use renderable;
use templatable;
use renderer_base;
/**
 * Summary of class main
 */
class main implements renderable, templatable {

    /**
     * exporting data for mustache template
     *
     * @param renderer_base $output
     * @return \stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        global $DB, $USER, $CFG;
        $data = new \stdClass();
        // $apiobj = new \api();
        $student = array_values((new manager)->get_mycourse_list_with_teachername_ccp());
        if (count($student) > 0) {

            $data->studenttable = true;
        }
        $teacher = array_values((new manager)->get_mycourses_list_as_teacher_ccp());
        if (count($teacher) > 0) {

            $data->teachertable = true;
        }
        $data->enroled_courses_as_student = $student;
        $data->courses_as_teacher = $teacher;
        return $data;
    }

}
