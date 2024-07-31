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

 require_once(__DIR__ ."/../../config.php");

 global $DB,$result;

 $PAGE->set_url("/local/message/delete.php");
 $id=optional_param("id",0, PARAM_INT);


 $result = $DB->delete_records('local_message',array('id'=>$id));
 $result = $DB->delete_records('local_message_read',array('messageid'=>$id));
 if($result) {
    redirect($CFG->wwwroot ."/local/message/manage.php",get_string('deletedrecord','local_message') );

 }
