<?php
require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once ($CFG->dirroot . '/theme/evagu/ccn/user_handler/ccn_user_handler.php');

class block_eva_users_slider extends block_base
{
  /**
   * Initializes class member variables.
   */
  public function init()
  {
    // Needed by Moodle to differentiate between blocks.
    $this->title = get_string('pluginname', 'block_eva_users_slider');
  }

  /**
   * Defines configuration data.
   *
   * The function is called immediatly after init().
   */
  public function specialization()
  {
    global $CFG, $DB;
    include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    if (empty($this->config)) {
      $ccnUserHandler = new ccnUserHandler();
      $ccnUsers = $ccnUserHandler->ccnGetExampleUsersIds(8);
      $this->config = new \stdClass();
      $this->config->title = 'Corpo Docente ESAGU';
      $this->config->users = $ccnUsers;
    }
  }

  /**
   * Returns the block contents.
   *
   * @return stdClass The block contents.
   */
  public function get_content()
  {
    if ($this->content !== null) {
      return $this->content;
    }
    if (empty($this->instance)) {
      $this->content = '';
      return $this->content;
    }
    $this->content = new stdClass();
    $this->content->items = array();
    $this->content->icons = array();
    $this->content->footer = '';
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
    if (!empty($this->config->users)) {
      $this->content->users = $this->config->users;
    } else {
      $this->content->users = '';
    }
    $this->content->text = '
        <section class="our-team instructor-page pb40 ccnEqOwl">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="main-title text-center">
                  <h3 class="mb0 mt0">' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h3>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="team_slider">';
    if (!empty($this->content->users)) {
      foreach ($this->content->users as $key => $ccnUserId) {
        if ($ccnUserId) {
          $ccnUserHandler = new ccnUserHandler();
          $ccnUser = $ccnUserHandler->ccnGetUserDetails($ccnUserId);
          $this->content->text .= '
                        <div class="item">
                          <a href="' . $ccnUser->profileUrl . "\">
              \t\t\t\t\t\t\t<div class=\"team_member style2 text-center ccnEqOwlItem\">
              \t\t\t\t\t\t\t\t<div class=\"instructor_col\">
              \t\t\t\t\t\t\t\t\t<div class=\"thumb\">
              \t\t\t\t\t\t\t\t\t\t<img class=\"img-fluid img-rounded-circle\" src=\"" . $ccnUser->rawAvatar . "\" alt=\"\">
              \t\t\t\t\t\t\t\t\t</div>
              \t\t\t\t\t\t\t\t\t<div class=\"details\">
              \t\t\t\t\t\t\t\t\t\t<h4>" . $ccnUser->fullname . "</h4>
              \t\t\t\t\t\t\t\t\t\t<p>" . $ccnUser->department . "</p>
              \t\t\t\t\t\t\t\t\t\t" . $ccnUser->ccnRender->teacherStarRating . "
              \t\t\t\t\t\t\t\t\t</div>
              \t\t\t\t\t\t\t\t</div>
              \t\t\t\t\t\t\t\t<div class=\"tm_footer\">
              \t\t\t\t\t\t\t\t\t<ul>
              \t\t\t\t\t\t\t\t\t\t<li class=\"list-inline-item\">" . get_string('last_online', 'theme_evagu') . ' ' . $ccnUser->ccnPretty->lastLogin . "</li>
               \t\t\t\t\t\t\t\t\t</ul>
               \t\t\t\t\t\t\t\t</div>
               \t\t\t\t\t\t\t</div>
                          </a>
                        </div>";
        }
      }
    }
    $this->content->text .= "
                </div>
      \t\t\t\t</div>
      \t\t\t</div>
      \t\t</div>
      \t</section>";
    return $this->content;
  }

  /**
   * Allow multiple instances in a single course?
   *
   * @return bool True if multiple instances are allowed, false otherwise.
   */
  public function instance_allow_multiple()
  {
    return true;
  }

  /**
   * Enables global configuration of the block in settings.php.
   *
   * @return bool True if the global configuration is enabled.
   */
  function has_config()
  {
    return true;
  }

  /**
   * Sets the applicable formats for the block.
   *
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
}
