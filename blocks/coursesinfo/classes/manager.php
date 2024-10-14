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
 * @package    block_coursesinfo
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_coursesinfo;
/**
 * Summary of manager
 */
class manager {

    /**
     * Summary of get_courses_list_student
     * @return array
     */
    public function get_courses_list_student() {
        global $USER, $DB, $CFG;

        $coursestudent = [];

        $courses = enrol_get_my_courses();
        foreach ($courses as $course) {
            $coursesobject = new \stdClass;

            $coursesobject->coid = $course->id;
            $coursesobject->fullname = $course->fullname;

            $sql = "SELECT cm.course, COALESCE(ROUND((COALESCE(SUM(CASE WHEN cmc.completionstate = 1 THEN 1 ELSE 0 END),0)/
                           COUNT(cm.id))*100,2),0)AS cc_percentage
                      FROM {course_modules} cm
                 LEFT JOIN {course_modules_completion} cmc ON cmc.coursemoduleid =cm.id AND cmc.userid = $USER->id
                     WHERE cm.course = $course->id AND cm.module<>10
                  GROUP BY cm.course;";

            $result = array_values($DB->get_records_sql($sql));
            $coursesobject->cc_percentage = $result[0]->cc_percentage;
            $context = \context_course::instance(($course->id));
            $roles = array_values(get_user_roles($context, $USER->id, false));
            $teachers = get_role_users(3, $context);
            $teachernames = "";
            foreach ($teachers as $teachernamess) {
                $teachernames .= fullname($teachernamess) . '<br> ';
            }
            $coursesobject->teachers = $teachernames;

            $teachersnames = get_role_users(3, $context);
            $teachername = "";

            foreach ($teachersnames as $teacher) {
                $teachername .= fullname($teacher) . '...';
                break;
            }
            $coursesobject->teachersnames = $teachername;

            if ($roles[0]->roleid != 3) {
                $coursestudent[] = $coursesobject;
            }
        }

        return array_values($coursestudent);
    }

    /**
     * Summary of get_courses_list_teacher
     * @return array
     */
    public function get_courses_list_teacher() {
        global $USER, $DB, $CFG;
        $coursesteacher = [];
        $courses = enrol_get_my_courses();
        foreach ($courses as $course) {
            $coursesobject = new \stdClass;

            $coursesobject->coid = $course->id;
            $coursesobject->fullname = $course->fullname;

            $context = \context_course::instance(($course->id));
            $roles = array_values(get_user_roles($context, $USER->id, false));

            $students = count(get_role_users(5, $context));
            $coursesobject->students = $students;
            $student = get_role_users(5, $context);
            $studentnames = "";
            foreach ($student as $studentsname) {
                $studentnames .= fullname($studentsname) . "<br>";
            }
            $coursesobject->student = $studentnames;
            $sql = "SELECT COUNT(DISTINCT cc.userid) AS completed_count
                      FROM {course_completions} cc
                     WHERE cc.course = $course->id AND cc.timecompleted IS NOT NULL;";

            $cccompletions = $DB->get_field_sql($sql);
            $coursesobject->cccompletion = $cccompletions;
            if ($roles[0]->roleid != 5) {
                $coursesteacher[] = $coursesobject;
            }
        }
        return ($coursesteacher);
    }
}
