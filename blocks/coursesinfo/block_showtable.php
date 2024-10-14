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
 * TODO describe file block_showtable
 *
 * @package    block_coursesinfo
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require('../../config.php');

require_login();

$url = new moodle_url('/blocks/coursesinfo/block_showtable.php', []);
$PAGE->requires->js_call_amd("block_coursesinfo/mydatatable");
$PAGE->requires->js_call_amd("block_coursesinfo/alldetails", "init");
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$renderer = $PAGE->get_renderer('block_coursesinfo');
$renderable = new block_coursesinfo\output\main();

echo $OUTPUT->header();

echo $renderer->render_mytemplate($renderable);

echo $OUTPUT->footer();
