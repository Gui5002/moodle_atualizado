<?php
global $CFG;
require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_steps extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_eva_steps');
    }

    public function specialization()
    {
        global $CFG, $DB;
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->slidesnumber = '3';
            $this->config->title = 'Passo a passo';
            $this->config->subtitle = 'A tecnologia está trazendo uma onda massiva de evolução no aprendizado de diferentes maneiras.';
            $this->config->title1 = 'Escolha o que fazer';
            $this->config->title2 = 'Escolha o que fazer';
            $this->config->title3 = 'Escolha o que fazer';
            $this->config->body1 = 'Texto com pequeno descritivop não colocar memorando.';
            $this->config->body2 = 'Texto com pequeno descritivop não colocar memorando.';
            $this->config->body3 = 'Texto com pequeno descritivop não colocar memorando.';
            $this->config->link1 = '#';
            $this->config->link2 = '#';
            $this->config->link3 = '#';
            $this->config->image1 = $CFG->wwwroot . '/theme/evagu/images/process/1.png';
            $this->config->image2 = $CFG->wwwroot . '/theme/evagu/images/process/2.png';
            $this->config->image3 = $CFG->wwwroot . '/theme/evagu/images/process/3.png';
        }
    }

    public function get_content()
    {
        global $CFG, $DB;
        require_once ($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        if (!empty($this->config->title)) {
            $this->content->title = $this->config->title;
        } else {
            $this->content->title = '';
        }
        if (!empty($this->config->subtitle)) {
            $this->content->subtitle = $this->config->subtitle;
        } else {
            $this->content->title = '';
        }
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int) $data->slidesnumber : 3;
        } else {
            $data = new stdClass();
            $data->slidesnumber = '3';
        }
        $this->content->text = "
        <section class=\"how-it-works\">
      \t\t<div class=\"container\">
      \t\t\t<div class=\"row\">
      \t\t\t\t<div class=\"col-lg-6 offset-lg-3\">
      \t\t\t\t\t<div class=\"main-title text-center\">
      \t\t\t\t\t\t<h3 class=\"mt0\" data-ccn=\"title\">" . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . "</h3>
      \t\t\t\t\t\t<p class=\"\" data-ccn=\"subtitle\">" . format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)) . "</p>
      \t\t\t\t\t</div>
      \t\t\t\t</div>
      \t\t\t</div>
      \t\t\t<div class=\"row\">";
        if ($data->slidesnumber > 0) {
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                // $mainfile = null;
                $title = 'title' . $i;
                $link = 'link' . $i;
                $body = 'body' . $i;
                $icon = 'icon' . $i;
                $image = 'image' . $i;
                $fs = get_file_storage();
                $files = $fs->get_area_files($this->context->id, 'block_eva_steps', 'items', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                if (!empty($data->$image) && count($files) >= 1) {
                    $mainfile = reset($files);
                    $mainfile = $mainfile->get_filename();
                    $data->$image = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_steps/items/" . $i . '/' . $mainfile);
                } else {
                    $data->$image = $CFG->wwwroot . '/theme/evagu/images/process/1.png';
                }
                $this->content->text .= '
<div class="work-block col-lg-4 col-md-6 col-sm-12">
                    <div class="inner-box">';
                $this->content->text .= '<figure class="icon-box"><img data-ccn="image' . $i . '" data-ccn-img="src" src="' . $data->$image . '" alt=""></figure>';
                $this->content->text .= '
                        <h4><a data-ccn="title' . $i . '" href="' . $data->$link . '">' . format_text($data->$title, FORMAT_HTML, array('filter' => true)) . '</a></h4>
                        <div class="text" data-ccn="body' . $i . '">' . format_text($data->$body, FORMAT_HTML, array('filter' => true)) . '</div>
                    </div>
                </div>';
            }
        }
        $this->content->text .= "
      \t\t\t</div>
      \t\t</div>
      \t</section>
";
        return $this->content;
    }

    /**
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple()
    {
        return true;
    }

    /**
     * @return bool True if the global configuration is enabled.
     */
    function has_config()
    {
        return true;
    }

    /**
     * @return string[] Array of pages and permissions.
     */
    function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }

    function instance_config_save($data, $nolongerused = false)
    {
        global $CFG;
        $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.gif'));
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_steps', 'items', $i, $filemanageroptions);
        }
        parent::instance_config_save($data, $nolongerused);
    }

    function instance_delete()
    {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_steps');
        return true;
    }

    /**
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid)
    {
        global $CFG;
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int) $data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.gif'));
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            if (!$fs->is_area_empty($fromcontext->id, 'block_eva_steps', 'items', $i, false)) {
                $draftitemid = 0;
                file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_steps', 'items', $i, $filemanageroptions);
                file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_steps', 'items', $i, $filemanageroptions);
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function instance_can_be_docked()
    {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
}
