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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}


require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once($CFG->libdir . '/filelib.php');


class mod_evadeclaration_mod_form extends moodleform_mod {

    public function definition() {
        global $CFG;

        $mform =& $this->_form;

        // General options.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('declarationname', 'evadeclaration'), array('size' => '64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addHelpButton('name', 'declarationname', 'evadeclaration');

        $this->standard_intro_elements(get_string('intro', 'evadeclaration'));

        // Design Options.
        $mform->addElement('header', 'designoptions', get_string('designoptions', 'evadeclaration'));

        // declaration image file.
        $mform->addElement('filemanager', 'declarationimage',
            get_string('declarationimage', 'evadeclaration'), null,
            $this->get_filemanager_options_array()
        );
        $mform->addHelpButton('declarationimage', 'declarationimage', 'evadeclaration');

        // declaration Text HTML editor.
        $mform->addElement('editor', 'declarationtext',
            get_string('declarationtext', 'evadeclaration'), null,
            evadeclaration_get_editor_options($this->context)
        );
        $mform->addRule('declarationtext', get_string('error'), 'required', null, 'client');
        $mform->addHelpButton('declarationtext', 'declarationtext', 'evadeclaration');

        // declaration Width.
        $mform->addElement('text', 'width', get_string('width', 'evadeclaration'), array('size' => '5'));
        $mform->setType('width', PARAM_INT);
        $mform->setDefault('width', get_config('evadeclaration', 'width'));
        $mform->setAdvanced('width');
        $mform->addHelpButton('width', 'size', 'evadeclaration');

        // declaration Height.
        $mform->addElement('text', 'height', get_string('height', 'evadeclaration'), array('size' => '5'));
        $mform->setType('height', PARAM_INT);
        $mform->setDefault('height', get_config('evadeclaration', 'height'));
        $mform->setAdvanced('height');
        $mform->addHelpButton('height', 'size', 'evadeclaration');

        // declaration Position X.
        $mform->addElement('text', 'declarationtextx', get_string('declarationtextx', 'evadeclaration'), array('size' => '5'));
        $mform->setType('declarationtextx', PARAM_INT);
        $mform->setDefault('declarationtextx', get_config('evadeclaration', 'declarationtextx'));
        $mform->setAdvanced('declarationtextx');
        $mform->addHelpButton('declarationtextx', 'textposition', 'evadeclaration');

        // declaration Position Y.
        $mform->addElement('text', 'declarationtexty', get_string('declarationtexty', 'evadeclaration'), array('size' => '5'));
        $mform->setType('declarationtexty', PARAM_INT);
        $mform->setDefault('declarationtexty', get_config('evadeclaration', 'declarationtexty'));
        $mform->setAdvanced('declarationtexty');
        $mform->addHelpButton('declarationtexty', 'textposition', 'evadeclaration');

        // Second page.
        $mform->addElement('header', 'secondpageoptions', get_string('secondpageoptions', 'evadeclaration'));
        // Enable back page text.

        $mform->addElement('selectyesno', 'enablesecondpage', get_string('enablesecondpage', 'evadeclaration'));
        $mform->setDefault('enablesecondpage', get_config('evadeclaration', 'enablesecondpage'));
        $mform->addHelpButton('enablesecondpage', 'enablesecondpage', 'evadeclaration');

        // declaration secondimage file.
        $mform->addElement('filemanager', 'secondimage',
            get_string('secondimage', 'evadeclaration'), null,
            $this->get_filemanager_options_array());
        $mform->addHelpButton('secondimage', 'secondimage', 'evadeclaration');
        $mform->disabledIf('secondimage', 'enablesecondpage', 'eq', 0);

        // declaration secondText HTML editor.
        $mform->addElement('editor', 'secondpagetext',
            get_string('secondpagetext', 'evadeclaration'), null,
            evadeclaration_get_editor_options($this->context));
        $mform->addHelpButton('secondpagetext', 'declarationtext', 'evadeclaration');
        $mform->disabledIf('secondpagetext', 'enablesecondpage', 'eq', 0);

        // declaration Position X.
        $mform->addElement('text', 'secondpagex', get_string('secondpagex', 'evadeclaration'), array('size' => '5'));
        $mform->setType('secondpagex', PARAM_INT);
        $mform->setDefault('secondpagex', get_config('evadeclaration', 'declarationtextx'));
        $mform->setAdvanced('secondpagex');
        $mform->addHelpButton('secondpagex', 'secondtextposition', 'evadeclaration');
        $mform->disabledIf('secondpagex', 'enablesecondpage', 'eq', 0);

        // declaration Position Y.
        $mform->addElement('text', 'secondpagey', get_string('secondpagey', 'evadeclaration'), array('size' => '5'));
        $mform->setType('secondpagey', PARAM_INT);
        $mform->setDefault('secondpagey', get_config('evadeclaration', 'declarationtexty'));
        $mform->setAdvanced('secondpagey');
        $mform->addHelpButton('secondpagey', 'secondtextposition', 'evadeclaration');
        $mform->disabledIf('secondpagey', 'enablesecondpage', 'eq', 0);

        // Variable options.
        $mform->addElement('header', 'variablesoptions', get_string('variablesoptions', 'evadeclaration'));
        // declaration Alternative Course Name.
        $mform->addElement('text', 'coursename', get_string('coursename', 'evadeclaration'), array('size' => '64'));
        $mform->setType('coursename', PARAM_TEXT);
        $mform->setAdvanced('coursename');
        $mform->addHelpButton('coursename', 'coursename', 'evadeclaration');

        // declaration Outcomes.
        $outcomeoptions = evadeclaration_get_outcomes();
        $mform->addElement('select', 'outcome', get_string('printoutcome', 'evadeclaration'), $outcomeoptions);
        $mform->setDefault('outcome', 0);
        $mform->addHelpButton('outcome', 'printoutcome', 'evadeclaration');

        // declaration date options.
        $mform->addElement('select', 'decldate', get_string('printdate', 'evadeclaration'),
                        evadeclaration_get_date_options());
        $mform->setDefault('decldate', get_config('evadeclaration', 'decldate'));
        $mform->addHelpButton('decldate', 'printdate', 'evadeclaration');

        // declaration date format.
        $mform->addElement('text', 'decldatefmt', get_string('datefmt', 'evadeclaration'));
        $mform->setDefault('decldatefmt', '');
        $mform->setType('decldatefmt', PARAM_TEXT);
        $mform->addHelpButton('decldatefmt', 'datefmt', 'evadeclaration');
        $mform->setAdvanced('decldatefmt');

        // declaration timestart date format.
        $mform->addElement('text', 'timestartdatefmt', get_string('timestartdatefmt', 'evadeclaration'));
        $mform->setDefault('timestartdatefmt', '');
        $mform->setType('timestartdatefmt', PARAM_TEXT);
        $mform->addHelpButton('timestartdatefmt', 'timestartdatefmt', 'evadeclaration');
        $mform->setAdvanced('timestartdatefmt');

        // declaration grade Options.
        $mform->addElement('select', 'declgrade', get_string('printgrade', 'evadeclaration'),
                        evadeclaration_get_grade_options());
        $mform->setDefault('declgrade', 0);
        $mform->addHelpButton('declgrade', 'printgrade', 'evadeclaration');

        // declaration grade format.
        $gradeformatoptions = array( 1 => get_string('gradepercent', 'evadeclaration'),
                                2 => get_string('gradepoints', 'evadeclaration'),
                                3 => get_string('gradeletter', 'evadeclaration')
        );
        $mform->addElement('select', 'gradefmt', get_string('gradefmt', 'evadeclaration'), $gradeformatoptions);
        $mform->setDefault('gradefmt', 0);
        $mform->addHelpButton('gradefmt', 'gradefmt', 'evadeclaration');

        // QR code.
        $mform->addElement('selectyesno', 'printqrcode', get_string('printqrcode', 'evadeclaration'));
        $mform->setDefault('printqrcode', get_config('evadeclaration', 'printqrcode'));
        $mform->addHelpButton('printqrcode', 'printqrcode', 'evadeclaration');

        $mform->addElement('text', 'codex', get_string('codex', 'evadeclaration'), array('size' => '5'));
        $mform->setType('codex', PARAM_INT);
        $mform->setDefault('codex', get_config('evadeclaration', 'codex'));
        $mform->setAdvanced('codex');
        $mform->addHelpButton('codex', 'qrcodeposition', 'evadeclaration');

        $mform->addElement('text', 'codey', get_string('codey', 'evadeclaration'), array('size' => '5'));
        $mform->setType('codey', PARAM_INT);
        $mform->setDefault('codey', get_config('evadeclaration', 'codey'));
        $mform->setAdvanced('codey');
        $mform->addHelpButton('codey', 'qrcodeposition', 'evadeclaration');

        $mform->addElement('selectyesno', 'qrcodefirstpage', get_string('qrcodefirstpage', 'evadeclaration'));
        $mform->setDefault('qrcodefirstpage', get_config('evadeclaration', 'qrcodefirstpage'));
        $mform->addHelpButton('qrcodefirstpage', 'qrcodefirstpage', 'evadeclaration');

        // Issue options.

        $mform->addElement('header', 'issueoptions', get_string('issueoptions', 'evadeclaration'));

        // Email to teachers ?
        $mform->addElement('selectyesno', 'emailteachers', get_string('emailteachers', 'evadeclaration'));
        $mform->setDefault('emailteachers', 0);
        $mform->addHelpButton('emailteachers', 'emailteachers', 'evadeclaration');

        // Email Others.
        $mform->addElement('text', 'emailothers', get_string('emailothers', 'evadeclaration'),
                        array('size' => '40', 'maxsize' => '200'));
        $mform->setType('emailothers', PARAM_TEXT);
        $mform->addHelpButton('emailothers', 'emailothers', 'evadeclaration');

        // Email From.
        $mform->addElement('text', 'emailfrom', get_string('emailfrom', 'evadeclaration'),
                        array('size' => '40', 'maxsize' => '200'));
        $mform->setDefault('emailfrom', $CFG->supportname);
        $mform->setType('emailfrom', PARAM_EMAIL);
        $mform->addHelpButton('emailfrom', 'emailfrom', 'evadeclaration');
        $mform->setAdvanced('emailfrom');

        // Delivery Options (Email, Download,...).
        $deliveryoptions = array(
            0 => get_string('openbrowser', 'evadeclaration'),
            1 => get_string('download', 'evadeclaration'),
            2 => get_string('emaildeclaration', 'evadeclaration'),
            3 => get_string('nodelivering','evadeclaration'),
            4 => get_string('emailoncompletion', 'evadeclaration'),
        );
        $mform->addElement('select', 'delivery', get_string('delivery', 'evadeclaration'), $deliveryoptions);
        $mform->setDefault('delivery', 0);
        $mform->addHelpButton('delivery', 'delivery', 'evadeclaration');

        // Report decl.
        // TODO acredito que seja para verificar a declaração pelo código, se for isto pode remover.
        $reportfile = "$CFG->dirroot/evadeclarations/index.php";
        if (file_exists($reportfile)) {
            $mform->addElement('selectyesno', 'reportdecl', get_string('reportdecl', 'evadeclaration'));
            $mform->setDefault('reportdecl', 0);
            $mform->addHelpButton('reportdecl', 'reportdecl', 'evadeclaration');
        }

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Prepares the form before data are set
     *
     * Additional wysiwyg editor are prepared here, the introeditor is prepared automatically by core.
     * Grade items are set here because the core modedit supports single grade item only.
     *
     * @param array $data to be set
     * @return void
     */
    public function data_preprocessing(&$data) {
        global $CFG;
        require_once(dirname(__FILE__) . '/locallib.php');
        parent::data_preprocessing($data);
        if ($this->current->instance) {
            // Editing an existing declaration - let us prepare the added editor elements (intro done automatically), and files.
            // First Page.
            // Get firstimage.
            $imagedraftitemid = file_get_submitted_draft_itemid('declarationimage');
            // Get firtsimage filearea information.
            $imagefileinfo = evadeclaration::get_declaration_image_fileinfo($this->context);
            file_prepare_draft_area($imagedraftitemid, $imagefileinfo['contextid'],
                            $imagefileinfo['component'], $imagefileinfo['filearea'],
                            $imagefileinfo['itemid'],
                            $this->get_filemanager_options_array());

            $data['declarationimage'] = $imagedraftitemid;

            // Prepare declaration text.
            $data['declarationtext'] = array('text' => $data['declarationtext'], 'format' => FORMAT_HTML);

            // Second page.
            // Get Back image.
            $secondimagedraftitemid = file_get_submitted_draft_itemid('secondimage');
            // Get secondimage filearea info.
            $secondimagefileinfo = evadeclaration::get_declaration_secondimage_fileinfo($this->context);
            file_prepare_draft_area($secondimagedraftitemid, $secondimagefileinfo['contextid'],
                            $secondimagefileinfo['component'], $secondimagefileinfo['filearea'],
                            $secondimagefileinfo['itemid'],
                            $this->get_filemanager_options_array());
            $data['secondimage'] = $secondimagedraftitemid;

            // Get backpage text.
            if (!empty($data['secondpagetext'])) {
                $data['secondpagetext'] = array('text' => $data['secondpagetext'], 'format' => FORMAT_HTML);
            } else {
                $data['secondpagetext'] = array('text' => '', 'format' => FORMAT_HTML);
            }
        } else { // Load default.
            $data['declarationtext'] = array('text' => '', 'format' => FORMAT_HTML);
            $data['secondpagetext'] = array('text' => '', 'format' => FORMAT_HTML);
        }

        // Completion rules.
        $data['completiontimeenabled'] = !empty($data['requiredtime']) ? 1 : 0;

    }

    public function add_completion_rules() {
        $mform =& $this->_form;

        $group = array();

        $group[] =& $mform->createElement('checkbox', 'completiontimeenabled', ' ',
                        get_string('coursetimereq', 'evadeclaration'));
        $group[] =& $mform->createElement('text', 'requiredtime', '', array('size' => '3'));
        $mform->setType('requiredtime', PARAM_INT);
        $mform->addGroup($group, 'completiontimegroup', get_string('coursetimereq', 'evadeclaration'), array(' '), false);

        $mform->addHelpButton('completiontimegroup', 'coursetimereq', 'evadeclaration');
        $mform->disabledIf('requiredtime', 'completiontimeenabled', 'notchecked');

        return array('completiontimegroup');
    }

    public function completion_rule_enabled($data) {
        return (!empty($data['completiontimeenabled']) && $data['requiredtime'] != 0);
    }

    public function data_postprocessing($data) {
        // For Completion Rules.
        if (!empty($data->completionunlocked)) {
            // Turn off completion settings if the checkboxes aren't ticked.
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completiontimeenabled) || !$autocompletion) {
                $data->requiredtime = 0;
            }
        }
        // File manager always creata a Files folder, so declimages is never empty.
        // I must check if it has a file or it's only a empty files folder reference.
        if (isset($data->declarationimage) && !empty($data->declarationimage)
            && !$this->check_has_files('declarationimage')) {
                $data->declarationimage = null;

        }

        if (isset($data->secondimage) && !empty($data->secondimage) &&
            !$this->check_has_files('secondimage')) {
                $data->secondimage = null;

        }
    }

    /**
     * Some basic validation
     *
     * @param $data
     * @param $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Check that the required time entered is valid.
        if ((isset($data['requiredtime']) && $data['requiredtime'] < 0)) {
            $errors['requiredtime'] = get_string('requiredtimenotvalid', 'evadeclaration');
        }

        return $errors;
    }

    private function check_has_files($itemname) {
        global $USER;

        $draftitemid = file_get_submitted_draft_itemid($itemname);
        file_prepare_draft_area($draftitemid, $this->context->id, 'mod_evadeclaration', 'imagefilecheck', null,
                                $this->get_filemanager_options_array());

        // Get file from users draft area.
        $usercontext = context_user::instance($USER->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files($usercontext->id, 'user', 'draft', $draftitemid, 'id', false);

        return (count($files) > 0);
    }

    private function get_filemanager_options_array () {
        global $COURSE;

        return array('subdirs' => 0, 'maxbytes' => $COURSE->maxbytes, 'maxfiles' => 1,
                'accepted_types' => array('image'));
    }

}