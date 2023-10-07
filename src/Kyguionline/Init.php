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

    $csv = new Export_CSV();
    if (isset($_REQUEST['csv']) && ($_REQUEST['csv'] == true) && isset($_REQUEST['nonce'])) {

      $nonce = $_REQUEST['nonce'];

      if (!wp_verify_nonce($nonce, 'dnonce'))
        wp_die('Invalid nonce..!!');

      $csv->download_csv_file($this->form_id);
    }
  }

}