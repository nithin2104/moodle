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
 * TODO describe module alldetails
 *
 * @module     block_coursesinfo/alldetails
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';

import { get_string as getString } from 'core/str';
import * as Str from 'core/str';

const Selectors = {
    actions: {

        allteachers: '[data-action="allteachers"]',
        allstudents: '[data-action="allstudents"]',
    },
};
export const init = () => {
    document.addEventListener('click', function (e) {

        let allteachers = e.target.closest(Selectors.actions.allteachers);
        if (allteachers) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const Teachers = allteachers.getAttribute("data-id");
            // eslint-disable-next-line promise/catch-or-return, promise/always-return
            Str.get_string('details', 'block_coursesinfo', Teachers).then(function () {
                ModalFactory.create({
                    title: getString("allteachers", "block_coursesinfo"),
                    body: Teachers,
                }).done(
                    function (modal) {
                        // Keep a reference to the modal.
                        self.modal = modal;
                        // We want to reset the form every time it is opened.
                        self.modal.getRoot().on(ModalEvents.hidden, function () {
                            // Self.modal.setBody('');
                            self.modal.hide();
                            self.modal.destroy();
                        }.bind(this));
                        // We want to reset the form every time it is opened.
                        self.modal.getRoot().on(ModalEvents.cancel, function () {
                            // Self.modal.setBody('');
                            self.modal.hide();
                            self.modal.destroy();
                        }.bind(this));
                        self.modal.show();
                    }
                );
            });
        }

        let allstudents = e.target.closest(Selectors.actions.allstudents);
        if (allstudents) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const Students = allstudents.getAttribute("data-id");

            // eslint-disable-next-line promise/catch-or-return, promise/always-return
            Str.get_string('details', 'block_coursesinfo', Students).then(function () {
                ModalFactory.create({
                    title: getString("allstudents", "block_coursesinfo"),
                    body: Students,
                }).done(
                    function (modal) {
                        // Keep a reference to the modal.
                        self.modal = modal;
                        // We want to reset the form every time it is opened.
                        self.modal.getRoot().on(ModalEvents.hidden, function () {
                            // Self.modal.setBody('');
                            self.modal.hide();
                            self.modal.destroy();
                        }.bind(this));
                        // We want to reset the form every time it is opened.
                        self.modal.getRoot().on(ModalEvents.cancel, function () {
                            // Self.modal.setBody('');
                            self.modal.hide();
                            self.modal.destroy();
                        }.bind(this));
                        self.modal.show();
                    }
                );
            });
        }

    });
};