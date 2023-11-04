<?php
namespace GAPTheme;

class Route {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_filter('query_vars', [$this, 'query_vars']);
    add_action('template_redirect', [$this, 'template_redirect'], 999);
  }

  function query_vars($query_vars) {
    $query_vars[] = 'reset_point';
    return $query_vars;
  }

  function template_redirect($template_id) {
    if (get_query_var('reset_point') == 'yes') {
      if (date('d-m') !== '1-1'){
        echo "Today is not 1-1";
        exit;
      }

      global $wpdb;

      $account_table_name = $wpdb->prefix . 'nrp_accounts';

      //Reset points
      $ok = $wpdb->query($wpdb->prepare("UPDATE $account_table_name SET `points_balance` = %s", '0'));

      //var_dump($ok);

      //Reset level
      $updated = $wpdb->query($wpdb->prepare("UPDATE $account_table_name SET `level` = `level_hold` WHERE `renew_year` = %d", date('Y')));
      var_dump($updated);
      exit;
    }

  }
}