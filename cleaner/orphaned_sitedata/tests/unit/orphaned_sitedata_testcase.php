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
 * orphaned_sitedata testcase.
 *
 * @package     cache_cleaner_test
 * @author      Daniel Thee Roperto <daniel.roperto@catalyst-au.net>
 * @copyright   2016 Catalyst IT
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace cleaner_orphaned_sitedata\tests\unit;

use advanced_testcase;
use context_course;
use ReflectionMethod;
use stored_file;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/adminlib.php');

if (file_exists($CFG->libdir.'/filestorage/stored_file.php')) {
    require_once($CFG->libdir.'/filestorage/stored_file.php');
}

/**
 * orphaned_sitedata testcase.
 *
 * @package     cache_cleaner_test
 * @author      Daniel Thee Roperto <daniel.roperto@catalyst-au.net>
 * @copyright   2016 Catalyst IT
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @SuppressWarnings(public) Allow as many methods as needed.
 */
class orphaned_sitedata_testcase extends advanced_testcase {
    protected function execute($cleaner) {
        ob_start();
        $cleaner->execute();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    protected function create_file($component, $filepath, $filename) {
        $syscontext = context_course::instance(1);
        $filerecord = [
            'contextid' => $syscontext->id,
            'component' => $component,
            'filearea'  => 'unittest',
            'itemid'    => 0,
            'filepath'  => $filepath,
            'filename'  => $filename,
        ];
        $fs = get_file_storage();
        return $fs->create_file_from_string($filerecord, 'backup data');
    }

    protected function get_pathname(\stored_file $file) {
        global $CFG;
        $contenthash = $file->get_contenthash();
        if (isset($CFG->filedir)) {
            $filedir = $CFG->filedir;
        } else {
            $filedir = $CFG->dataroot.'/filedir';
        }
        $l1 = $contenthash[0] . $contenthash[1];
        $l2 = $contenthash[2] . $contenthash[3];
        return "$filedir/$l1/$l2/$contenthash";
    }
}
