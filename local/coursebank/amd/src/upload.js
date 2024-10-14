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
 * TODO describe module upload
 *
 * @module     local_coursebank/upload
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import ModalForm from 'core_form/modalform';
import {get_string as getString} from 'core/str';

const Selectors = {
    action: {
        upload: '[data-action="upload"]',
    },
};

export const init = (contextid) => {

    document.addEventListener('click', function(e) {
        let upload = e.target.closest(Selectors.action.upload);
        if (upload) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const modal = new ModalForm({
                formClass: "local_coursebank\\form\\coursecontent",
                args: {contextid: contextid},
                modalConfig: {title: getString('uploadafile')},
                returnFocus: upload,
            });
            modal.addEventListener(modal.events.FORM_SUBMITTED, () => window.location.reload());
            modal.show();
        }
    });
};