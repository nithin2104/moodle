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
 * View wiki snippet instance
 *
 * @package    mod_wikisnippet
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

list($course, $cm) = get_course_and_cm_from_cmid($id, 'wikisnippet');
$snippetid = $cm->instance;
$snippetrecord = $DB->get_record('wikisnippet', ['id' => $snippetid], '*', MUST_EXIST);

require_login($course, true, $cm);

$PAGE->set_url('/mod/wikisnippet/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($snippetrecord->name));
$PAGE->set_heading(format_string($snippetrecord->name));
$PAGE->set_context(context_module::instance($cm->id));
$PAGE->set_pagelayout('course');

$updaterequired = false;
$cache = cache::make('mod_wikisnippet', 'wikidata');
if ($cached = $cache->get($snippetrecord->id)) {
    if ((time() - $cached['time']) > mod_wikisnippet\snippet::$cachettl) {
        $updaterequired = true;
    } else {
        $content = $cached['content'];
    }
} else {
    $updaterequired = true;
}

if ($updaterequired) {
    if ($content = mod_wikisnippet\snippet::get($snippetrecord->wikiurl, $snippetrecord->nolinks, $snippetrecord->noimages)) {
        $cache->set($snippetrecord->id, ['time' => time(), 'content' => $content]);
    } else {
        if (!empty($cached['content'])) {
            $content = $cached['content'];
        }
    }
}

echo $OUTPUT->header();


if (!$content) {
    if ($err = mod_wikisnippet\snippet::$lasterror) {
        $errmsg = get_string('contentgeterror', 'wikisnippet', $err);
        echo $OUTPUT->notification($errmsg, 'error');
    } else {
        echo $OUTPUT->notification(get_string('nowikicontenterror', 'wikisnippet'), 'error');
    }
} else {
    echo \html_writer::tag('div', $content, ['class' => 'mx-5']);
}

echo $OUTPUT->footer();
