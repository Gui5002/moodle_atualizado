<?php
/*
@ccnRef: @block_eva/block.php
*/

defined('MOODLE_INTERNAL') || die();

// if (!($this->config)) {
//   if(!($this->content)){
//     $this->content = new \stdClass();
//   }
//     $this->content->text = '<h5 class="mb30">'.$this->title.'</h5>';
//     return $this->content->text;
// }

// print_object($this);
$ccnBlockType = $this->instance->blockname;

$ccnCollectionFullwidthTop =  array(
    "eva_about_1",
    "eva_about_3",
    "eva_about_4",
    "eva_abouteva",
    "eva_abouteva_cards",
    "eva_abouteva_info",
    "eva_accordion",
    "eva_accordion2",
    "eva_blog_recent",
    "eva_blog_recent_list",
    "eva_blog_recent_slider",
    "eva_boxes",
    "eva_banner",
    "eva_cards_post_internship",
    "eva_cf_paid",
    "eva_cf_rating",
    "eva_contact_form",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_continuing_education_cicles",
    "eva_course_grid",
    "eva_course_grid_2",
    "eva_course_grid_3",
    "eva_event_body",
    "eva_event_contact",
    "eva_event_details",
    "eva_event_list",
    "eva_event_slider",
    "eva_faqs",
    "eva_featured_event",
    "eva_featured_posts",
    "eva_featured_video",
    "eva_featuredcourses",
    "eva_cen_geral",
    "eva_continuing_education_news",
    "eva_gallery",
    "eva_gallery_slider",
    "eva_globalsearch_n",
    "eva_globalsearch_sb",
    "eva_list_study_room",
    "eva_more_courses",
    "eva_my_courses",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_postgraduate_about",
    "eva_postgraduate_contact",
    "eva_postgraduate_services",
    "eva_revistagu",
    "eva_slider_8",
    // "eva_banner_slider",
    "eva_traning_suggestion",
    "eva_ts_controll",
    "eva_ts_view",
    "eva_tstmnls_3",
    "eva_users",
    "eva_users_slider",
    "eva_users_slider_round",
    "eva_library_central",
    "eva_library_service",
    "eva_library_information",
    "eva_about_databases",
    "eva_cards_database",
    "eva_video_pesquisa",
    "eva_publicidade",
    "eva_reports",
    "eva_reports_controll",
);

$ccnCollectionAboveContent =  array(
    "eva_about_1",
    "eva_about_3",
    "eva_about_4",
    "eva_abouteva",
    "eva_abouteva_cards",
    "eva_abouteva_info",
    "eva_accordion",
    "eva_accordion2",
    "eva_blog_recent",
    "eva_blog_recent_list",
    "eva_blog_recent_slider",
    "eva_boxes",
    "eva_banner",
    "eva_cards_post_internship",
    "eva_cf_paid",
    "eva_cf_rating",
    "eva_contact_form",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_continuing_education_cicles",
    "eva_course_grid",
    "eva_course_grid_2",
    "eva_course_grid_3",
    "eva_event_body",
    "eva_event_contact",
    "eva_event_details",
    "eva_event_list",
    "eva_event_slider",
    "eva_faqs",
    "eva_featured_event",
    "eva_featured_posts",
    "eva_featured_video",
    "eva_featuredcourses",
    "eva_cen_geral",
    "eva_continuing_education_news",
    "eva_gallery",
    "eva_gallery_slider",
    "eva_globalsearch_n",
    "eva_globalsearch_sb",
    "eva_list_study_room",
    "eva_more_courses",
    "eva_my_courses",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_postgraduate_about",
    "eva_postgraduate_contact",
    "eva_postgraduate_services",
    "eva_revistagu",
    "eva_banner_slider",
    "eva_traning_suggestion",
    "eva_ts_controll",
    "eva_ts_view",
    "eva_tstmnls_3",
    "eva_users",
    "eva_users_slider",
    "eva_users_slider_round",
    "eva_library_central",
    "eva_library_service",
    "eva_library_information",
    "eva_about_databases",
    "eva_cards_database",
    "eva_video_pesquisa",
    "eva_publicidade",
    "eva_reports",
    "eva_reports_controll",
);

$ccnCollectionBelowContent =  array(
    "eva_about_1",
    "eva_about_3",
    "eva_about_4",
    "eva_abouteva",
    "eva_abouteva_cards",
    "eva_abouteva_info",
    "eva_accordion",
    "eva_accordion2",
    "eva_blog_recent",
    "eva_blog_recent_list",
    "eva_blog_recent_slider",
    "eva_boxes",
    "eva_banner",
    "eva_cards_post_internship",
    "eva_cf_paid",
    "eva_cf_rating",
    "eva_contact_form",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_continuing_education_cicles",
    "eva_course_grid",
    "eva_course_grid_2",
    "eva_course_grid_3",
    "eva_event_body",
    "eva_event_contact",
    "eva_event_details",
    "eva_event_list",
    "eva_event_slider",
    "eva_faqs",
    "eva_featured_event",
    "eva_featured_posts",
    "eva_featured_video",
    "eva_featuredcourses",
    "eva_cen_geral",
    "eva_continuing_education_news",
    "eva_gallery",
    "eva_gallery_slider",
    "eva_globalsearch_n",
    "eva_globalsearch_sb",
    "eva_list_study_room",
    "eva_more_courses",
    "eva_my_courses",
    "eva_continuing_education_b1",
    "eva_continuing_education_b2",
    "eva_continuing_education_b3",
    "eva_postgraduate_about",
    "eva_postgraduate_contact",
    "eva_postgraduate_services",
    "eva_revistagu",
    "eva_banner_slider",
    "eva_traning_suggestion",
    "eva_ts_controll",
    "eva_ts_view",
    "eva_tstmnls_3",
    "eva_users",
    "eva_users_slider",
    "eva_users_slider_round",
    "eva_library_central",
    "eva_library_service",
    "eva_library_information",
    "eva_about_databases",
    "eva_cards_database",
    "eva_video_pesquisa",
    "eva_publicidade",
    "eva_reports",
    "eva_reports_controll",
);

$ccnCollection = array_merge($ccnCollectionFullwidthTop, $ccnCollectionAboveContent, $ccnCollectionBelowContent);

if (empty($this->config)) {
  if (in_array($ccnBlockType, $ccnCollectionFullwidthTop)) {
    $this->instance->defaultregion = 'fullwidth-top';
    $this->instance->region = 'fullwidth-top';
    $DB->update_record('block_instances', $this->instance);
  }
  if (in_array($ccnBlockType, $ccnCollectionAboveContent)) {
    $this->instance->defaultregion = 'above-content';
    $this->instance->region = 'above-content';
    $DB->update_record('block_instances', $this->instance);
  }
  if (in_array($ccnBlockType, $ccnCollectionBelowContent)) {
    $this->instance->defaultregion = 'below-content';
    $this->instance->region = 'below-content';
    $DB->update_record('block_instances', $this->instance);
  }
  /* Begin Legacy */
  if (!in_array($ccnBlockType, $ccnCollection)) {
    if (!($this->content)) {
      $this->content = new \stdClass();
    }
    $this->content->text = '<h5 class="mb30">' . $this->title . '</h5>';
    return $this->content->text;
  }
  /* End Legacy */
}