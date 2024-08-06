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
 * Upgrade steps for Messages plugin
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    local_message
 * @category   upgrade
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute the plugin upgrade steps from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_message_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2022041901) {

        // Define table local_message_crud to be created.
        $table = new xmldb_table('local_message_crud');

        // Adding fields to table local_message_crud.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('profile', XMLDB_TYPE_INTEGER, '11', null, null, null, null);
        $table->add_field('firstname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lastname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('desrciption', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_message_crud.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_message_crud.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Message savepoint reached.
        upgrade_plugin_savepoint(true, 2022041901, 'local', 'message');
    }
    if ($oldversion < 2022041904) {

        // Define field userid to be added to local_message_crud.
        $table = new xmldb_table('local_message_crud');
        $field1 = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'desrciption');
        $field2 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'userid');
        $field3 = new xmldb_field('timeupdated', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'timecreated');
        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }

        // Message savepoint reached.
        upgrade_plugin_savepoint(true, 2022041904, 'local', 'message');
    }
    if ($oldversion < 2022041905) {

        // Rename field description on table local_message_crud to description.
        $table = new xmldb_table('local_message_crud');
        $field = new xmldb_field('desrciption', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'lastname');

        // Launch rename field description.
        $dbman->rename_field($table, $field, 'description');

        // Message savepoint reached.
        upgrade_plugin_savepoint(true, 2022041905, 'local', 'message');
    }


    return true;
}
