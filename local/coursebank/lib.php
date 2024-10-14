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
 * Callback implementations for local_coursebank
 *
 * @package    local_coursebank
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Summary of local_coursebank_extend_navigation_frontpage
 * @param mixed $navigation
 * @return void
 */
function local_coursebank_extend_navigation_frontpage($navigation) {
    $context = \context_system::instance();
    $cap = has_capability('local/coursebank:access', $context);
    if (isloggedin() && !isguestuser() && $cap) {
        $navigation->add(
            get_string('coursebank', 'local_coursebank'),
            new moodle_url('/local/coursebank/index.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
        );
    }
}
/**
 * Summary of local_coursebank_extend_navigation_course
 * @param mixed $navigation
 * @param mixed $course
 * @return void
 */
function local_coursebank_extend_navigation_course($navigation, $course) {
    global $PAGE;
    $context = $PAGE->context;
    $cap = has_capability('local/coursebank:access', $context);
    if (isloggedin() && !isguestuser() && $cap) {
        $navigation->add(
            get_string('coursebank', 'local_coursebank'),
            new moodle_url('/local/coursebank/index.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
        );
    }
}

/**
 * Summary of local_coursebank_extend_navigation_category_settings
 * @param mixed $navigation
 * @param mixed $category
 * @return void
 */
function local_coursebank_extend_navigation_category_settings($navigation, $category) {
    global $PAGE;
    $context = $PAGE->context;
    $cap = has_capability('local/coursebank:access', $context);
    if (isloggedin() && !isguestuser() && $cap) {
        $navigation->add(
            get_string('coursebank', 'local_coursebank'),
            new moodle_url('/local/coursebank/index.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
        );
    }
}

/**
 * Summary of local_coursebank_pluginfile
 * @param mixed $course
 * @param mixed $cm
 * @param mixed $context
 * @param mixed $filearea
 * @param mixed $args
 * @param mixed $forcedownload
 * @param array $options
 * @return void
 */
function local_coursebank_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_coursebank', $filearea, $args[0], '/', $args[1]);

    send_stored_file($file);
}
