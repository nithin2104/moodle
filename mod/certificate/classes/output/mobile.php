<?php
// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Certificate module capability definition
 *
 * @package    mod_certificate
 * @copyright  2016 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class mobile {

    public static function mobile_course_view($args) {
        global $OUTPUT, $USER, $DB;

        $args = (object) $args;
        $cm = get_coursemodule_from_id('certificate', $args->cmid);

        // Capabilities check.
        require_login($args->courseid, false, $cm, true, true);

        $context = context_module::instance($cm->id);

        require_capability('mod/certificate:view', $context);

        if ($args->userid !== $USER->id) {
            require_capability('mod/certificate:manage', $context);
        }

        $certificate = $DB->get_record('certificate', ['id' => $cm->instance]);

        // Get certificates from external (taking care of exceptions).
        try {
            $issued = mod_certificate_external::issue_certificate($cm->instance);
            $certificates = mod_certificate_external::get_issued_certificates($cm->instance);
            $issues = array_values($certificates['issues']); // Make it mustache compatible.
        } catch (Exception $e) {
            $issues = [];
        }

        // Set timemodified for each certificate.
        foreach ($issues as $issue) {
            if (!empty($issue->timemodified)) {
                continue;
            }

            $issue->timemodified = $issue->timecreated;
        }

        $showget = !$certificate->requiredtime ||
            has_capability('mod/certificate:manage', $context) ||
            certificate_get_course_time($certificate->course) >= ($certificate->requiredtime * 60);

        $certificate->name = format_string($certificate->name);
        [$certificate->intro, $certificate->introformat] = external_format_text(
            $certificate->intro,
            $certificate->introformat,
            $context->id,
            'mod_certificate',
            'intro'
        );

        $data = [
            'certificate' => $certificate,
            'showget' => $showget && count($issues) > 0,
            'issues' => $issues,
            'issue' => $issues[0],
            'numissues' => count($issues),
            'cmid' => $cm->id,
            'courseid' => $args->courseid,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_certificate/mobile_view_page', $data),
                ],
            ],
            'files' => $issues,
        ];
    }

    public static function mobile_issues_view($args) {
        global $OUTPUT, $USER, $DB;

        $args = (object) $args;
        $cm = get_coursemodule_from_id('certificate', $args->cmid);

        // Capabilities check.
        require_login($args->courseid, false, $cm, true, true);

        $context = context_module::instance($cm->id);

        require_capability('mod/certificate:view', $context);

        if ($args->userid != $USER->id) {
            require_capability('mod/certificate:manage', $context);
        }

        $certificate = $DB->get_record('certificate', ['id' => $cm->instance]);

        // Get certificates from external (taking care of exceptions).
        try {
            $issued = mod_certificate_external::issue_certificate($cm->instance);
            $certificates = mod_certificate_external::get_issued_certificates($cm->instance);
            $issues = array_values($certificates['issues']); // Make it mustache compatible.
        } catch (Exception $e) {
            $issues = [];
        }

        $data = ['issues' => $issues];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_certificate/mobile_view_issues', $data),
                ],
            ],
        ];
    }

}