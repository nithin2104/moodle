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

namespace contenttype_mp4;
use core\event\contentbank_content_viewed;

/**
 * Class contenttype
 *
 * @package    contenttype_mp4
 * @copyright  2024 Nithin kumar nithin54k@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class contenttype extends \core_contentbank\contenttype {

    /**
     * Return an array of implemented features by this plugin.
     *
     * @return array
     */
    protected function get_implemented_features(): array {
        return [self::CAN_UPLOAD, self::CAN_DOWNLOAD];
    }

    /**
     * Return an array of extensions this contenttype could manage.
     *
     * @return array
     */
    public function get_manageable_extensions(): array {
        return ['.mp4'];
    }

    /**
     * Returns the list of different types of the given content type.
     *
     * @return array
     */
    public function get_contenttype_types(): array {
        return [];
    }
    /**
     * Returns user has access capability for the content itself.
     *
     * @return bool     True if content could be accessed. False otherwise.
     */
    protected function is_access_allowed(): bool {
        return true;
    }
    /**
     * Summary of get_icon
     * @param \core_contentbank\content $content
     * @return string
     */
    public function get_icon(\core_contentbank\content  $content): string {
        global $OUTPUT;
        return $OUTPUT->image_url('mp4', 'contenttype_mp4')->out(false);
    }
    /**
     * Summary of get_view_content
     * @param \core_contentbank\content $content
     * @return string
     */
    public function get_view_content(\core_contentbank\content $content): string {
        // Trigger an event for viewing this content.
        $event = contentbank_content_viewed::create_from_record($content->get_content());
        $event->trigger();

        $fileurl = $content->get_file_url();
        $html = "<div class='text-center'>
                    <video class='img-fluid' controls>
                        <source src='$fileurl' type='video/mp4'>
                        Your browser does not support the video tag.
                    </video>
                </div>";
        return $html;
    }

}
