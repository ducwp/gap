<?php
namespace GAPTheme;

class User {
  public $current_user;
  public $level_1 = 500;
  public $level_2 = 1000;

  public function __construct() {
    $this->current_user = wp_get_current_user();
  }

  public function get_user_point() {
    return absint(get_user_meta($this->current_user->ID, '_nrp_points', true));
  }

  public function get_user_data() {
    $point = $this->get_user_point();
    $data = [];

    $data['name'] = $this->current_user->display_name;
    $data['point'] = $point;

    global $wpdb;
    $table_name = $wpdb->prefix . 'nrp_accounts';
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE customer_id = %d LIMIT 1",
      array($this->current_user->ID)
    ));
    //var_dump($row);
    $data['level'] = $row !== NULL && isset($row->level) ? $row->level : 'member';

    switch ($data['level']) {
      case 'member':
        $data['icon'] = 'member.svg';
        $data['label'] = 'Member';
        break;
      case 'vip':
        $data['icon'] = 'vip.svg';
        $data['label'] = 'Vip';
        break;
      case 'vvip':
        $data['icon'] = 'vvip.svg';
        $data['label'] = 'VVip';
        break;
    }

    return $data;
  }
}