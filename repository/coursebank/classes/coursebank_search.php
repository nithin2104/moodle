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
 * Utility class for searching of content bank files.
 *
 * @package    repository_coursebank
 * @copyright  2020 Mihail Geshoski <mihail@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace repository_coursebank;

/**
 * Represents the content bank search related functionality.
 *
 * @package    repository_coursebank
 * @copyright  2020 Mihail Geshoski <mihail@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coursebank_search {

    /**
     * Generate and return content nodes for all content bank files that match the search criteria
     * and can be viewed/accessed by the user.
     *
     * @param string $search The search string
     * @return array[] The array containing all content file nodes that match the search criteria. Each content node is
     *                 an array with keys: shorttitle, title, datemodified, datecreated, author, license, isref, source,
     *                 icon, thumbnail.
     */
    public static function get_search_contents(string $search): array {
        global $DB;

        if (!empty($search)) {
            $sql = "SELECT c.* FROM {local_coursebank} c WHERE c.name like '%$search%' ORDER BY c.name ASC";
        }
        $contents = $DB->get_records_sql($sql);
        return array_reduce($contents, function($list, $content) {
            $contentcontext = \context::instance_by_id($content->contextid);
            $browser = \repository_coursebank\helper::get_coursebank_browser($contentcontext);
            // If the user can access the content and content node can be created, add the node into the
            // search results list.
            if ($browser->can_access_content() &&
                    $contentnode = \repository_coursebank\helper::create_coursebank_content_node($content)) {
                $list[] = $contentnode;
            }
            return $list;
        }, []);
    }
}
