<?php
namespace GAPTheme\Xemtongket;

class Init {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    Import::instance();
    View::instance();
    add_action('elementor_pro/forms/actions/register', function ($form_actions_registrar) {
      $form_actions_registrar->register(new GetData());
    });
    add_action('wp', function () {
      if (!is_page(['xem-tong-ket']))
        return;

      if (!is_user_logged_in()) {
        $myaccount = get_permalink(wc_get_page_id('myaccount'));
        wp_safe_redirect($myaccount);
        exit;
      }

    });
  }
}