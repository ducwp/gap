<?php
namespace GAPTheme\Kyguionline;

class Init {
  public $form_id;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->form_id = 2686;
    Insert::instance();
    new Mainpage();
  }

}