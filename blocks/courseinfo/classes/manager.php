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

namespace block_courseinfo;

/**
 * Class manager
 *
 * @package    block_courseinfo
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manager {
    /**
     * Summary of get_allcourse_list
     * @return array
     */
    public function get_mycourse_list_with_teachername_ccp() {

        global $DB, $USER;

        $sql = "SELECT c.id AS course_id, c.fullname AS course_name,
                COALESCE(
                GROUP_CONCAT(DISTINCT CONCAT(t.firstname, ' ', t.lastname) ORDER BY t.lastname SEPARATOR ', '), 'No Teachers')
                AS teachers,
                COALESCE(ROUND((COALESCE(SUM(CASE WHEN cmc.completionstate = 1 THEN 1 ELSE 0 END), 0) / COUNT(cm.id)) * 100, 2), 0)
                AS cc_percentage
                FROM {course} c
                JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50 AND c.id <> 1 and c.visible = 1
                LEFT JOIN {role_assignments} ra ON ra.contextid = ctx.id AND ra.roleid = 3
                LEFT JOIN {user} t ON t.id = ra.userid
                JOIN {course_modules} cm ON cm.course = c.id AND c.id <> 1 and c.visible = 1 AND cm.module <> 10
                LEFT JOIN {course_modules_completion} cmc ON cmc.coursemoduleid = cm.id AND cmc.userid = $USER->id
                WHERE c.id IN (
                    SELECT c.id
                    FROM {course} c
                    JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50 AND c.id <> 1 and c.visible = 1
                    LEFT JOIN {role_assignments} ra ON ra.contextid = ctx.id AND ra.roleid = 5
                    WHERE ra.userid = $USER->id
                )
                GROUP BY
                c.id, c.fullname
                ORDER BY
                c.id DESC;";
        return $DB->get_records_sql($sql);

    }

    /**
     * Summary of get_mycourses_list_as_teacher_ccp
     * @return array
     */
    public function get_mycourses_list_as_teacher_ccp() {
        global $DB, $USER;
        $sql = "SELECT c.id AS course_id, c.fullname AS course_name, COALESCE(COUNT(ra.id),0) AS enroled,
                COALESCE(COUNT(cc.userid),0) AS c_completed
                FROM {course} c
                JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50 AND c.id <> 1 and c.visible = 1
                LEFT JOIN {role_assignments} ra ON ra.contextid = ctx.id AND ra.roleid = 5
                LEFT JOIN {user} u ON u.id = ra.userid
                LEFT JOIN {course_completions}  cc ON cc.course = c.id AND cc.userid = u.id AND cc.timecompleted is not null
                WHERE c.id IN (
                    SELECT c.id
                    FROM {course} c
                    JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50 AND c.id <> 1 and c.visible = 1
                    LEFT JOIN {role_assignments} ra ON ra.contextid = ctx.id AND ra.roleid = 3
                    WHERE ra.userid = $USER->id
                )
                GROUP BY c.id
                ORDER BY c.id;";
        return $DB->get_records_sql($sql);
    }
}
