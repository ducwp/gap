<?php
namespace GAPTheme\Datlichcuahang;

class Form {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('wpcf7_init', [$this, 'add_form_tags']);
    add_filter('wpcf7_validate_gap_date', [$this, 'gap_date_validation_filter'], 20, 2);
    add_filter('wpcf7_validate_gap_time', [$this, 'gap_time_validation_filter'], 20, 2);
  }

  public function add_form_tags() {
    wpcf7_add_form_tag('gap_calendar_icon', [$this, 'gap_calendar_icon_handler']);
    wpcf7_add_form_tag('gap_today', [$this, 'gap_today_handler']);
    wpcf7_add_form_tag(['gap_date', 'gap_date*'], [$this, 'gap_date_form_tag_handler'], array('name-attr' => true));
    wpcf7_add_form_tag(['gap_time', 'gap_time*'], [$this, 'gap_time_form_tag_handler'], array('name-attr' => true));
  }

  public function gap_calendar_icon_handler($tag) {
    $img = sprintf('<img src="%s" width="24" height="24" />', get_stylesheet_directory_uri() . '/assets/img/date.svg');
    return $img;
  }
  public function gap_today_handler($tag) {
    return date_i18n(get_option('date_format'));
  }



  /* Calendar */
  public function gap_date_form_tag_handler($tag) {
    $tag = new \WPCF7_FormTag($tag);

    if (empty($tag->name)) {
      return '';
    }

    /* $atts = array();

    $class = wpcf7_form_controls_class($tag->type);
    $atts['class'] = $tag->get_class_option($class);
    $atts['id'] = $tag->get_id_option(); */

    $atts['name'] = $tag->name;
    $atts = wpcf7_format_atts($atts);

    $html = '<div class="wrapper">';
    $html .= '
    <header>
      <p class="current-date"></p>
      <div class="icons">
        <span id="prev"><i class="fa fa-chevron-left"></i></span>
        <span id="next"><i class="fa fa-chevron-right"></i></span>
      </div></header>
    <div class="calendar">
      <ul class="weeks">
        <li>Sun</li><li>Mon</li><li>Tue</li><li>Wed</li><li>Thu</li><li>Fri</li><li>Sat</li>
      </ul>
      <ul class="days"></ul>
    </div>
  </div>';

    return $html;
  }

  public function gap_time_form_tag_handler($tag) {
    $tag = new \WPCF7_FormTag($tag);

    if (empty($tag->name)) {
      return '';
    }

    /* $atts = array();

    $class = wpcf7_form_controls_class($tag->type);
    $atts['class'] = $tag->get_class_option($class);
    $atts['id'] = $tag->get_id_option(); */

    $atts['name'] = $tag->name;
    $atts = wpcf7_format_atts($atts);

    $img = sprintf('<img src="%s" width="24" height="24" />', get_stylesheet_directory_uri() . '/assets/img/date.svg');
    $html = '';


    //hours
    $starttime = '10:00'; // your start time
    $endtime = '20:00'; // End time
    $duration = '30'; // split by 30 mins

    $array_of_time = array();
    $start_time = strtotime($starttime); //change to strtotime
    $end_time = strtotime($endtime); //change to strtotime

    $add_mins = $duration * 60;

    while ($start_time <= $end_time) // loop between time
    {
      $array_of_time[] = date("H:i", $start_time);
      $start_time += $add_mins; // to check endtie=me
    }
    $html .= '<div class="gap_times wpcf7-form-control wpcf7-gap_calendar"><div class="row" id="scr1">';
    foreach ($array_of_time as $i => $time) {
      $html .= '<div class="col-lg-4"><label>';
      if (strtotime($time . ':00') < current_time('timestamp')) {
        $dis = 'disabled';
      } else
        $dis = '';
        
      //if($dis === 'disabled') $checked = '';

      /* if ($time === '10:30' || $time === '13:00')
        $dis = 'disabled';
      else
        $dis = ''; */
      $checked = $i == 0 ? 'checked' : '';
      $html .= sprintf('<input type="radio" name="gap_time" value="%s" %s %s>', $time, $dis, $checked);
      $html .= sprintf('<span class="time_label">%s</span>', $time);
      $html .= '</label></div>';

      if ($time === '15:30')
        $html .= '</div><div class="row" id="scr2" style="display: none">';
    }

    $html .= '</div>';

    $arrow = '<svg width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1 14.5L14 1.5L27 14.5" stroke="000" stroke-width="1" stroke-linejoin="round"/><path d="M8 19L14 13L20 19" stroke="#000" stroke-width="1" stroke-linejoin="round"/></svg>';
    $html .= sprintf('<nav class="gap_time_nav"><button type="button" class="prev">%1$s</button> <button type="button" class="next">%1$s</button></nav>', $arrow);
    $html .= '</div>';


    //gap_notes

    return $html;
  }

  //Validation
  function gap_date_validation_filter($result, $tag) {
    $gap_date = $_POST['gap_date'];
    if (empty($gap_date)) {
      $result->invalidate($tag, "Vui lòng chọn ngày");
    }
    return $result;
  }

  function gap_time_validation_filter($result, $tag) {
    $gap_time = $_POST['gap_time'];
    if (empty($gap_time)) {
      $result->invalidate($tag, "Vui lòng chọn thời gian");
    }
    return $result;
  }

}

//https://github.com/wrick17/calendar-plugin