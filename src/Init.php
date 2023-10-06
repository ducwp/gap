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

    //do_action( 'wpcf7_contact_form', $contact_form, $atts );
    //add_action('wpcf7_shortcode_callback', [$this, 'wpcf7_shortcode_callback'], 99, 2);

   
   /* add_filter( 'wpcf7_contact_form_default_pack',
			function($contact_form, $args){
//print_r($contact_form);
$contact_form = '';
return $contact_form;
      }, 10, 2);*/
   
  } 

  function wpcf7_shortcode_callback($contact_form, $atts) {

    print_r($atts);

    if ($atts['id'] == '36f8be0')
      $contact_form = '[tel* abc]';
    return $contact_form;

  }

  function add_new_form_action($form_actions_registrar) {

    $form_actions_registrar->register(new Elementor\FormSummary());
    $form_actions_registrar->register(new Elementor\FormPTKG());

  }

}