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

namespace local_coursebank\output;
use local_coursebank\manager as manager;
use core_contentbank\contentbank as cb;
use renderable;
use renderer_base;
use stdClass;
use templatable;
/**
 * Class viewcontent
 *
 * @package    local_coursebank
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class viewcontent implements renderable, templatable {

    /**
     * Summary of cid
     * @var $contextid
     */
    public $contextid;

    /**
     * Summary of __construct
     * @param mixed $contextid
     */
    public function __construct($contextid) {
        $this->contextid = $contextid;
    }

    /**
     * Summary of export_for_template
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $SITE;
        $data = new stdClass();
        $context = \context::instance_by_id($this->contextid);

        // Get the toolbar ready.
        $toolbar = [];

        // Place the Add button in the toolbar.
        if (has_capability('moodle/contentbank:useeditor', $context)) {
            // Get the content types for which the user can use an editor.
            $editabletypes['\contenttype_h5p\contenttype'] = 'h5p';
            if (!empty($editabletypes)) {
                // Editor base URL.
                $editbaseurl = new \moodle_url('/local/coursebank/edit.php', ['contextid' => $context->id]);
                $toolbar[] = [
                    'name' => get_string('add'),
                    'link' => $editbaseurl, 'dropdown' => true,
                    'contenttypes' => $editabletypes,
                    'action' => 'add',
                ];
            }
        }
        // The tools are displayed in the action bar on the index page.
        foreach ($toolbar as $tool) {
            // Customize the output of a tool, like dropdowns.
            $method = 'export_tool_'.$tool['action'];
            if (method_exists($this, $method)) {
                $this->$method($tool);
            }
            $data->tools[] = $tool;
        }
        list($allowedcategories, $allowedcourses) = (new cb)->get_contexts_with_capabilities_by_user('local/coursebank:access');
        $allowedcontexts = [];
        $systemcontext = \context_system::instance();
        if (has_capability('local/coursebank:access', $systemcontext)) {
            $allowedcontexts[$systemcontext->id] = get_string('coresystem');
        }
        $options = [];
        foreach ($allowedcategories as $allowedcategory) {
            $options[$allowedcategory->ctxid] = format_string($allowedcategory->name, true, [
            'context' => \context_coursecat::instance($allowedcategory->ctxinstance),
            ]);
        }
        if (!empty($options)) {
            $allowedcontexts['categories'] = [get_string('coursecategories') => $options];
        }
        $options = [];
        foreach ($allowedcourses as $allowedcourse) {
            // Don't add the frontpage course to the list.
            if ($allowedcourse->id != $SITE->id) {
                $options[$allowedcourse->ctxid] = $allowedcourse->shortname;
            }
        }
        if (!empty($options)) {
            $allowedcontexts['courses'] = [get_string('courses') => $options];
        }
        if (!empty($allowedcontexts)) {
            $strchoosecontext = get_string('choosecontext', 'local_coursebank');
            $singleselect = new \single_select(
                    new \moodle_url('/local/coursebank/index.php'),
                    'contextid',
                $allowedcontexts,
                $this->contextid,
                ['' => $strchoosecontext]
            );
        }
        $singleselect->set_label($strchoosecontext, ['class' => 'sr-only']);
        $data->allowedcontexts = $singleselect->export_for_template($output);

        $managerobj = new manager($this->contextid);
        $data->coursebank = array_values($managerobj->get_coursebank_files());
        return $data;
    }

    /**
     * Summary of export_tool_add
     * @param array $tool
     * @return void
     */
    private function export_tool_add(array &$tool) {
        $editabletypes = $tool['contenttypes'];
        $context = \context::instance_by_id($this->contextid);
        $addoptions = [];
        foreach ($editabletypes as $class => $type) {
            $contentype = new $class($context);
            // Get the creation options of each content type.
            $types = $contentype->get_contenttype_types();
            if ($types) {
                // Add a text describing the content type as first option. This will be displayed in the drop down to
                // separate the options for the different content types.
                $contentdesc = new stdClass();
                $contentdesc->typename = get_string('description', $contentype->get_contenttype_name());
                array_unshift($types, $contentdesc);
                // Context data for the template.
                $addcontenttype = new stdClass();
                // Content type name.
                $addcontenttype->name = $type;
                // Content type editor base URL.
                $tool['link']->param('plugin', $type);
                $addcontenttype->baseurl = $tool['link']->out();
                // Different types of the content type.
                $addcontenttype->types = $types;
                $addoptions[] = $addcontenttype;
            }
        }
        $tool['contenttypes'] = $addoptions;
    }
}
