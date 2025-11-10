<?php
global $CFG;
require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_abouteva_cards extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_eva_abouteva_cards');
    }

    public function has_config()
    {
        return true;
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    public function specialization()
    {
        global $CFG, $DB;
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    }

    public function instance_allow_config()
    {
        return true;
    }

    public function get_content()
    {
        global $CFG, $USER, $DB, $OUTPUT;
        if ($this->content !== null) {
            return $this->content;
        }
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->items = is_numeric($data->items) ? (int) $data->items : 4;
        } else {
            $data = new stdClass();
            $data->items = 4;
        }
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->style = '';
        if (!empty($this->config->title)) {
            $this->content->title = $this->config->title;
        } else {
            $this->content->title = '';
        }
        if (!empty($this->config->subtitle)) {
            $this->content->subtitle = $this->config->subtitle;
        } else {
            $this->content->subtitle = '';
        }
        if (!empty($this->config->color_bg)) {
            $this->content->color_bg = $this->config->color_bg;
        } else {
            $this->content->color_bg = '#fff';
        }
        if (!empty($this->config->color_title)) {
            $this->content->color_title = $this->config->color_title;
        } else {
            $this->content->color_title = '#0a0a0a';
        }
        if (!empty($this->config->color_subtitle)) {
            $this->content->color_subtitle = $this->config->color_subtitle;
        } else {
            $this->content->color_subtitle = '#6f7074';
        }
        // if ($data->items > 0) {
        $this->content->text = '
        <section style="padding-top: 0px;" data-ccn-c="color_bg" data-ccn-cv="' . $this->content->color_bg . '" data-ccn-co="bg">
  <div class="container">
   <div class="row">
    <div class="col-lg-12">
     ' . $this->content->style . '
    </div>
   </div>
  </div>
  <div class="container">
   <div class="row">
        <div class="col-lg-10 offset-lg-1 text-center">
          <div class="main-title text-center">';
        $this->content->text .= '<h1 class="mt0">' . $this->content->title . '</h1>';
        $this->content->text .= '<p>' . $this->content->subtitle . '</p>';
        $this->content->text .= '
          </div>
        </div>
   </div>
      <div class="row">
      <div class="col-12">
      <div class="row justify-content-center">';
        $col_class = '';
        if ($data->items == 1) {
            $col_class = 'col-sm-12 col-lg-12';
        } else if ($data->items == 2) {
            $col_class = 'col-sm-6 col-lg-6';
        } else if ($data->items >= 3) {
            $col_class = 'col-sm-6 col-lg-4';
        }
        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {
                $icon = 'icon' . $i;
                $icon_color = 'color_icon' . $i;
                $icon_color_hover = 'color_icon_hover' . $i;
                $title_color = 'color_title' . $i;
                $title_color_hover = 'color_title_hover' . $i;
                $body_color = 'color_body' . $i;
                $body_color_hover = 'color_body_hover' . $i;
                $color = 'color' . $i;
                $color_hover = 'color_hover' . $i;
                $title = 'title' . $i;
                $body = 'body' . $i;
                $link = 'link' . $i;
                $link_target = 'link_target' . $i;
                $this->content->text .= '
              <style type="text/css">
              .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover {
                background-color:' . $data->$color_hover . '!important;
              }
              .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover h3 {
                color:' . $data->$title_color_hover . '!important;
              }
              .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover p {
                color:' . $data->$body_color_hover . '!important;
              }
              .icon_hvr_img_box.ccn-box [data-ccn-c=color' . $i . ']:hover .icon span {
                color:' . $data->$icon_color_hover . '!important;
              }
              </style>
              <div class="' . $col_class . '">';
                if (!empty($data->$link)) {
                    $this->content->text .= '<a href="' . $data->$link . '" target="' . $data->$link_target . '">';
                }
                $this->content->text .= '
           <div class="icon_hvr_img_box ccn-box sbbg1" data-ccn-c="color' . $i . '" data-ccn-co="bg" style="background-color: ' . $data->$color . ';min-height: 330px !important;max-height: 330px !important;max-width: 410px !important;">
            <div class="overlay">
             <div class="ccn_icon_2 icon"><span data-ccn="icon' . $i . '" data-ccn-c="color_icon' . $i . '" data-ccn-co="content" class="' . $data->$icon . '" style="color:' . $data->$icon_color . '"></span></div>
             <div>
              <h3 data-ccn="title' . $i . '" data-ccn-c="color_title' . $i . '" data-ccn-co="content" style="color:' . $data->$title_color . '; text-align: center;">' . $data->$title . '</h3>
              <p data-ccn="body' . $i . '" data-ccn-c="color_body' . $i . '" data-ccn-co="content" style="color:' . $data->$body_color . '; text-align: center;">' . $data->$body . '</p>
             </div>
            </div>
           </div>';
                if (!empty($data->$link)) {
                    $this->content->text .= '</a>';
                }
                $this->content->text .= '
          </div>';
            }
        }
        $this->content->text .= '
              </div>
            </div>
          </div>
        </div>
      </section>';
        return $this->content;
    }

    /**
     * Returns the role that best describes the course list block.
     *
     * @return string
     */
    public function get_aria_role()
    {
        return 'navigation';
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}