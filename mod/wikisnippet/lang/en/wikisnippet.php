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
 * English language pack for mod_wikisnippets
 *
 * @package    mod_wikisnippet
 * @category   string
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'wiki snippet';
$string['modulename'] = 'wiki snippet';
$string['modulenameplural'] = 'wiki snippets';
$string['pluginadministration'] = 'mod_wikisnippet administration';
$string['modulename_help'] = 'The wikisnippet module allows course managers to include fragments of wiki pages';
$string['wikisnippet'] = 'wikisnippet';
$string['nowikisnippets'] = 'No wiki snippets found';

$string['ws_proxyprompt'] = 'Enter the proxy address';
$string['ws_proxyhelp'] = 'In format ipaddress:port ot host:port e.g. proxy.mydomain.com:8080';
$string['ws_debugon'] = 'Turn debug reporting on';
$string['ws_debugon_help'] = 'Not recommended on live servers - writes debugging information to the server\'s logs';
$string['ws_cachetime'] = 'Enter time in hours that a snippet must be cached for';
$string['ws_cachetime_help'] = 'Time to live for caching items - setting 0 will turn off caching - not recommended';

$string['wikisnippet:addinstance'] = 'Add wiki snippet instance to course';
$string['wikisnippet:view'] = 'View wiki snippet instance in course';

$string['cachedef_wikidata'] = 'Cache data for wiki snippet.';

$string['contentgeterror'] = 'Error getting content : "{$a}"';
$string['nowikicontenterror'] = 'Error : There is no content to display.';

$string['wikis_Name'] = 'Name';
$string['wikis_Desc'] = 'Description';

$string['ws_formsection'] = 'wiki Snippet Settings';
$string['wikis_url'] = 'wiki URL';
$string['wikis_url_help'] = 'including fragment #anchor <br/> enter #toc for Table of Contents, #preamble for the introductory text and #infobox for the information table on the wiki page';
$string['wikis_excludeImages'] = 'No Images?';
$string['wikis_excludeImages_help'] = 'Select so that images on the wiki page are stripped out';
$string['wikis_excludeLinks'] = 'No Links?';
$string['wikis_excludeLinks_help'] = 'Select so that any internal links on the wiki page are stripped out - leaving the text';
$string['wikis_preview_title'] = 'Snippet Preview';
$string['wikis_submit'] = 'Grab Snippet';
$string['wikis_error'] = 'Errors';
