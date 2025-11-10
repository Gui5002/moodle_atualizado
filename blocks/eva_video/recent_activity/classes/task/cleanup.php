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
 * Task for updating RSS feeds for rss client block
 *
 * @package   block_eva_videos
 * @author    RCN <xangay@gmail.com>
 * @copyright RCN 2024
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_eva_video\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Task for updating RSS feeds for rss client block
 *
 * @package   block_eva_videos
 * @author    RCN <xangay@gmail.com>
 * @copyright RCN 2024
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cleanup extends \core\task\scheduled_task {

    /**
     * Name for this task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('cleanuptask', 'block_eva_videos');
    }

    /**
     * Remove old entries from table block_eva_videos
     */
    public function execute() {
        global $CFG, $DB;
        require_once("{$CFG->dirroot}/course/lib.php");

        // Those entries will never be displayed as RECENT anyway.
        $DB->delete_records_select('block_eva_video', 'timecreated < ?', [
                time() - COURSE_MAX_RECENT_PERIOD,
            ]);
    }
}
