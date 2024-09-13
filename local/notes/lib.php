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
 * Callback implementations for Notes Plugin
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Summary of local_greetings_extend_navigation_frontpage
 * @param mixed $navigation
 * @return void
 */
function local_notes_extend_navigation_frontpage($navigation) {
    global $PAGE;
    $context = $PAGE->context;
    if (isloggedin() && !isguestuser()) {
        $navigation->add(
            get_string('usernotes', 'local_notes'),
            new moodle_url('/local/notes/index.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
        );
    }
}

/**
 * Summary of local_notes_extend_navigation_course
 * @param mixed $navigation
 * @param mixed $course
 * @return void
 */
function local_notes_extend_navigation_course($navigation, $course) {
    global $PAGE;
    $context = $PAGE->context;

    if (isloggedin() && !isguestuser()) {
        $navigation->add(
            get_string('usernotes', 'local_notes'),
            new moodle_url('/local/notes/index.php', ['contextid' => $context->id]),
            navigation_node::TYPE_CUSTOM,
        );
    }
}


/**
 * Summary of local_notes_extend_navigation_module
 * @param mixed $navigation
 * @param mixed $cm
 * @return void
 */
function local_notes_extend_navigation_module($navigation, $cm) {
    global $PAGE;
    $context = $PAGE->context;
    $url = new moodle_url('/local/notes/index.php', ['contextid' => $context->id]);
    $navigation->add(get_string('usernotes', 'local_notes'),
        $url,
        navigation_node::TYPE_CUSTOM)->set_show_in_secondary_navigation(true);
}

