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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Form for adding and editing wiki snippet instances
 *
 * @package    mod_wikisnippet
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_wikisnippet_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // General fieldset.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        $mform->setType('name', empty($CFG->formatstringstriptags) ? PARAM_CLEANHTML : PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        if (!empty($this->_features->introeditor)) {
            // Description element that is usually added to the General fieldset.
            $this->standard_intro_elements();
        }
        $mform->addElement('header', 'specific', get_string('ws_formsection', 'wikisnippet'));

        $mform->addElement('text', 'wikiurl', get_string('wikis_url', 'wikisnippet'), ['size' => '80']);
        // $mform->setDefault('wikiurl', $this->_customdata['wikiurl']);
        $mform->setType('wikiurl', PARAM_URL);
        $mform->addRule('wikiurl', null, 'required', null, 'client');
        $mform->addElement('static', 'label1', '', get_string('wikis_url_help', 'wikisnippet'));

        $mform->addElement('checkbox', 'noimages', get_string('wikis_excludeImages', 'wikisnippet'));
        // $mform->setDefault('noimages', $this->_customdata['noimages']);
        $mform->setType('noimages', PARAM_INT);
        $mform->addElement('static', 'label2', '', get_string('wikis_excludeImages_help', 'wikisnippet'));

        $mform->addElement('checkbox', 'nolinks', get_string('wikis_excludeLinks', 'wikisnippet'));
        // $mform->setDefault('nolinks', $this->_customdata['nolinks']);
        $mform->setType('nolinks', PARAM_INT);
        $mform->addElement('static', 'label3', '', get_string('wikis_excludeLinks_help', 'wikisnippet'));

        // Other standard elements that are displayed in their own fieldsets.
        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}
