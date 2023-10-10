<?php
namespace GAPTheme\Datlichcuahang;

class Init {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
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