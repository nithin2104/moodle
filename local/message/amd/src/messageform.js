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
 * TODO describe module messageform
 *
 * @module     local_message/messageform
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import ModalForm from 'core_form/modalform';

import {get_string as getString} from 'core/str';

const Selectors = {
    actions: {
        update: '[data-action="updatemodal"]',
    },
};

export const init = () => {
    // In this example we will open the modal dialogue with the form when user clicks on the edit link:
    document.addEventListener('click', e => {
        let update = e.target.closest(Selectors.actions.update);
        if (update) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const modalForm = new ModalForm({
                // Name of the class where form is defined (must extend \core_form\dynamic_form):
                formClass: "local_message\\form\\message_form",
                // Add as many arguments as you need, they will be passed to the form:
                // Pass any configuration settings to the modal dialogue, for example, the title:
                modalConfig: {title: getString('addtitle', 'local_message')},
                // DOM element that should get the focus after the modal dialogue is closed:
                returnFocus: update,
            });
            // Listen to events if you want to execute something on form submit. Event detail will contain everything the process().
            modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, () => window.location.reload());
            modalForm.show();
        }
    });
};

