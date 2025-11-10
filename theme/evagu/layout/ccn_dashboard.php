<?php

defined('MOODLE_INTERNAL') || die();

echo $OUTPUT->doctype();

include ($CFG->dirroot . '/theme/evagu/ccn/ccn_themehandler.php');
include ($CFG->dirroot . '/theme/evagu/ccn/ccn_themehandler_context.php');

array_push($extraclasses, 'ccn_context_dashboard');
$bodyclasses = implode(' ', $extraclasses);
$bodyattributes = $OUTPUT->body_attributes($bodyclasses);

if ((int) $ccnMdlVersion >= 400) {
    echo $OUTPUT->render_from_template('theme_evagu/ccn_mdl_400/ccn_dashboard', $templatecontext);
} else {
    echo $OUTPUT->render_from_template('theme_evagu/ccn_dashboard', $templatecontext);
}
