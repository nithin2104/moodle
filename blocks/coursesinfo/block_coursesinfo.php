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
 * Block definition class for the block_pluginname plugin.
 *
 * @package   block_coursesinfo
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Summary of block_coursesinfo
 */
class block_coursesinfo extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_coursesinfo');
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    public function get_content() {
        if (isset($this->content)) {
            return $this->content;
        }
        $this->page->requires->js_call_amd("block_coursesinfo/allrecords", "init");
        $this->page->requires->js_call_amd("block_coursesinfo/alldetails", "init");
        $renderable = new block_coursesinfo\output\main();
        $renderer = $this->page->get_renderer('block_coursesinfo');

        $this->content = new stdClass();
        $this->content->text = $renderer->render($renderable);
        return $this->content;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'all' => true,
        ];
    }
    /**
     * Summary of has_config
     * @return bool
     */
    public function has_config() {
        return true;
    }
}
