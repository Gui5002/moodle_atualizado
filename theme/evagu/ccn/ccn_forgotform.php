<?php
/*
@ccnRef: @template block_login
*/

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->dirroot . '/login/lib.php');
GLOBAL $PAGE;

if (signup_is_enabled()) {
    $signup = $CFG->wwwroot . '/login/signup.php';
}
$forgot = $CFG->wwwroot . '/login/forgot_password.php';
$username = get_moodle_cookie();
$_ccnforgot = '';
if (!isloggedin() or isguestuser()) {   // Show the block

    $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/theme/evagu/style/tela-login.css'));

    $_ccnforgot .= '<div class="formcenter">';
    $_ccnforgot .= "\n".'<form autocomplete="off" class="mform" method="post" action="'.$forgot.'" id="mform1_'.random_string().'">';

    $_ccnforgot .= '<div class="form-group">';
    $_ccnforgot .= '<input type="text" name="username" placeholder="'.get_string('username', 'theme_evagu').'" id="id_username" required ';
    $_ccnforgot .= ' class="form-control fc-campo" value="" autocomplete="username"/>';
//    $_ccnforgot .= '<div class="form-control-feedback invalid-feedback" id="id_error_username"></div>';
    $_ccnforgot .= '</div>';

    $_ccnforgot .= '<div class="form-group">';
    $_ccnforgot .= '<input type="text" name="email" id="id_email" placeholder="'.get_string('email_address', 'theme_evagu').'" autocomplete="email" ';
    $_ccnforgot .= ' class="form-control fc-campo" value="" autocomplete="email"/>';
    $_ccnforgot .= '</div>';

    $_ccnforgot .= '</br>';
    $_ccnforgot .= '<button type="submit" class="btn btn-thm btn-login btcenter" name="submitbutton">'.get_string('submit').'</button>';

//    $_ccnforgot .= '<button type="submit" class="btn btn-thm btn-login float-right" name="submitbuttonemail" id="id_submitbuttonemaiil">'.get_string('search').'</button>';

    $_ccnforgot .= '<input type="hidden" name="logintoken" value="'.s(\core\session\manager::get_login_token()).'" />';
    $_ccnforgot .= "</form>\n";
    $_ccnforgot .= '</div>';

    /* if (!empty($signup)) {
        $_ccnforgot .= '<div><a href="'.$signup.'">'.get_string('startsignup').'</a></div>';
    } */
   /* if (!empty($forgot)) {
        $_ccnforgot .= '<div><a href="'.$forgot.'">'.get_string('forgotaccount').'</a></div>';
    } */

//    $authsequence = get_enabled_auth_plugins(true); // Get all auths, in sequence.
//    $potentialidps = array();
//    foreach ($authsequence as $authname) {
//        $authplugin = get_auth_plugin($authname);
//        $potentialidps = array_merge($potentialidps, $authplugin->loginpage_idp_list($this->page->url->out(false)));
//    }
//
//    if (!empty($potentialidps)) {
//        $_ccnforgot .= '<div class="potentialidps">';
//        $_ccnforgot .= '<h6>' . get_string('potentialidps', 'auth') . '</h6>';
//        $_ccnforgot .= '<div class="potentialidplist">';
//        foreach ($potentialidps as $idp) {
//            $_ccnforgot .= '<div class="potentialidp">';
//            $_ccnforgot .= '<a class="btn btn-secondary btn-block" ';
//            $_ccnforgot .= 'href="' . $idp['url']->out() . '" title="' . s($idp['name']) . '">';
//            if (!empty($idp['iconurl'])) {
//                $_ccnforgot .= '<img src="' . s($idp['iconurl']) . '" width="24" height="24" class="mr-1"/>';
//            }
//            $_ccnforgot .= s($idp['name']) . '</a></div>';
//        }
//        $_ccnforgot .= '</div>';
//        $_ccnforgot .= '</div>';
//    }
}
function validation($data, $files) {

    $errors =  validation($data, $files);

    // Extend validation for any form extensions from plugins.
    $errors = array_merge($errors, core_login_validate_extend_forgot_password_form($data));

    $errors += core_login_validate_forgot_password_data($data);

    return $errors;
}
/* Login */
