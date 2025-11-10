<?php

/*
 * @ccnRef: @template block_eva_library_list
 */
defined('MOODLE_INTERNAL') || die();
include_once ($CFG->dirroot . '/course/lib.php');
require_once ($CFG->libdir . '/filelib.php');  // Necessário para file_encode_url()
$_ccnlibrarylist = '';
$_ccnlibrarylist = '<ul id="vertical-menu" class="mega-vertical-menu nav navbar-nav">';
$topcategory = core_course_category::top();
if ($topcategory->is_uservisible() && ($categories = $topcategory->get_children())) {  // Check we have categories.
    if (count($categories) > 1 || (count($categories) == 1 && $DB->count_records('course') > 200)) {  // Just print top level category links
        foreach ($categories as $category) {
            $children_courses = $category->get_courses();
            //  print_object($children_courses);
            foreach ($children_courses as $child_course) {
                if ($child_course === reset($children_courses)) {
                    foreach ($child_course->get_course_overviewfiles() as $file) {
                        if ($file->is_valid_image()) {
                            // <<< ajuste mínimo: inserir itemid no path >>>
                            $imagepath = '/' . $file->get_contextid()
                                . '/' . $file->get_component()
                                . '/' . $file->get_filearea()
                                . '/' . $file->get_itemid()
                                . $file->get_filepath()
                                . $file->get_filename();
                            $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
                            $outputimage = $imageurl;
                            // Use the first image found.
                            break;
                        }
                    }
                }
            }
            $categoryname = $category->get_formatted_name();
            $linkcss = $category->visible ? '' : ' class="dimmed" ';
            $_ccnlibrarylist .= '
            <li><a href="' . $CFG->wwwroot . '/course/index.php?categoryid=' . $category->id . '"' . $linkcss . '>' . $categoryname . '</a></li>';
        }
    }
}
$_ccnlibrarylist .= '
</ul>
';
