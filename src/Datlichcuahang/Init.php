<?php
namespace GAPTheme\Datlichcuahang;

class Init {
  public $slot_num; //Gioi han so dat lich cho 1 thoi gian
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->slot_num = 2; //Gioi han so dat lich cho 1 thoi gian
    add_action('wp', [$this, 'gap_check_user_login']);
    Ajax::instance();
    Form::instance();
  }

  function gap_check_user_login() {
    if (!is_page(['dat-lich-cua-hang']))
      return;

    if (!is_user_logged_in()) {
      $myaccount = get_permalink(wc_get_page_id('myaccount'));
      wp_safe_redirect($myaccount);
      exit;
    }

  }
}