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
 * Class api
 *
 * @package    block_courseinfo
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('MOODLE_URL', 'http://localhost/moodle/webservice/rest/server.php');
define('TOKEN', 'd8011bd4fa28ce6e2416873fb7244bab');

/**
 * Summary of api
 */
class api {

    /**
     * Summary of call_moodle_api
     * @param mixed $function
     * @param mixed $params
     * @return mixed
     */
    public function call_moodle_api($function, $params) {
        $url = MOODLE_URL . '?wstoken=' . TOKEN . '&wsfunction=' . $function . '&moodlewsrestformat=json';
        $response = file_get_contents($url . '&' . http_build_query($params));
        return json_decode($response, true);
    }

    /**
     * Summary of get_courses_and_completion
     * @param mixed $userid
     * @return array<int|mixed>[]
     */
    public function get_courses_and_completion($userid) {
        $courses = $this->call_moodle_api('core_enrol_get_users_courses', ['userid' => $userid]);
        $coursedata = [];
        $activities = [];
        // foreach ($courses as $course) {
        //     $courseid = $course['id'];
        //     // $coursename = $course['fullname'];

        //     // Get activity completion status.
        //     $activities[] = $this->call_moodle_api('core_completion_get_activity_completion_status',
        //                                         ['courseid' => $courseid, 'userid' => $userid]);

        //     // $completionprogress = 0;
        //     // $completedactivities = 0;
        //     // $totalactivities = count($activities);

        //     // foreach ($activities as $activity) {
        //     //     if ($activity['completionstate'] == 1) { // 1 means completed
        //     //         $completedactivities++;
        //     //     }
        //     // }

        //     // if ($totalactivities > 0) {
        //     //     $completionprogress = ($completedactivities / $totalactivities) * 100;
        //     // }

        //     // $coursedata[] = [
        //     //     'course_id' => $courseid,
        //     //     'course_name' => $coursename,
        //     //     'completion_progress' => $completionprogress,
        //     // ];
        // }

        return $courses;
    }

    /**
     * Summary of get_teachers_for_courses
     * @param mixed $courseids
     * @return array
     */
    public function get_teachers_for_courses($courseids) {
        $teachers = [];

        foreach ($courseids as $courseid) {
            // Get course participants (assuming role ID for teachers is 3, adjust if necessary).
            $users = $this->call_moodle_api('core_enrol_get_enrolled_users', ['courseid' => $courseid]);
            $teachernames = [];
            foreach ($users as $user) {
                // print_object($user['roles']);
                if (3 == $user['roles'][0]['roleid']) { // Check if the user has the role of teacher.
                    $teachernames[] = $user['fullname'];
                }
            }
            // echo "course id =";
            // print_object($courseid);

            $teachers[$courseid] = $teachernames;
        }

        return $teachers;
    }
}
