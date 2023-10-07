<?php

namespace GAPTheme;

class Init {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {

    Hooks::instance();
    Shortcodes::instance();
    Xemtongket\Import::instance();

    Firebase::instance();
    WooCommerce::instance();
    Kyguionline\Init::instance();
    add_action('elementor_pro/forms/actions/register', [$this, 'add_new_form_action']);
  }

  function add_new_form_action($form_actions_registrar) {
    $form_actions_registrar->register(new Xemtongket\GetData());
    $form_actions_registrar->register(new Elementor\PhuongthucKygui());
  }

}