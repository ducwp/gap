<?php
namespace GAPTheme\Cf7db;

if (!defined('ABSPATH'))
  exit;

/**
 * Cfdb7_Wp_List_Table class will create the page to load the table
 */
class Subpage {
  private $form_post_id;

  /**
   * Constructor start subpage
   */
  public function __construct() {
    $this->form_post_id = (int) $_GET['fid'];
    $this->list_table_page();

  }
  /**
   * Display the list table page
   *
   * @return Void
   */
  public function list_table_page() {
    $ListTable = new List_Table();
    $ListTable->prepare_items();
    ?>
    <div class="wrap">
      <div id="icon-users" class="icon32"></div>
      <h2><?php echo get_the_title($this->form_post_id); ?></h2>
      <form method="post" action="">
        <?php $ListTable->search_box(__('Search', 'contact-form-cfdb7'), 'search'); ?>
        <?php $ListTable->display(); ?>
      </form>
    </div>
    <?php
  }

}
// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
