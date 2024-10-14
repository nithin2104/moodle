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
 * Class containing data for the Recently accessed courses block.
 *
 * @package    block_coursesinfo
 * @copyright  2018 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_coursesinfo\output;

use renderable;
use renderer_base;
use templatable;
use block_coursesinfo\manager;

/**
 * Class containing data for Recently accessed courses block.
 *
 * @package    block_coursesinfo
 * @copyright  2018 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return \stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $DB, $CFG;

        $data = new \stdClass();

        $students = array_values((new manager)->get_courses_list_student());
        if (count($students) > 0) {
            $data->studentsTable = true;
        }
        $data->students = $students;
        if ($t = (new manager)->get_courses_list_teacher()) {

            $teacher = array_values($t);
            if (count($teacher) > 0) {
                $data->teacherTable = true;
            }
            $data->teacher = $teacher;
        }

        $data->wwwroot = $GLOBALS['CFG']->wwwroot;
        return $data;
    }
}
