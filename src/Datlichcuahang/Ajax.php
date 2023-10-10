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
    //echo $_POST['date'];
   // echo "AAA";
    
    wp_send_json_success("xxx");
    die;
  }
}