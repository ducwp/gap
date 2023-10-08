<?php
namespace GAPTheme\Datlichcuahang;

class CF7 {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('wpcf7_init', [$this, 'add_form_tags']);
    add_filter('wpcf7_validate_gap_calendar', [$this, 'form_validation'], 20, 2);
    add_filter('wpcf7_validate_myCustomField', [$this, 'wpcf7_myCustomField_validation_filter'], 10, 2);
    add_filter('wpcf7_validate_myCustomField*', [$this, 'wpcf7_myCustomField_validation_filter'], 10, 2);
  }

  public function add_form_tags() {
    wpcf7_add_form_tag('clock', [$this, 'clock_form_tag_handler']); // "clock" is the type of the form-tag
    wpcf7_add_form_tag('gap_calendar', [$this, 'gap_calendar']); // "clock" is the type of the form-tag
    wpcf7_add_form_tag(array('myCustomField', 'myCustomField*'), [$this, 'custom_myCustomField_form_tag_handler'], true);
  }

  function custom_myCustomField_form_tag_handler($tag) {

    $tag = new WPCF7_FormTag($tag);

    if (empty($tag->name)) {
      return '';
    }

    $validation_error = wpcf7_get_validation_error($tag->name);

    $class = wpcf7_form_controls_class($tag->type);

    if ($validation_error) {
      $class .= ' wpcf7-not-valid';
    }

    $atts = array();

    $atts['class'] = $tag->get_class_option($class);
    $atts['id'] = $tag->get_id_option();

    if ($tag->is_required()) {
      $atts['aria-required'] = 'true';
    }

    $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

    $atts['name'] = $tag->name;

    $atts = wpcf7_format_atts($atts);

    $myCustomField = '';

    $query = new WP_Query(array(
      'post_type' => 'CUSTOM POST TYPE HERE',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
    ));

    while ($query->have_posts()) {
      $query->the_post();
      $post_title = get_the_title();
      $myCustomField .= sprintf('<option value="%1$s">%1$s</option>',
        esc_html($post_title));
    }

    wp_reset_query();

    $myCustomField = sprintf(
      '<span class="wpcf7-form-control-wrap %1$s"><select %2$s>%3$s</select>%4$s</span>',
      sanitize_html_class($tag->name),
      $atts,
      $myCustomField,
      $validation_error
    );

    return $myCustomField;
  }

  public function clock_form_tag_handler($tag) {
    return date_i18n(get_option('date_format') . ' - ' . get_option('time_format'));
  }

  public function gap_calendar() {
    $html = '<div class="gap_booking"><div class="gb_header"><div class="row">';
    $html .= '<div class="col-lg-6">A</div>';
    $html .= sprintf('<div class="col-lg-6">Hôm nay: %s</div>', date_i18n(get_option('date_format')));
    $html .= '</div></div>';

    $html .= '<div class="row">';
    $html .= '<div class="col-lg-6"><div id="calendar-wrapper" class="calendar-wrapper"></div></div>';

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
    $html .= '<div class="col-lg-6"><div class="gap_times"><div class="row" class="scr1">';
    foreach ($array_of_time as $time) {
      $html .= '<div class="col-lg-4"><label>';
      if ($time === '10:30' || $time === '13:00')
        $dis = 'disabled';
      else
        $dis = '';
      $html .= sprintf('<input type="radio" name="gap_time" value="%s" %s>', $time, $dis);
      $html .= sprintf('<span class="time_label">%s</span>', $time);
      $html .= '</label></div>';

      if ($time === '15:30')
        $html .= '</div><div class="row" class="scr2" style="display: none">';
    }
    $html .= '</div></div>';
    $html .= '<div class="gap_notes">';
    $html .= '<span class="gap_note">Đã hết chỗ</span>';
    $html .= '<span class="gap_note gn_available">Còn trống</span>';
    $html .= '<span class="gap_note gn_selected">Đang chọn</span>';
    $html .= '</div>';
    $html .= '</div></div></div>';
    //gap_notes

    return $html;
  }

  function form_validation($result, $tag) {
    /* if ('your-email-confirm' == $tag->name) {
      $your_email = isset($_POST['your-email']) ? trim($_POST['your-email']) : '';
      $your_email_confirm = isset($_POST['your-email-confirm']) ? trim($_POST['your-email-confirm']) : '';

      if ($your_email != $your_email_confirm) {
        $result->invalidate($tag, "Are you sure this is the correct address?");
      }
    } */

    file_put_contents("D:/_POST.txt", json_encode($_POST));

    $gap_time = absint($_POST['gap_time']);
    if (empty($gap_time)) {
      $result->invalidate($tag, "Vui lòng chọn thời gian");
    }

    $result->invalidate($tag, "Vui lòng chọn thời gian");

    /*  if ($clothes_new_local + $clothes_new_global + $clothes_used_local + $clothes_used_global < 5) {
       $tag = new \WPCF7_Shortcode($tag);
       if (in_array($tag->name, ['clothes_new_local', 'clothes_new_global', 'clothes_used_local', 'clothes_used_global'])) { // validate name field only
         $result->invalidate($tag, "Tổng sản phẩm quần áo phải từ 5 trở lên.");
       }
     } */

    return $result;
  }

  function wpcf7_myCustomField_validation_filter($result, $tag) {
    $tag = new \WPCF7_FormTag($tag);

    $name = $tag->name;

    if (isset($_POST[$name]) && is_array($_POST[$name])) {
      foreach ($_POST[$name] as $key => $value) {
        if ('' === $value) {
          unset($_POST[$name][$key]);
        }
      }
    }

    $empty = !isset($_POST[$name]) || empty($_POST[$name]) && '0' !== $_POST[$name];

    if ($tag->is_required() && $empty) {
      $result->invalidate($tag, wpcf7_get_message('invalid_required'));
    }

    return $result;
  }

}

//https://github.com/wrick17/calendar-plugin