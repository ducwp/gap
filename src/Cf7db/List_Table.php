<?php
namespace GAPTheme\Cf7db;

class List_Table extends \WP_List_Table {
  private $form_post_id;
  private $column_titles;

  public function __construct() {

    parent::__construct(
      array(
        'singular' => 'contact_form',
        'plural' => 'contact_forms',
        'ajax' => false
      )
    );

  }

  /**
   * Prepare the items for the table to process
   *
   * @return Void
   */
  public function prepare_items() {
    $this->form_post_id = (int) $_GET['fid'];

    $search = empty($_REQUEST['s']) ? false : esc_sql($_REQUEST['s']);
    $form_post_id = $this->form_post_id;

    global $wpdb;

    $this->process_bulk_action();

    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = $this->table_data();

    //usort( $data, array( &$this, 'sort_data' ) );

    $perPage = 100;
    $currentPage = $this->get_pagenum();
    if (!empty($search)) {

      $totalItems = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_value LIKE '%$search%' AND form_post_id = '$form_post_id' ");
    } else {

      $totalItems = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = '$form_post_id'");
    }

    $this->set_pagination_args(array(
      'total_items' => $totalItems,
      'per_page' => $perPage
    ));
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
  }
  /**
   * Override the parent columns method. Defines the columns to use in your listing table
   *
   * @return Array
   */
  public function get_columns() {
    $form_post_id = $this->form_post_id;

    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $results = $cfdb->get_results("
            SELECT * FROM $table_name 
            WHERE form_post_id = $form_post_id ORDER BY form_id DESC LIMIT 1", OBJECT
    );

    $first_row = isset($results[0]) ? unserialize($results[0]->form_value) : 0;
    $columns = array();
    $rm_underscore = apply_filters('remove_underscore_data', true);

    if (!empty($first_row)) {
      //$columns['form_id'] = $results[0]->form_id;
      $columns['cb'] = '<input type="checkbox" />';
      foreach ($first_row as $key => $value) {

        $matches = array();
        $key = esc_html($key);

        if ($key == 'gap_status')
          continue;

        if ($rm_underscore)
          preg_match('/^_.*$/m', $key, $matches);
        if (!empty($matches[0]))
          continue;

        $key_val = str_replace(array('your-', 'cfdb7_file'), '', $key);
        $key_val = str_replace(array('_', '-'), ' ', $key_val);
        $columns[$key] = ucwords($key_val);

        $this->column_titles[] = $key_val;

        if (sizeof($columns) > 4)
          break;
      }
      $columns['form-date'] = 'Date';
    }


    return $columns;
  }
  /**
   * Define check box for bulk action (each row)
   * @param  $item
   * @return checkbox
   */
  public function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="%1$s[]" value="%2$s" />',
      $this->_args['singular'],
      $item['form_id']
    );
  }
  /**
   * Define which columns are hidden
   *
   * @return Array
   */
  public function get_hidden_columns() {
    return array('form_id');
  }
  /**
   * Define the sortable columns
   *
   * @return Array
   */
  public function get_sortable_columns() {
    return array('form-date' => array('form-date', true));
  }
  /**
   * Define bulk action
   * @return Array
   */
  public function get_bulk_actions() {

    return array(
      'read' => __('Read', 'contact-form-cfdb7'),
      'unread' => __('Unread', 'contact-form-cfdb7'),
      'delete' => __('Delete', 'contact-form-cfdb7')
    );

  }
  /**
   * Get the table data
   *
   * @return Array
   */
  private function table_data() {
    $data = array();
    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $search = empty($_REQUEST['s']) ? false : esc_sql($_REQUEST['s']);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $page = $this->get_pagenum();
    $page = $page - 1;
    $start = $page * 100;
    $form_post_id = $this->form_post_id;

    $orderby = isset($_GET['orderby']) ? 'form_date' : 'form_id';
    $order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

    if (!empty($search)) {

      $results = $cfdb->get_results("SELECT * FROM $table_name 
                        WHERE  form_value LIKE '%$search%'
                        AND form_post_id = '$form_post_id'
                        ORDER BY $orderby $order
                        LIMIT $start,100", OBJECT
      );
    } else {

      $results = $cfdb->get_results("SELECT * FROM $table_name 
                        WHERE form_post_id = $form_post_id
                        ORDER BY $orderby $order
                        LIMIT $start,100", OBJECT
      );
    }

    foreach ($results as $result) {

      $form_value = unserialize($result->form_value);
      $ftype = isset($form_value['form_type']) ? $form_value['form_type'] : 'other';

      $link_inner = "<a href=admin.php?page=gap-ky-gui&fid=%s&ufid=%s&ftype=".$ftype.">%s</a>";
      $link = "<b>{$link_inner}</b>";
      if (isset($form_value['gap_status']) && ($form_value['gap_status'] === 'read'))
        $link = $link_inner;


      $fid = $result->form_post_id;
      $form_values['form_id'] = $result->form_id;

      foreach ($this->column_titles as $col_title) {
        $form_value[$col_title] = isset($form_value[$col_title]) ?
          $form_value[$col_title] : '';
      }

      foreach ($form_value as $k => $value) {

        $ktmp = $k;

        $can_foreach = is_array($value) || is_object($value);

        if ($can_foreach) {

          foreach ($value as $k_val => $val):
            $val = esc_html($val);
            $form_values[$ktmp] = (strlen($val) > 150) ? substr($val, 0, 150) . '...' : $val;
            $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);

          endforeach;
        } else {
          $value = esc_html($value);
          $form_values[$ktmp] = (strlen($value) > 150) ? substr($value, 0, 150) . '...' : $value;
          $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
        }

      }
      $form_values['form-date'] = sprintf($link, $fid, $result->form_id, $result->form_date);
      $data[] = $form_values;
    }

    return $data;
  }
  /**
   * Define bulk action
   *
   */
  public function process_bulk_action() {

    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $action = $this->current_action();

    if (!empty($action)) {

      $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
      $nonce_action = 'bulk-' . $this->_args['plural'];

      if (!wp_verify_nonce($nonce, $nonce_action)) {

        wp_die('Not valid..!!');
      }
    }

    $form_ids = isset($_POST['contact_form']) ? $_POST['contact_form'] : array();


    if ('delete' === $action) {

      foreach ($form_ids as $form_id):

        $form_id = (int) $form_id;
        $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT);
        $result_value = $results[0]->form_value;
        $result_values = unserialize($result_value);
        $upload_dir = wp_upload_dir();
        $cfdb7_dirname = $upload_dir['basedir'] . '/cfdb7_uploads';

        foreach ($result_values as $key => $result) {

          if ((strpos($key, 'cfdb7_file') !== false) &&
            !empty($result) &&
            file_exists($cfdb7_dirname . '/' . $result)) {

            unlink($cfdb7_dirname . '/' . $result);
          }

        }

        $cfdb->delete(
          $table_name,
          array('form_id' => $form_id),
          array('%d')
        );
      endforeach;

    } else if ('read' === $action) {


      foreach ($form_ids as $form_id):

        $form_id = (int) $form_id;
        $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT);
        $result_value = $results[0]->form_value;
        $result_values = unserialize($result_value);
        $result_values['gap_status'] = 'read';
        $form_data = serialize($result_values);
        $cfdb->query(
          "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
        );

      endforeach;

    } else if ('unread' === $action) {

      foreach ($form_ids as $form_id):

        $form_id = (int) $form_id;
        $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT);
        $result_value = $results[0]->form_value;
        $result_values = unserialize($result_value);
        $result_values['gap_status'] = 'unread';
        $form_data = serialize($result_values);
        $cfdb->query(
          "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
        );
      endforeach;
    }


  }
  /**
   * Define what data to show on each column of the table
   *
   * @param  Array $item        Data
   * @param  String $column_name - Current column name
   *
   * @return Mixed
   */
  public function column_default($item, $column_name) {
    return $item[$column_name];

  }
  /**
   * Allows you to sort the data by the variables set in the $_GET
   *
   * @return Mixed
   */
  private function sort_data($a, $b) {
    // Set defaults
    $orderby = 'form_date';
    $order = 'ASC';
    // If orderby is set, use this as the sort column
    if (!empty($_GET['orderby'])) {
      $orderby = $_GET['orderby'] === 'form_id' ? 'form_id' : 'form_date';
    }
    // If order is set use this as the order
    if (!empty($_GET['order'])) {
      $order = $_GET['order'] === 'asc' ? 'ASC' : 'DESC';
    }
    $result = strcmp($a[$orderby], $b[$orderby]);
    if ($order === 'asc') {
      return $result;
    }
    return -$result;
  }
  /**
   * Display the bulk actions dropdown.
   *
   * @since 3.1.0
   * @access protected
   *
   * @param string $which The location of the bulk actions: 'top' or 'bottom'.
   *                      This is designated as optional for backward compatibility.
   */
  protected function bulk_actions($which = '') {
    if (is_null($this->_actions)) {
      $this->_actions = $this->get_bulk_actions();
      /**
       * Filters the list table Bulk Actions drop-down.
       *
       * The dynamic portion of the hook name, `$this->screen->id`, refers
       * to the ID of the current screen, usually a string.
       *
       * This filter can currently only be used to remove bulk actions.
       *
       * @since 3.5.0
       *
       * @param array $actions An array of the available bulk actions.
       */
      $this->_actions = apply_filters("bulk_actions-{$this->screen->id}", $this->_actions);
      $two = '';
    } else {
      $two = '2';
    }

    if (empty($this->_actions))
      return;

    echo '<label for="bulk-action-selector-' . esc_attr($which) . '" class="screen-reader-text">' . __('Select bulk action', 'contact-form-cfdb7') . '</label>';
    echo '<select name="action' . $two . '" id="bulk-action-selector-' . esc_attr($which) . "\">\n";
    echo '<option value="-1">' . __('Bulk Actions', 'contact-form-cfdb7') . "</option>\n";

    foreach ($this->_actions as $name => $title) {
      $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

      echo "\t" . '<option value="' . $name . '"' . $class . '>' . $title . "</option>\n";
    }

    echo "</select>\n";

    submit_button(__('Apply', 'contact-form-cfdb7'), 'action', '', false, array('id' => "doaction$two"));
    echo "\n";
    $nonce = wp_create_nonce('dnonce');

    echo "<a href='" . esc_html($_SERVER['REQUEST_URI']) . "&csv=true&nonce=" . $nonce . "' style='float:right; margin:0;' class='button'>";
    _e('Export CSV', 'contact-form-cfdb7');
    echo '</a>';
    do_action('cfdb7_after_export_button');
  }
}