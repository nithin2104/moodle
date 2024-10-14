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
 * TODO describe module allrecords
 *
 * @module     block_coursesinfo/allrecords
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

export const init = () =>{
    var trs = $("#courseinfotable tbody tr");
    var trs2 = $("#courseinfotable2 tbody tr");
    
    var btnMore = $("#seeMoreRecords");
    var btnMore2 = $("#seeMoreRecords2");
    
    var trsLength = trs.length;
    var trsLength2 = trs2.length;
    
    
    if (trsLength <= 5) {
        btnMore.hide();
        
    } else {
        trs.hide();
        trs.slice(0, 5).show();
        btnMore.show();
    }
    if (trsLength2 <= 5) {
        btnMore2.hide();   
    } else {
        trs2.hide();
        trs2.slice(0, 5).show();
        btnMore2.show();
    }
}