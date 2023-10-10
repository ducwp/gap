<?php
namespace GAPTheme\Cf7db;

if (!defined('ABSPATH'))
  exit;

/**
 * Cfdb7_Wp_List_Table class will create the page to load the table
 */
class Mainpage {
  /**
   * Constructor will create the menu item
   */
  public function __construct() {
    add_action('admin_menu', array($this, 'admin_list_table_page'));
  }


  /**
   * Menu item will allow us to load the page to display the table
   */
  public function admin_list_table_page() {
    wp_enqueue_style('gap-flexboxgrid', get_stylesheet_directory_uri() . '/assets/vendor/flexboxgrid/flexboxgrid.min.css');
    #wp_enqueue_style('cfdb7-admin-style', plugin_dir_url(dirname(__FILE__)) . 'css/admin-style.css');

    // Fallback: Make sure admin always has access
    $cap = 'manage_options';

    //add_menu_page( __( 'Contact Forms', 'contact-form-cfdb7' ), __( 'Contact Forms', 'contact-form-cfdb7' ), $cfdb7_cap, 'cfdb7-list.php', array($this, 'list_table_page'), 'dashicons-list-view' );
    add_menu_page(__('Ký gửi', 'gap-theme'), __('Ký gửi', 'gap-theme'), $cap, 'gap-ky-gui', array($this, 'list_table_page'), 'dashicons-list-view');
  }
  /**
   * Display the list table page
   *
   * @return Void
   */
  public function list_table_page() {
    if (!class_exists('WPCF7_ContactForm')) {

      wp_die('Please activate <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">contact form 7</a> plugin.');
    }

    $fid = empty($_GET['fid']) ? 0 : (int) $_GET['fid'];
    $ufid = empty($_GET['ufid']) ? 0 : (int) $_GET['ufid'];

    if (!empty($fid) && empty($_GET['ufid'])) {

      new Subpage();
      return;
    }

    if (!empty($ufid) && !empty($fid)) {

      new Details();
      return;
    }

    $ListTable = new Main_List_Table();
    $ListTable->prepare_items();
    ?>
    <div class="wrap">
      <div id="icon-users" class="icon32"></div>
      <h2><?php _e( 'Contact Forms List', 'contact-form-cfdb7' ); ?></h2>
      <?php $ListTable->display(); ?>
    </div>
    <?php
  }

}
// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}