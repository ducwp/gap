<?php
namespace GAPTheme;

class User {
  public $current_user;
  public $level_1 = 1000;
  public $level_2 = 2000;

  public function __construct() {
    $this->current_user = wp_get_current_user();
  }

  public function get_user_point() {
    return absint(get_user_meta($this->current_user->ID, '_nrp_points', true));
  }

  public function get_user_data() {
    $point = $this->get_user_point();
    $data = [];
    if ($point < $this->level_1)
      $data = ['icon' => 'star.svg', 'label' => 'Hạng 0', 'level' => 0];
    elseif ($point >= $this->level_1 && $point < $this->level_2)
      $data = ['icon' => 'medal.svg', 'label' => 'Hạng 1', 'level' => 1];
    else
      $data = ['icon' => 'trophy.svg', 'label' => 'Hạng 2', 'level' => 2];

    $data['name'] = $this->current_user->display_name;
    $data['point'] = $point;
    return $data;
  }
}