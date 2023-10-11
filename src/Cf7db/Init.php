<?php
namespace GAPTheme\Cf7db;

class Init {
  public $form_test_id;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->form_test_id = 2119;
    Insert::instance();
    new Mainpage();
  }
}