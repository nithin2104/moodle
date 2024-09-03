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
 *
 * @package    local_notes
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_notes\form;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
/**
 * Summary of notes_form
 */
class notes_form extends \moodleform {
    /**
     * Summary of definition
     * @return void
     */
    protected function definition() {

        global $CFG;
        $context = \context_system::instance();
        $mform = $this->_form;

        $textfieldoptions = [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => 10,
            'maxbytes' => 10485760,
            'context' => $context,
        ];
        $mform->addElement('editor', 'usernotes_editor', get_string('notes', 'local_notes'), ['rows' => 5], $textfieldoptions);
        $mform->setType('usernotes_editor', PARAM_TEXT);
        $mform->addRule('usernotes_editor', get_string('required'), 'required', null, 'client');

        $mform->addElement('submit', 'submit', get_string('submit'));

    }

    /**
     * Summary of validation
     * @param mixed $data
     * @param mixed $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Check if the editor field is empty.
        if (empty(trim($data['usernotes_editor']['text']))) {
            $errors['usernotes_editor'] = get_string('required');
        }

        return $errors;
    }
}
