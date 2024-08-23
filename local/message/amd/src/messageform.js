/* eslint-disable promise/always-return */
/* eslint-disable promise/catch-or-return */
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
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import Fragment from 'core/fragment';
import Ajax from 'core/ajax';
import {get_string as getString} from 'core/str';
import * as Str from 'core/str';

const Selectors = {
    actions: {
        updatemodal: '[data-action="updatemodal"]',
        viewmodal: '[data-action="viewmodal"]',
        deletemodal: '[data-action="deletemodal"]',
        uploadrecords: '[data-action="uploadrecords"]',
    },
};

export const init = () => {
    // In this example we will open the modal dialogue with the form when user clicks on the edit link:
    document.addEventListener('click', e => {
        let updatemodal = e.target.closest(Selectors.actions.updatemodal);
        if (updatemodal) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const title = updatemodal.getAttribute('data-id') ?
                getString('edittitle', 'local_message', updatemodal.getAttribute('data-name')) :
                getString('addtitle', 'local_message', updatemodal.getAttribute('data-name'));
            const modalForm = new ModalForm({
                // Name of the class where form is defined (must extend \core_form\dynamic_form):
                formClass: "local_message\\form\\message_form",
                // Add as many arguments as you need, they will be passed to the form:
                args: {id: updatemodal.getAttribute('data-id')},
                // Pass any configuration settings to the modal dialogue, for example, the title:
                modalConfig: {title},
                // DOM element that should get the focus after the modal dialogue is closed:
                returnFocus: updatemodal,
            });
            // Listen to events if you want to execute something on form submit. Event detail will contain everything the process().
            modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, () => window.location.reload());
            modalForm.show();
        }
        let viewmodal = e.target.closest(Selectors.actions.viewmodal);
        if (viewmodal) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const id = viewmodal.getAttribute("data-id");
            const args = {id: id};
            Str.get_string('viewdetails', 'local_message', args).then(function() {
                ModalFactory.create({
                    title: getString('viewdetails', 'local_message'),
                    body: detailpage(args),
                }).done(
                    function(modal) {
                        self.modal = modal;
                        self.modal.setLarge();
                        self.modal.getRoot().on(ModalEvents.hidden, function() {
                            self.modal.hide();
                            self.modal.destroy();
                        });
                        self.modal.getRoot().on(ModalEvents.cancel, function() {
                            self.modal.hide();
                            self.destroy();
                        });
                        self.modal.show();
                    }
                );
            });
        }
        const detailpage = function(args) {
            return Fragment.loadFragment('local_message', 'message', 1, args);
        };
        let deletemodal = e.target.closest(Selectors.actions.deletemodal);
        if (deletemodal) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const id = deletemodal.getAttribute('data-id');
            const firstname = deletemodal.getAttribute('data-name');
            const a = {

                'type': 'record',
                'name': firstname,
            };
            ModalFactory.create({
                title: getString('deletecheck', 'moodle', 'Record'),
                type: ModalFactory.types.SAVE_CANCEL,
                body: getString('deletechecktypename', 'moodle', a),
            }).done(
                function(modal) {
                    this.modal = modal;
                    modal.setSaveButtonText(getString('delete'));
                    modal.getRoot().on(
                        ModalEvents.save,
                        function(e) {
                            e.preventDefault();
                            var params = {};
                            params.confirm = true;
                            params.id = id;
                            var promise = Ajax.call([{
                                methodname: "local_message_delete_record",
                                args: params,
                            }]);
                            promise[0]
                                .done(function() {
                                    window.location.reload(true);
                                })
                                .fail(function() {
                                    // Failed.
                                });
                        }
                    );
                    modal.show();
                }
            );
        }
        let uploadrecords = e.target.closest(Selectors.actions.uploadrecords);
        if (uploadrecords) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const modal = new ModalForm({
                formClass: "local_message\\form\\uploadrecords_form",
                modalConfig: {title: getString('uploadafile')},
                returnFocus: uploadrecords,
            });
            modal.addEventListener(modal.events.FORM_SUBMITTED, () => window.location.reload());
            modal.show();
        }
    });

};

