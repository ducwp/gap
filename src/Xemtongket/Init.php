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
    add_action('elementor_pro/forms/actions/register', function ($form_actions_registrar) {
      $form_actions_registrar->register(new GetData());
    });
  }
}