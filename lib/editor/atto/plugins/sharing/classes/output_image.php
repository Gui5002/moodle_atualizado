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
 * This file contains a class that provides functions for generate a QR code.
 *
 * @package    atto_sharing
 * @copyright  2021 Brain Station 23 Ltd. <brainstation-23.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace atto_sharing;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use DOMDocument;
use Exception;
use stdClass;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/editor/atto/plugins/sharing/thirdparty/vendor/autoload.php');
require_once($CFG->dirroot . '/course/lib.php');

/**
 * Class output_image
 *
 * Output QR code.
 *
 * @package    atto_sharing
 * @copyright  2021 Brain Station 23 Ltd. <brainstation-23.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class output_image
{
    /**
     * QR code is saved in this file.
     * @var string
     */
    protected $file;

    /**
     * Output file type.
     * 0 - png, 1 - svg
     * @var int
     */
    protected $format;

    /**
     * Course for which the sharing is created.
     * @var stdClass
     */
    protected $context;

    /**
     * @param $context
     */
    public function __construct($context)
    {
        $this->format = 2;
        $this->context = $context;
    }

}
