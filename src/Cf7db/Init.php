<?php
namespace GAPTheme\Cf7db;

class Init {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    Insert::instance();
    new Mainpage();
  }
}