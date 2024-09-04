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

namespace mod_wikisnippet;

/**
 * Class snippet
 *
 * @package    mod_wikisnippet
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class snippet {

    /**
     * Summary of cachettl
     * @var $cachettl
     */
    public static $cachettl = 24 * 60 * 60;

    /**
     * Summary of lasterror
     * @var $lasterror
     */
    public static $lasterror = null;


    /**
     * Summary of get
     * @param mixed $snippeturl
     * @param mixed $nolinks
     * @param mixed $noimages
     * @param mixed $debug
     * @return mixed
     */
    public static function get($snippeturl, $nolinks = true, $noimages = false, $debug = false) {
        require_once('wikisnippet/wikisnippet.inc.php');
        $getter = new \WikiSnippet();
        if ($debug) {
            $getter->setdebugging();
        }
        $content = $getter->getWikiContent($snippeturl, $nolinks, $noimages);
        if ($getter->error) {
            self::$lasterror = $getter->error;
            return null;
        }
        return $content;
    }
}
