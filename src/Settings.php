<?php
namespace GAPTheme;

//https://codestarframework.com/documentation/#/
use CSF;

class Settings {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }


  private function __construct() {

    /* global $wpdb;

    $charsetCollate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE ppfuture_actions_args (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            cron_action_id bigint(20) UNSIGNED NOT NULL,
            post_id bigint(20) UNSIGNED NOT NULL,
            enabled tinyint(1) NOT NULL DEFAULT '0',
            scheduled_date datetime NOT NULL,
            created_at datetime NOT NULL,
            args varchar(250) NOT NULL,
            PRIMARY KEY  (id),
            KEY post_id (post_id, id),
            KEY enabled_post_id (post_id, enabled, id),
            KEY cron_action_id (cron_action_id, id),
            KEY enabled_cron_action_id (cron_action_id, enabled, id)
        ) ENGINE=InnoDB $charsetCollate;";

        echo $sql; */

    // Control core classes for avoid errors
    if (class_exists('CSF')) {

      //
      // Set a unique slug-like ID
      $prefix = 'gap_settings';

      //
      // Create options
      CSF::createOptions($prefix, array(
        'framework_title' => 'GAP <small>by marup.vn</small>',
        'menu_title' => 'GAP',
        'menu_slug' => 'gap-settings',
        'menu_capability' => 'manage_categories',
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="1632" height="1632" fill="none"><path fill="#000" fill-rule="evenodd" d="M816 1632c450.66 0 816-365.34 816-816 0-450.664-365.34-816-816-816C365.335 0 0 365.336 0 816c0 450.66 365.335 816 816 816ZM368 719.269C368 508.891 453.906 430 676.381 430h558.389c16.52 0 24.23 7.67 24.23 24.106v107.38c0 16.435-7.71 25.201-24.23 25.201H682.989c-113.44 0-146.481 32.872-146.481 149.017v157.783c0 116.143 33.041 150.113 146.481 150.113h363.451c38.55 0 55.07-17.53 55.07-66.838v-88.753c0-9.862-3.31-14.245-13.22-14.245H765.591c-15.419 0-24.23-8.765-24.23-25.201v-82.179c0-16.435 8.811-25.201 24.23-25.201h459.269c22.02 0 33.04 10.957 33.04 32.871v255.306c0 122.72-48.46 167.64-136.57 167.64H676.381C453.906 1197 368 1118.11 368 907.731V719.269Z" clip-rule="evenodd"/></svg>'),
        //'theme' => 'light',
        'footer_text' => 'marup.vn',
      ));



      CSF::createSection($prefix, array(
        'title' => __('General Settings', 'graby'),
        'fields' => array(

          //
          array(
            'id' => 'woo_account_page_dashboard',
            'type' => 'wp_editor',
            'title' => 'Add to Account Dashboard',
          ),

          array(
            'id' => 'woo_note_doi_tra',
            'type' => 'text',
            'title' => 'Lưu ý đổi trả',
            'default' => 'Lưu ý: Hàng đã mua không được đổi trả',
          ),

          array(
            'id' => 'freeshipping_gold',
            'type' => 'text',
            'title' => 'Freeshipping coupon',
            'default' => 'gap_freeshipping_gold',
            'desc' => 'Tạo coupon: ' . admin_url('edit.php?post_type=shop_coupon')
          ),

        )
      ));

      // Create a section
      CSF::createSection($prefix, array(
        'title' => __('Backup Data'),
        'fields' => array(
          array(
            'type' => 'backup',
          ),

        )
      ));


    }

  }

  function product_cats() {
    // return custom query array.
    return array(
      'opt-1' => 'Option 1',
      'opt-2' => 'Option 2',
      'opt-3' => 'Option 3',
    );
  }
}