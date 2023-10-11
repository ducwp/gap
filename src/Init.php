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
    Firebase::instance();
    WooCommerce::instance();
    Cf7db\Init::instance();
    Phuongthuckygui\Init::instance();
    Datlichcuahang\Init::instance();
    //Blocking::instance();
    Kyguionline\Init::instance();
    Xemtongket\Init::instance();
  }
}