<?php

namespace GAPTheme;

class Init {
  public $level_1 = 1000;
  public $level_2 = 2000;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    Settings::instance();
    Taxonomy::instance();
    Hooks::instance();
    Shortcodes::instance();
    Firebase::instance();
    WooCommerce::instance();
    DePro::instance();
    Cf7db\Init::instance();
    Phuongthuckygui\Init::instance();
    Datlichcuahang\Init::instance();
    //Blocking::instance();
    Kyguionline\Init::instance();
    Xemtongket\Init::instance();
  }

  
}