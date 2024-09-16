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

/**
 * Class send_notification
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_message\task;
defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot.'/local/message/lib.php');

/**
 * Summary of send_message_notification
 */
class send_message_notification extends \core\task\adhoc_task {

    /**
     * Summary of execute
     * @return void
     */
    public function execute() {
        global $USER;
        mtrace("Ad-hoc task started.");
        // Your task logic here.
        $delay = time() + 60;
        while (time() < $delay) {
            sleep(1);
        }

        email_to_user(get_admin(), $this->get_custom_data()->user, 'Message App', $this->get_custom_data()->messagebody);

        mtrace(fullname($this->get_custom_data()->user)." submitted a message into local_message DB with user id ".
        $this->get_custom_data()->user->id . " Admin will receive a email notification about this action");
        mtrace("Ad-hoc task completed.");

    }
}
