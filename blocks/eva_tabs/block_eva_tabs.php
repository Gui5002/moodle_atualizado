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
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_tabs extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_tabs');
    }
     function applicable_formats() {
       $ccnBlockHandler = new ccnBlockHandler();
       return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
     }
    function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config = new \stdClass();
          $this->config->slidesnumber = '3';
          $this->config->title = 'Sequencia de Trilhas';
          $this->config->title1 = 'Education';
          $this->config->title2 = 'Training';
          $this->config->title3 = 'Academia';
          $this->config->text1['text'] = 'Lorem RCN is simply renata text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard renata text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
          $this->config->text2['text'] = 'Lorem RCN is simply renata text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard renata text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
          $this->config->text3['text'] = 'Lorem RCN is simply renata text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard renata text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
        }
    }
    function instance_allow_multiple() {
        return true;
    }
    function get_content() {
        global $CFG, $PAGE;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== NULL) {
            return $this->content;
        }
        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
            if(!empty($this->config->title)){$this->content->title = $this->config->title;}
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 3;
        } else {
            $data = new stdClass();
            $data->slidesnumber = '3';
        }
        $text = '';
        if ($data->slidesnumber > 0) {
            $text .= '
            <div class="shortcode_widget_tab">';
              if(!empty($this->config->title)){
                $text .='  <h4 data-ccn="title">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h4>';
              }
            $text .='
                <div class="ui_kit_tab mt30">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">';
                  for ($i = 1; $i <= $data->slidesnumber; $i++) {
                    $ccnTabTitle = 'title' . $i;
                    $ccnTabLink = 'tab-'. $this->instance->id . $i;
                    $ccnAriaSelected = 'false';
                    $ccnClass = 'nav-link';
                    if($i == 1){
                      $ccnAriaSelected = 'true';
                      $ccnClass .= ' active';
                    }
                    $text .= '<li class="nav-item">
                                <a data-ccn="'.$ccnTabTitle.'" class="'.$ccnClass.'" id="'.$ccnTabLink.'-tab" data-toggle="tab" href="#'.$ccnTabLink.'" role="tab" aria-controls="'.$ccnTabLink.'" aria-selected="true">'.format_text($data->$ccnTabTitle, FORMAT_HTML, array('filter' => true)).'</a>
                              </li>';
                  }
                 $text .='
                    </ul>
                    <div class="tab-content" id="myTabContent">';
                    for ($i = 1; $i <= $data->slidesnumber; $i++) {
                      $ccnTabBody = 'text' . $i;
                      $ccnTabLink = 'tab-'. $this->instance->id . $i;
                      $ccnBodyClass = 'tab-pane fade';
                      if($i == 1){
                        $ccnBodyClass .= ' show active';
                      }
                      $text .='<div data-ccn="'.$ccnTabBody.'" class="'.$ccnBodyClass.'" id="'.$ccnTabLink.'" role="tabpanel" aria-labelledby="'.$ccnTabLink.'-tab">'.format_text($data->$ccnTabBody['text'], FORMAT_HTML, array('filter' => true, 'noclean' => true)).'</div>';
                    }
              $text .='
                  </div>
                </div>
              </div>';
        }
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;
        return $this->content;
  }
    /**
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
      return $attributes;
    }
}
