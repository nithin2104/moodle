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
 * Upgrade steps for local_coursebank
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    local_coursebank
 * @category   upgrade
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute the plugin upgrade steps from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_coursebank_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2024100302) {

        // Define key contextid (foreign) to be added to local_coursebank.
        $table = new xmldb_table('local_coursebank');
        $key = new xmldb_key('contextid', XMLDB_KEY_FOREIGN, ['contextid'], 'context', ['id']);

        // Launch add key contextid.
        $dbman->add_key($table, $key);
        $key = new xmldb_key('usercreated', XMLDB_KEY_FOREIGN, ['usercreated'], 'user', ['id']);

        // Launch add key usercreated.
        $dbman->add_key($table, $key);

        // Coursebank savepoint reached.
        upgrade_plugin_savepoint(true, 2024100302, 'local', 'coursebank');
    }

    return true;
}
