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
 * TODO describe module my_datatables
 *
 * @module     local_message/my_datatables
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import Ajax from "core/ajax";
import $ from "jquery";
import "local_message/datatables";
import Templates from "core/templates";
import ModalFactory from "core/modal_factory";
import ModalEvents from "core/modal_events";

const selectors = {
  actions: {
    star: '[data-action="star"]',
    mymodal: '[data-action="my_modal"]',
  },
};
export const init = () => {
  $(function() {
    $("#viewmsgtable").DataTable({
      bLengthChange: false,
      ordering: false,
    });
  });
  $(function() {
    $(".mytable").DataTable({
      bLengthChange: false,
      ordering: false,
    });
  });
  document.addEventListener("click", (e) => {
    let starred = e.target.closest(selectors.actions.star);
    if (starred) {
      var isfav = starred.getAttribute("data-favorited") === "true";
      var id = starred.getAttribute("data-id");
      var params = {};
      if (isfav) {
        params.fav = false;
        params.id = id;
      } else {
        params.fav = true;
        params.id = id;
      }
      var promise = Ajax.call([
        {
          methodname: "local_message_favourites",
          args: params,
        },
      ]);
      promise[0]
        .done(function() {
          // Success.
          window.location.reload(true);
        })
        .fail(function() {
          // Failed.
        });
    }
    let mymodal = e.target.closest(selectors.actions.mymodal);
    if (mymodal) {
      const myObject = mymodal.getAttribute("data-object");
      const data = JSON.parse(myObject);
      ModalFactory.create({
        title: "View Messages",
        body: Templates.render("local_message/viewmsg", data),
      }).done(function(modal) {
        modal.setLarge();
        modal.getRoot().on(ModalEvents.hidden, function(e) {
          e.preventDefault();
          window.location.reload(true);
        });
        modal.show();
      });
    }
  });
};
