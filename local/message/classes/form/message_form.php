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
 * Goals hierarchy
 *
 * This file defines the current version of the local_message Moodle code being used.
 * This is compared against the values stored in the database to determine
 * whether upgrades should be performed (see lib/db/*.php)
 *
 * @package    local_message
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message\form;

use core_form\dynamic_form;
use moodle_url;
use context;
use context_system;
use local_message\manager as manager;

/**
 * Custom goal class.
 */
class message_form extends dynamic_form {

    /**
     * Form defination
     */
    public function definition() {
        global $CFG, $DB, $USER;

        $context = context_system::instance();

        $mform = $this->_form; // Don't forget the underscore!
        $id = $this->optional_param('id', 0, PARAM_INT);
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);
        $editoroptions = $this->_customdata['editoroptions'];

        $mform->addElement('filemanager', 'profile', "Profile", null,
                   ['subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                   'accepted_types' => ['jpg', 'jpeg', 'png'], ]);

        $mform->addElement('text', 'firstname', "First Name ");
        $mform->addRule('firstname', "Required", 'required', null);
        $mform->setType('text', PARAM_TEXT);

        $mform->addElement('text', 'lastname', "Last Name");
        $mform->addRule('lastname', "Required", 'required', null);
        $mform->setType('text', PARAM_TEXT);

        $textfieldoptions = [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => $maxfiles,
            'maxbytes' => $maxbytes,
            'context' => $context,
        ];
        $mform->addElement(
            'editor',
            'description_editor',
            'Description',
            null,
            $textfieldoptions
        );
        $mform->setType('description_editor', PARAM_TEXT);

        $mform->addElement('hidden', 'status');
        $mform->setType('int', PARAM_INT);
        // Set type of element.
    }

    /**
     * Perform some moodle validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        return $errors;
    }
    /**
     * Returns context where this form is used
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {
        return context_system::instance();
    }

    /**
     * Checks if current user has access to this form, otherwise throws exception
     */
    protected function check_access_for_dynamic_submission(): void {
        require_capability('local/greetings:viewmessages', $this->get_context_for_dynamic_submission());
    }

    /**
     * Process dynamic submission
     */
    public function process_dynamic_submission() {
        global $CFG, $DB, $USER;

        $data = $this->get_data();

        if ($data) {
            (new manager)->create_update($data);
        }
    }

    /**
     * Set form data for dynamic submission.
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB, $CFG;
        $context = context_system::instance();
        $id = $this->optional_param('id', 0, PARAM_INT);
        $data = (new manager)->get_message_records($id);
        if (!empty($data)) {
                $textfieldoptions = ['trusttext' => true, 'subdirs' => true, 'maxfiles' => -1, 'maxbytes' => $CFG->maxbytes,
                'context' => $context, ];
                $data = file_prepare_standard_editor(
                    // The existing data.
                    $data,

                    // The field name in the database.
                    'description',

                    // The options.
                    $textfieldoptions,

                    // The combination of contextid, component, filearea, and itemid.
                    context_system::instance(),
                    'local_message',
                    'description',
                    $data->id
                );

            $this->set_data($data);
        }
    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {

        return new moodle_url(
            '/local/message/manage.php',
        );
    }
}
