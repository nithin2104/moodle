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
 * Folder module version information
 *
 * @package   local_message
 * @copyright 2009 Petr Skoda  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_message\form;
defined('MOODLE_INTERNAL') || die;
require_once("$CFG->libdir/formslib.php");
/**
 * Summary of record
 */
class record extends \moodleform {
    // Add elements to form.
    /**
     * Summary of definition
     * @return void
     */
    public function definition() {
        global $CFG, $DB, $USER;

        $mform = $this->_form; // Don't forget the underscore!
        $id = $this->optional_param('id', 0, PARAM_INT);
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement(
            'filepicker',
            'uploadrecords',
            'uploadrecords',
            null,
            [
                'subdirs' => 0,
                'maxbytes' => $CFG->maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => 50,
                'accepted_types' => ['csv'],
            ]
        );

        $this->add_action_buttons();
    }
}
