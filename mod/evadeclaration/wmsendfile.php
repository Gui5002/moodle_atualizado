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
 * Add Watermark and send files
 *
 * @package mod
 * @subpackage evadeclaration
 * @copyright 2024 © Renata Neves
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use setasign\Fpdi\TcpdfFpdi;
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
$code = required_param('code', PARAM_TEXT); // Issued Code.
$issueddecl = $DB->get_record("evadeclaration_issues", array('code' => $code));
if (!$issueddecl) {
    print_error(get_string('issueddeclarationnotfound', 'evadeclaration'));
} else {
    send_declaration_file($issueddecl);
}
function send_declaration_file(stdClass $issueddecl) {
    global $CFG, $USER, $DB, $PAGE;
    if ($issueddecl->haschange) {
        // This issue have a haschange flag, try to reissue.
        if (empty($issueddecl->timedeleted)) {
            require_once($CFG->dirroot . '/mod/evadeclaration/locallib.php');
            try {
                // Try to get cm.
                $cm = get_coursemodule_from_instance('evadeclaration', $issueddecl->declarationid, 0, false, MUST_EXIST);
                $context = context_module::instance($cm->id);
                // Must set a page context to issue .
                $PAGE->set_context($context);
                $evadeclaration = new evadeclaration($context, null, null);
                $file = $evadeclaration->get_issue_file($issueddecl);
            } catch (moodle_exception $e) {
                // Only debug, no errors.
                debugging($e->getMessage(), DEBUG_DEVELOPER, $e->getTrace());
            }
        } else {
            // Have haschange and timedeleted, somehting wrong, it will be impossible to reissue
            // add wraning.
            debugging("issued declaration [$issueddecl->id], have haschange and timedeleted");
        }
        $issueddecl->haschange = 0;
        $DB->update_record('evadeclaration_issues', $issueddecl);
    }
    if (empty($file)) {
        $fs = get_file_storage();
        if (!$fs->file_exists_by_hash($issueddecl->pathnamehash)) {
            print_error(get_string('filenotfound', 'evadeclaration', ''));
        }
        $file = $fs->get_file_by_hash($issueddecl->pathnamehash);
    }
    $canmanage = false;
    $cm = get_coursemodule_from_instance('evadeclaration', $issueddecl->declarationid);
    if ($cm) {
        $canmanage = has_capability('mod/evadeclaration:manage', context_course::instance($cm->course));
    }
    if ($canmanage || (!empty($USER) && $USER->id == $issueddecl->userid)) {
        // If logged in it's owner of this declaration, or has can manage the course
        // will send the declaration without watermark.
        send_stored_file($file, 0, 0, true);
    } else {
        // If no login or it's not declaration owner and don't have manage privileges
        // it will put a 'copy' watermark and send the file.
        $wmfile = put_watermark($file);
        send_temp_file($wmfile, $file->get_filename());
    }
}
/**
 * @param file
 * @param rotangle
 * @param bodersytle
 * @param bodersytle
 */
function put_watermark($file) {
    global $CFG;
    require_once($CFG->libdir.'/pdflib.php');
    require_once($CFG->dirroot.'/mod/assign/feedback/editpdf/fpdi/autoload.php');
    // require_once($CFG->dirroot.'/mod/assign/feedback/editpdf/fpdi/FpdfTpl.php');
    // require_once($CFG->dirroot.'/mod/assign/feedback/editpdf/fpdi/Fpdi.php');
    // Copy to a tmp file.
    $tmpfile = $file->copy_content_to_temp();
    // TCPF doesn't import files yet, so i must use FPDI.
    $pdf = new TcpdfFpdi();
    $pagecount = $pdf->setSourceFile($tmpfile);
    for ($pgnum = 1; $pgnum <= $pagecount; $pgnum++) {
        // Import a page.
        $templateid = $pdf->importPage($pgnum);
        // Get the size of the imported page.
        $size = $pdf->getTemplateSize($templateid);
        // Create a page (landscape or portrait depending on the imported page size).
        if ($size['width'] > $size['height']) {
            $pdf->AddPage('L', array($size['width'], $size['height']));
            // Font size 1/3 Height if it landscape.
            $fontsize = $size['height'] / 3;
        } else {
            $pdf->AddPage('P', array($size['width'], $size['height']));
            // Font size 1/3 Width if it portrait.
            $fontsize = $size['width'] / 3;
        }
        // Use the imported page.
        $pdf->useTemplate($templateid);
        // Calculating the rotation angle.
        $rotangle = (atan($size['height'] / $size['width']) * 180) / pi();
        // Find the middle of the page to use as a pivot at rotation.
        $mdlx = ($size['width'] / 2);
        $mdly = ($size['height'] / 2);
        // Set the transparency of the text to really light.
        $pdf->SetAlpha(0.25);
        $pdf->StartTransform();
        $pdf->Rotate($rotangle, $mdlx, $mdly);
        $pdf->SetFont("freesans", "B", $fontsize);
        $pdf->SetXY(0, $mdly);
        $bodersytle = array('LTRB' => array('width' => 2, 'dash' => $fontsize / 5,
                                    'cap' => 'round',
                                    'join' => 'round',
                                    'phase' => $fontsize / $mdlx)
        );
        $pdf->Cell($size['width'], $fontsize, get_string('declarationcopy', 'evadeclaration'), $bodersytle, 0, 'C', false, '',
                4, true, 'C', 'C');
        $pdf->StopTransform();
        // Reset the transparency to default.
        $pdf->SetAlpha(1);
    }
    // Set protection seems not work, but don't hurt.
    $pdf->SetProtection(array('print', 'modify',
                              'copy', 'annot-forms',
                              'fill-forms', 'extract',
                              'assemble', 'print-high'),
                        null,
                        random_string(5),
                        1,
                        null
    );
    // For DEBUG
    // $pdf->Output($file->get_filename(), 'I');.
    // Save and send tmpfiles.
    $pdf->Output($tmpfile, 'F');
    return $tmpfile;
}
