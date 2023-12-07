<?php
namespace GAPTheme\Datlichcuahang;

class Init {
  public $slot_num; //Gioi han so dat lich cho 1 thoi gian
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->slot_num = 2; //Gioi han so dat lich cho 1 thoi gian
    add_action('wp', [$this, 'gap_check_user_login']);
    Ajax::instance();
    Form::instance();
    add_action('blocksy:single:content:bottom', function () {
      if (!current_user_can('edit_pages') || !is_page('dat-lich-cua-hang'))
        return;
      ?>
      <div style="max-width: var(--normal-container-max-width); padding: 0 10px; margin: 0 auto; text-align: center">
        <button type="button" class="button BlockingBtn" data-action="gap_block_date_time">Block</button>
        <button type="button" class="button BlockingBtn" data-action="gap_unblock_date_time">UnBlock</button>
      </div>
      <?php
    });
  }

  function gap_check_user_login() {
    if (!is_page(['dat-lich-cua-hang']))
      return;

    if (!is_user_logged_in()) {
      $myaccount = get_permalink(wc_get_page_id('myaccount'));
      wp_safe_redirect($myaccount);
      exit;
    }

  }
}