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
 * Block block_courseinfo
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/blocks}
 *
 * @package    block_courseinfo
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_courseinfo extends block_base {

    /**
     * Block initialisation
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_courseinfo');
    }

    /**
     * Get content
     *
     * @return stdClass
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->page->requires->js_call_amd('block_courseinfo/morerecords');
        $renderable = new block_courseinfo\output\main();
        $renderer = $this->page->get_renderer('block_courseinfo');
        $this->content = new stdClass;
        $this->content->text = $renderer->render($renderable);
        $this->content->footer = '';
        return $this->content;
    }

    /**
     * Summary of instance_allow_multiple
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Summary of specialization
     * @return void
     */
    public function specialization() {
        if (isset($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('pluginname', 'block_courseinfo');
        }
    }

    /**
     * Summary of has_config
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Summary of applicable_formats
     * @return bool[]
     */
    public function applicable_formats() {
        return [ 'all' => true ];
    }
}
