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
 * TODO describe module search
 *
 * @module     local_coursebank/search
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


export const init = () => {
    const search = document.querySelector('[data-action="search"]');
    const filearea = document.querySelector('[data-region="filearea"]');
    const noContentMessage = document.createElement('p');
    noContentMessage.id = 'noContentText';
    noContentMessage.textContent = '';
    noContentMessage.classList.add('d-none', 'text-muted', 'mt-2');
    filearea.appendChild(noContentMessage); // Append to filearea
    search.addEventListener('keyup', function() {
        let havingfiles = false;
        const searchitem = this.value.toLowerCase(); // Convert searchitem to lowercase for case-insensitive comparison
        const fileitems = document.querySelectorAll('.fileitem'); // Select all divs with the class 'fileitem'

        fileitems.forEach(fileitem => {
            const link = fileitem.querySelector('a');
            const dataName = link.getAttribute('data-name').toLowerCase();
            if (dataName.includes(searchitem)) {
                fileitem.classList.remove('d-none');
                havingfiles = true;
            } else {
                fileitem.classList.add('d-none');
            }
        });
        if (havingfiles) {
            noContentMessage.classList.add('d-none');
        } else {
            noContentMessage.textContent = `No items found for "${searchitem}"...`; // Update <p> content
            noContentMessage.classList.remove('d-none');

        }
    });

};