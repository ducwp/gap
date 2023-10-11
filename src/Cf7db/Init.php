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
    //wpcf7_after_send_mail
    add_action('wpcf7_mail_sent', function ($cf7) {
      $form_id = $cf7->id();
      file_put_contents("D:/wpcf7_mail_sent.txt", $form_id);
      $page = get_permalink(get_page_by_path('cam-on'));
      wp_redirect($page);
    });
  }
}