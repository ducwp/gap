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

    CF7::instance();
    Hooks::instance();
    Shortcodes::instance();
    Summary::instance();
    add_action('elementor_pro/forms/actions/register', [$this, 'add_new_form_action']);
  }

  function add_new_form_action($form_actions_registrar) {

    $form_actions_registrar->register(new Elementor\FormSummary());
    $form_actions_registrar->register(new Elementor\FormPTKG());

  }

}