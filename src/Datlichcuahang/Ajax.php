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
    add_action('wp_ajax_' . 'gap_block_date_time', [$this, 'block_date_time']);
    add_action('wp_ajax_nopriv_' . 'gap_block_date_time', [$this, 'block_date_time']);
    add_action('wp_ajax_' . 'gap_unblock_date_time', [$this, 'unblock_date_time']);
    add_action('wp_ajax_nopriv_' . 'gap_unblock_date_time', [$this, 'unblock_date_time']);
  }

  public function click_date() {
    $form_date = isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : date('d/m/Y');
    echo $this->gap_times($form_date);
    die;
  }

  public function gap_times($vn_date) {
    global $wpdb;
    //$db_table_name = $wpdb->prefix . 'gap_cf7'; // table name
    $date = $this->vndate_to_mysql($vn_date);
    //$slot_num = Init::instance()->slot_num;

    $html = '<div class="row" id="scr1">';
    $array_of_time = $this->array_of_time();
    foreach ($array_of_time as $i => $time) {
      $dis = '';
      /* $sql_time = $time . ':00';
      $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (form_type='offline' AND gap_date='{$date}' AND gap_time='{$sql_time}')";
      $rows = $wpdb->get_results($sql);

      if ($rows[0]->num_rows >= $slot_num)
        $dis = 'disabled'; */

      if ($this->is_reached($date, $time))
        $dis = 'disabled';

      $label_class = '';

      $date_time = $date . ' ' . $time . ':00';

      if (strtotime($date_time) < current_time('timestamp')) {
        $dis = 'disabled';
        $label_class = 'pass';
      }

      if ($this->is_blocked($date, $time)) {
        if (!current_user_can('manage_options'))
          $dis = 'disabled';
        else
          $label_class .= ' blocked_by_admin';
      }

      $html .= '<div class="col-xs-12 col-md-6 col-lg-4"><label>';
      $html .= sprintf('<input type="radio" name="gap_time" value="%s" %s >', $time, $dis);
      $html .= sprintf('<span class="time_label %s">%s</span>', $label_class, $time);
      $html .= '</label></div>';

      if ($time === '15:30')
        $html .= '</div><div class="row" id="scr2" style="display: none">';
    }
    $html .= '</div>';

    return $html;
  }

  public function array_of_time() {
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

    return $array_of_time;
  }

  public function is_reached($date, $time) {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_cf7'; // table name
    $sql_time = $time . ':00';
    $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (form_type='offline' AND gap_date='{$date}' AND gap_time='{$sql_time}')";
    $rows = $wpdb->get_results($sql);
    $slot_num = Init::instance()->slot_num;
    if ($rows[0]->num_rows >= $slot_num)
      return true;

    return false;
  }

  public function block_date_time() {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_blockings'; // table name

    $vn_date = $_POST['date'];
    $date_arr = explode('/', $vn_date);
    $date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];
    $time = $_POST['time'] . ':00';
    $block_time = current_time('Y-m-d H:i:s');
    $data = array(
      'gap_date' => $date,
      'gap_time' => $time,
      'block_time' => $block_time
    );
    $wpdb->insert($db_table_name, $data);
    die;
  }

  public function unblock_date_time() {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_blockings'; // table name

    $vn_date = $_POST['date'];
    $date_arr = explode('/', $vn_date);
    $date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];
    $time = $_POST['time'] . ':00';
    $data = array(
      'gap_date' => $date,
      'gap_time' => $time
    );
    $wpdb->delete($db_table_name, $data);

    die;
  }

  /* public function blocking_times() {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_blockings'; // table name
    $sql = "SELECT * FROM  $db_table_name";
    $rows = $wpdb->get_results($sql);
    $time_block = [];
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $time_block[] = $row->gap_date . ' ' . $row->gap_time;
      }
    }
    return $time_block;
  } */

  public function is_blocked($date, $time) {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_blockings'; // table name

    $sql_time = $time . ':00';
    $sql = "SELECT COUNT(*) as num_rows FROM  $db_table_name WHERE (gap_date='{$date}' AND gap_time='{$sql_time}')";
    $rows = $wpdb->get_results($sql);

    if ($rows[0]->num_rows > 0)
      return true;

    return false;
  }

  public function vndate_to_mysql($vn_date) {
    $date_arr = explode('/', $vn_date);
    return $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];
  }
}