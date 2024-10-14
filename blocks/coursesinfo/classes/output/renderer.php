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
 * Recently accessed courses block renderer
 *
 * @package    block_coursesinfo
 * @copyright  2018 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_coursesinfo\output;


use plugin_renderer_base;

/**
 * Recently accessed courses block renderer
 *
 * @package    block_coursesinfo
 * @copyright  2018 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the main content for the Recently accessed courses block.
     *
     * @param main $main The main renderable
     * @return string HTML string
     */
    public function render_coursesinfo(main $main) {
        return $this->render_from_template('block_coursesinfo/main', $main->export_for_template($this));
    }
    /**
     * Summary of render_mytemplate
     * @param \block_coursesinfo\output\main $data
     * @return bool|string
     */
        /**
         * Summary of render_mytemplate
         * @param \block_coursesinfo\output\main $renderable
         * @return bool|string
         */
    public function render_mytemplate(main $renderable) {
        return $this->output->render_from_template('block_coursesinfo/table', $renderable->export_for_template($this));
    }

}
