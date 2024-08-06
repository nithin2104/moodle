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

defined('MOODLE_INTERNAL') || die;
require_once("$CFG->libdir/formslib.php");
/**
 * Summary of edit
 */
class edit extends moodleform {
    // Add elements to form.
    /**
     * Summary of definition
     * @return void
     */
    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        // Add elements to your form.
        $mform->addElement('hidden', 'id', 'yes');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('text', 'messagetext', get_string('messagetext', 'local_message'), ['placeholder' => 'Enter message']);
        $mform->setType('messagetext', PARAM_NOTAGS);

        $choices = [
            $choices['0'] = \core\output\notification::NOTIFY_SUCCESS,
            $choices['1'] = \core\output\notification::NOTIFY_WARNING,
            $choices['2'] = \core\output\notification::NOTIFY_ERROR,
            $choices['3'] = \core\output\notification::NOTIFY_INFO,
        ];

        $mform->addElement('select', 'messagetype', get_string('messagetype', 'local_message'), $choices);
        $mform->setDefault("messagetype", get_string('defaultvalue', 'local_message'));

        $this->add_action_buttons(1, get_string('submit', 'local_message'));
    }

    // Custom validation should be added here.
    /**
     * Summary of validation
     * @param mixed $data
     * @param mixed $files
     * @return array
     */
    public function validation($data, $files) {
        return [];
    }
}