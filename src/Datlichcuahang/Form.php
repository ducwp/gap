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
    add_filter('wpcf7_validate_gap_date*', [$this, 'gap_date_validation_filter'], 20, 2);
    add_filter('wpcf7_validate_gap_time*', [$this, 'gap_time_validation_filter'], 20, 2);
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

    $html = '<div class="calendar-wrapper">';
    $html.= '';
    $html .= '
    <header>
      <p class="current-date"></p>
      <div class="icons">
        <span id="prev"><i class="fa fa-chevron-left"></i></span>
        <span id="next"><i class="fa fa-chevron-right"></i></span>
      </div></header>
    <div class="calendar wpcf7-form-control-wrap" data-name="gap_date">
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
    /* $atts['name'] = $tag->name;
    $atts = wpcf7_format_atts($atts); */
    
    $html = '<div class="gap_times wpcf7-form-control-wrap" data-name="gap_time"><div id="gap_time_ajax">';
    $html .= Ajax::instance()->gap_times(date('d/m/Y'));
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
    if (!isset($_POST['gap_date']) || empty($_POST['gap_date'])) {
      $result->invalidate($tag, "Vui lòng chọn ngày");
    }
    return $result;
  }

  function gap_time_validation_filter($result, $tag) {
    if (!isset($_POST['gap_time']) || empty($_POST['gap_time'])) {
      $result->invalidate($tag, "Vui lòng chọn thời gian");
    }

    //Kiem tra một lần nữa xem hết chỗ chưa (nhiều người gửi cùng lúc) trong lúc user điền form
    if (isset($_POST['gap_date']) && isset($_POST['gap_time'])) {
      //global $wpdb;
      //$db_table_name = $wpdb->prefix . 'gap_cf7'; // table name

      //$date_arr = explode('/', $_POST['gap_date']);
      //$date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];

      $date = Ajax::instance()->vndate_to_mysql($_POST['gap_date']);
      
      if (Ajax::instance()->is_reached($date, $_POST['gap_time'])) {
        $result->invalidate($tag, "Thời điểm bạn chọn đã hết chỗ. Vui lòng chọn một thời điểm khác.");
      }

      if (Ajax::instance()->is_blocked($date, $_POST['gap_time'])) {
        $result->invalidate($tag, "Thời điểm bạn chọn không có sẵn Vui lòng chọn một thời điểm khác.");
      }

      /* $sql_time = $_POST['gap_time'] . ':00';
      $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (form_type='offline' AND gap_date='{$date}' AND gap_time='{$sql_time}')";
      $rows = $wpdb->get_results($sql);
      $slot_num = Init::instance()->slot_num;
      if ($rows[0]->num_rows >= $slot_num) {
        $result->invalidate($tag, "Thời điểm bạn chọn đã hết chỗ. Vui lòng chọn một thời điểm khác.");
      } */
    }

    return $result;
  }

}

//https://github.com/wrick17/calendar-plugin