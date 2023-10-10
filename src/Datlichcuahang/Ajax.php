<?php
namespace GAPTheme\Datlichcuahang;

class Ajax {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('wp_ajax_' . 'gap_click_date', [$this, 'click_date']);
    add_action('wp_ajax_nopriv_' . 'gap_click_date', [$this, 'click_date']);

  }

  public function click_date() {
    $form_date = isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : date('d/m/Y');
    echo $this->gap_times($form_date);
    die;
    
    /* global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_cf7'; // table name

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

    $form_date = isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : date('d/m/Y');
    $date_arr = explode('/', $form_date);
    $date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];

    $slot_num = 1; //Gioi han so dat lich cho 1 thoi gian

    $html = '<div class="row" id="scr1">';
    foreach ($array_of_time as $i => $time) {
      $dis = '';
      $sql_time = $time . ':00';
      $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (form_type='offline' AND gap_date='{$date}' AND gap_time='{$sql_time}')";
      $rows = $wpdb->get_results($sql);


      if ($rows[0]->num_rows >= $slot_num)
        $dis = 'disabled';

      if (strtotime($date . ' ' . $time . ':00') < current_time('timestamp'))
        $dis = 'disabled';


      $html .= '<div class="col-lg-4"><label>';


      $html .= sprintf('<input type="radio" name="gap_time" value="%s" %s >', $time, $dis);
      $html .= sprintf('<span class="time_label">%s</span>', $time);
      $html .= '</label></div>';

      if ($time === '15:30')
        $html .= '</div><div class="row" id="scr2" style="display: none">';
    }
    $html .= '</div>';

    echo $html; */
    //die;
  }

  public function gap_times($vn_date) {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_cf7'; // table name

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
    
    $date_arr = explode('/', $vn_date);
    $date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];

    $slot_num = 1; //Gioi han so dat lich cho 1 thoi gian

    $html = '<div class="row" id="scr1">';
    foreach ($array_of_time as $i => $time) {
      $dis = '';
      $sql_time = $time . ':00';
      $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (form_type='offline' AND gap_date='{$date}' AND gap_time='{$sql_time}')";
      $rows = $wpdb->get_results($sql);


      if ($rows[0]->num_rows >= $slot_num)
        $dis = 'disabled';

      if (strtotime($date . ' ' . $time . ':00') < current_time('timestamp'))
        $dis = 'disabled';


      $html .= '<div class="col-lg-4"><label>';


      $html .= sprintf('<input type="radio" name="gap_time" value="%s" %s >', $time, $dis);
      $html .= sprintf('<span class="time_label">%s</span>', $time);
      $html .= '</label></div>';

      if ($time === '15:30')
        $html .= '</div><div class="row" id="scr2" style="display: none">';
    }
    $html .= '</div>';

    return $html;
  }
}