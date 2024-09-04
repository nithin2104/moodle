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
 * Callback implementations for wiki snippets
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/mod}
 *
 * @package    mod_wikisnippet
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Summary of wikisnippet_supports
 * @param mixed $feature
 * @return bool|int|null
 */
function wikisnippet_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO: {
            return true;
        }
        case FEATURE_MOD_ARCHETYPE: {
            return MOD_ARCHETYPE_RESOURCE;
        }
        default: {
            return null;
        }
    }
}

/**
 * Summary of wikisnippet_add_instance
 * @param stdClass $wikisnippet
 * @param mod_wikisnippet_mod_form| $mform
 * @return bool|int
 */
function wikisnippet_add_instance(stdClass $wikisnippet, mod_wikisnippet_mod_form $mform) {
    global $DB;
    $thistime = time();
    $wikisnippet->timecreated = $thistime;
    $wikisnippet->modified = $thistime;
    if (!isset($wikisnippet->nolinks)) {
        $wikisnippet->nolinks = 0;
    }
    if (!isset($wikisnippet->noimages)) {
        $wikisnippet->noimages = 0;
    }

    if ($snippet = mod_wikisnippet\snippet::get($wikisnippet->wikiurl, $wikisnippet->nolinks, $wikisnippet->noimages)) {
        $id = $DB->insert_record('wikisnippet', $wikisnippet);
        $cache = cache::make('mod_wikisnippet', 'wikidata');
        $cache->set($id, ['time' => $thistime, 'content' => $snippet]);
        return $id;
    } else {
        $error = mod_wikisnippet\snippet::$lasterror;
        if ($error) {
            throw new moodle_exception('contentgeterror', 'wikisnippet', null, $error);

        } else {
            throw new moodle_exception('nowikicontenterror', 'wikisnippet');
        }
    }

}

/**
 * Summary of wikisnippet_update_instance
 * @param stdClass $wikisnippet
 * @param mod_wikisnippet_mod_form| $mform
 * @return bool
 */
function wikisnippet_update_instance(stdClass $wikisnippet, mod_wikisnippet_mod_form $mform) {
    global $DB;

    $wikisnippet->timemodified = time();
    $wikisnippet->id = $wikisnippet->instance;
    if (!isset($wikisnippet->nolinks)) {
        $wikisnippet->nolinks = 0;
    }
    if (!isset($wikisnippet->noimages)) {
        $wikisnippet->noimages = 0;
    }
    if ($record = $DB->get_record('wikisnippet', ['id' => $wikisnippet->id])) {
        $update = false;
        if ($wikisnippet->wikiurl == $record->wikiurl) {
            if ($wikisnippet->nolinks == $record->nolinks) {
                if ($wikisnippet->noimages == $record->noimages) {
                    $update = true;
                }
            } else {
                $update = true;
            }
        } else {
            $update = true;
        }

        $cache = cache::make('mod_wikisnippet', 'wikidata');
        if (!$update) {
            $wid = $wikisnippet->id;
            if ($cached = $cache->get($wid)) {
                $ttl = mod_wikisnippet\snippet::$cachettl;
                if ((time() - $cached['time']) > $ttl) {
                    $update = true;
                }
            } else {
                $update = true;
            }
        }

        if ($update) {
            if ($snippet = mod_wikisnippet\snippet::get($wikisnippet->wikiurl, $wikisnippet->nolinks, $wikisnippet->noimages)) {
                $cache->set($wikisnippet->id, ['time' => time(), 'content' => $snippet]);
            }
        }
    } else {
        return false;
    }

    return $DB->update_record('wikisnippet', $wikisnippet);
}

/**
 * Summary of wikisnippet_delete_instance
 * @param mixed $id
 * @return bool
 */
function wikisnippet_delete_instance($id) {
    global $DB;

    $cache = cache::make('mod-wikisnippet', 'wikidata');
    if ($cache->get($id)) {
        $cache->delete($id);
    }

    return $DB->delete_records('wikisnippet', ['id' => $id]);

}
