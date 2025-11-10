<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
include_once($CFG->dirroot . '/course/lib.php');

class block_eva_blog_list extends block_list {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_blog_list');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
      $ccnBlockHandler = new ccnBlockHandler();
      return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }


    function get_content() {
        global $CFG, $USER, $DB, $OUTPUT, $PAGE, $COURSE;

        if($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        
        if(!empty($this->config->title)){$this->content->title = $this->config->title;}
        
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_blog_list/css/about.css'));

        $this->content->footer = '
        <div class="selected_filter_widget style2 mb30">
          <div id="accordion" class="panel-group">
            <div class="panel">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a href="#panelBodySoftware" class="accordion-toggle link fz20 mb15" data-toggle="collapse" data-parent="#accordion">'. $this->content->title .'</a>
                </h4>
              </div>
              <div id="panelBodySoftware" class="panel-collapse collapse show">
                <div class="panel-body">
                  <div class="category_sidebar_widget">
                    <ul class="category_list no-list-style">';
                    $urlreturn = $CFG->wwwroot."/blog/index.php";
                    $see_more = get_string('see_more', 'theme_evagu');
                    $url = new moodle_url('/blog/index.php');

                    $categorias = $DB->get_records('course_categories', array('visible' => 1));

                    if (count($categorias) > 1) {
                        foreach ($categorias as $category) {
                            $categoryname = $category->name;
                            $categoryid = $category->id;
                            
                            if (!empty($categoryid)) {
                                $url->param('category', $categoryid);
                            }
                    
                            $url->out(false);

                            $sql = "SELECT count(*) FROM {post} WHERE category = " . $categoryid;
                            $count = $DB->count_records_sql($sql);
                            $this->content->footer .= '<li><a href="'.str_ireplace("&amp;","&",$url).'">('.$count.')&nbsp;'.$categoryname.'</a></li>';
                        }
                    }
                    
                    $this->content->footer .='
                    </ul>';
                    $this->content->footer .= '<br><a href="'.$urlreturn.'"><span class="fa fa-plus pr10"></span>'.$see_more.'</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>';
        return $this->content;
    }

    /**
     * Returns the role that best describes the course list block.
     *
     * @return string
     */
    public function get_aria_role() {
        return 'navigation';
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
      return $attributes;
    }
}
