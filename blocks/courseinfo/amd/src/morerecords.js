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
 * TODO describe module morerecords
 *
 * @module     block_courseinfo/morerecords
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import $ from 'jquery';

var trs = $("#courseinfotable tbody tr");
var trs2 = $("#courseinfotable2 tbody tr");

var btnMore = $("#seeMoreRecords");
var btnMore2 = $("#seeMoreRecords2");
var btnLess = $("#seeLessRecords");
var btnLess2 = $("#seeLessRecords2");
var trsLength = trs.length;
var trsLength2 = trs2.length;
// alert(trsLength);
// alert(trsLength2);
var currentIndex = 1;
var currentIndex2 = 1;

trs.hide();
trs.slice(0, 1).show();
checkButton();
trs2.hide();
trs2.slice(0, 1).show();
checkButton2();

btnMore.on('click', function(e) {
    e.preventDefault();
    $("#courseinfotable tbody tr").slice(currentIndex, currentIndex + 1).show();
    currentIndex += 1;
    checkButton();
});
btnMore2.on('click', function(e) {
    e.preventDefault();
    $("#courseinfotable2 tbody tr").slice(currentIndex2, currentIndex2 + 1).show();
    currentIndex2 += 1;
    checkButton2();
});

btnLess.on('click', function(e) {
    e.preventDefault();
    $("#courseinfotable tbody tr").slice(currentIndex - 1, currentIndex).hide();
    currentIndex -= 1;
    checkButton();
});
btnLess2.on('click', function(e) {
    e.preventDefault();
    $("#courseinfotable2 tbody tr").slice(currentIndex2 - 1, currentIndex2).hide();
    currentIndex2 -= 1;
    checkButton2();
});

/**
 * Checking which buttons to show according to tr length.
 */
function checkButton() {
    var currentLength = $("#courseinfotable tbody tr:visible").length;

    if (currentLength >= trsLength) {
        btnMore.hide();
    } else {
        btnMore.show();
    }

    if (trsLength > 1 && currentLength > 1) {
        btnLess.show();
    } else {
        btnLess.hide();
    }

}
/**
 * Checking which buttons to show according to tr length.
 */
function checkButton2() {
    var currentLength = $("#courseinfotable2 tbody tr:visible").length;

    if (currentLength >= trsLength2) {
        btnMore2.hide();
    } else {
        btnMore2.show();
    }

    if (trsLength2 > 1 && currentLength > 1) {
        btnLess2.show();
    } else {
        btnLess2.hide();
    }

}