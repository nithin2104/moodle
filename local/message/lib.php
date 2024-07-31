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
 * Folder module version information
 *
 * @package   local_message
 * @copyright 2009 Petr Skoda  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function local_message_before_footer(){
       global $DB,$USER;

       $sql="select lm.id, lm.messagetext, lm.messagetype from {local_message} lm
       left outer join {local_message_read} lmr on lm.id=lmr.messageid
       where lmr.userid <> :userid or lmr.userid is null";
       $PARAM=[
              "userid" => $USER->id
       ];
       $messages = $DB->get_records_sql($sql, $PARAM);

       // Add a notification of some kind.
       foreach($messages as $message){

              $type=\core\output\notification::NOTIFY_INFO;
              if($message->messagetype === '0'){
                     $type=\core\output\notification::NOTIFY_SUCCESS;
              }
              else if($message->messagetype  === '1'){
                     $type=\core\output\notification::NOTIFY_WARNING;
              }
              else if($message->messagetype  === '2'){
                     $type=\core\output\notification::NOTIFY_ERROR;
              }
              else{
                     $type=\core\output\notification::NOTIFY_INFO;
              }
              \core\notification::add($message->messagetext, $type);

              $now = time();
              $readrecords=new stdClass();
              $readrecords->messageid=$message->id;
              $readrecords->userid=$USER->id;
              $readrecords->timeread=userdate($now);
              $DB->insert_record('local_message_read', $readrecords);

       
       }
}