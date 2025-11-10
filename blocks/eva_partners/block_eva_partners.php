<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_partners extends block_base
{
public function init()
{
$this->title = get_string('eva_partners', 'block_eva_partners');
}
public function specialization()
{
global $CFG, $DB;
include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
if (empty($this->config)) {
  $this->config = new \stdClass();
  $this->config->title = 'Need To Train Your Team?';
  $this->config->subtitle = 'Os serviços de uma escola superior tem uma variedade de atividades.';
  $this->config->color_bg = '#fff';
  $this->config->color_title = '#0a0a0a';
  $this->config->color_subtitle = '#6f7074';
}
}
public function get_content(){
global $CFG, $DB;
require_once($CFG->libdir . '/filelib.php');
if ($this->content !== null) {
return $this->content;
}
$this->content =  new \stdClass();
if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = "";}
if(!empty($this->config->subtitle)){$this->content->subtitle = $this->config->subtitle;} else {$this->content->subtitle = "";}
if(!empty($this->config->color_bg)){$this->content->color_bg = $this->config->color_bg;} else {$this->content->color_bg = "#fff";}
if(!empty($this->config->color_title)){$this->content->color_title = $this->config->color_title;} else {$this->content->color_title = "#0a0a0a";}
if(!empty($this->config->color_subtitle)){$this->content->color_subtitle = $this->config->color_subtitle;} else {$this->content->color_subtitle = "#6f7074";}
$fs = get_file_storage();
$files = $fs->get_area_files($this->context->id, 'block_eva_partners', 'content');
$this->content->image = '';
foreach ($files as $file) {
$filename = $file->get_filename();
if ($filename <> '.') {
$url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
$this->content->image .= '
<div class="col-sm-6 col-md-4 col-lg">
							<div class="our_partner">
								<img class="img-fluid" src="'. $url.'" alt="'. $filename.'">
							</div>
						</div>
				';
}
}
$this->content->text = '
<section id="our-partners" class="our-partners" data-ccn-c="color_bg" data-ccn-co="bg" data-ccn-cv="'.$this->content->color_bg.'">
  		<div class="container">
  			<div class="row">
  				<div class="col-lg-6 offset-lg-3">
  					<div class="main-title text-center">
  						<h3 data-ccn="title" class="mt0" data-ccn-c="color_title" data-ccn-cv="'.$this->content->color_title.'">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h3>
  						<p data-ccn="subtitle" data-ccn-c="color_subtitle" data-ccn-cv="'.$this->content->color_subtitle.'">'.format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)).'</p>
  					</div>
  				</div>
  			</div>
  			<div class="row">
  				<div class="col-lg-8 offset-lg-2">
  					<div class="row">'. $this->content->image .'</div>
  </div>
</div>
  </div>
</section>';
return $this->content;
}
/**
 * @return bool True if multiple instances are allowed, false otherwise.
 */
public function instance_allow_multiple() {
return true;
}
/**
 * @return bool True if the global configuration is enabled.
 */
function has_config() {
return true;
}
/**
 * @return string[] Array of pages and permissions.
 */
 function applicable_formats() {
 $ccnBlockHandler = new ccnBlockHandler();
 return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
 }
 public function html_attributes() {
   global $CFG;
   $attributes = parent::html_attributes();
   include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
   return $attributes;
 }
}
