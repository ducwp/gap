<?php
namespace GAPTheme;

class Countdown {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('add_meta_boxes', array($this, 'add_counter_metabox'));
    add_action('save_post_product', array($this, 'save_counter_meta_box'), PHP_INT_MAX, 2);

    add_action('wp_ajax_' . 'gap_countdown', [$this, 'gap_countdown_ajax']);
    add_action('wp_ajax_nopriv_' . 'gap_countdown', [$this, 'gap_countdown_ajax']);
    add_action('woocommerce_product_thumbnails', [$this, 'countdown_html']);
  }

  function add_counter_metabox() {
    add_meta_box(
      'alg-product-countdown',
      __('Product Time Countdown', 'gap-theme'),
      array($this, 'display_counter_metabox'),
      'product',
      'side',
      'high'
    );
  }

  function display_counter_metabox() {
    $the_post_id = get_the_ID();
    $is_enabled = get_post_meta($the_post_id, '_' . 'gap_product_countdown_enabled', true);
    $countdown_date = get_post_meta($the_post_id, '_' . 'gap_product_countdown_date', true);
    $countdown_time = get_post_meta($the_post_id, '_' . 'gap_product_countdown_time', true);
    $countdown_action = get_post_meta($the_post_id, '_' . 'gap_product_countdown_action', true);
    $table_data = array();

    $field_html = '';
    $field_html .= '<select name="gap_product_countdown_enabled">';
    $field_html .= '<option value="no" ' . selected('no', $is_enabled, false) . '>' .
      __('No', 'gap-theme') . '</option>';
    $field_html .= '<option value="yes" ' . selected('yes', $is_enabled, false) . '>' .
      __('Yes', 'gap-theme') . '</option>';
    $field_html .= '</select>';
    $table_data[] = array(__('Enabled', 'gap-theme'), $field_html);

    $field_html = '';
    $field_html .= '<input type="date" name="gap_product_countdown_date" value="' . $countdown_date . '">';
    $table_data[] = array(__('Date', 'gap-theme'), $field_html);

    $field_html = '';
    $field_html .= '<input type="time" name="gap_product_countdown_time" value="' . $countdown_time . '">';
    $table_data[] = array(__('Time', 'gap-theme'), $field_html);

    $action_options = apply_filters('gap_product_countdown', array(
      'do_nothing' => __('Do nothing', 'gap-theme'),
      'disable_product' => __('Disable product', 'gap-theme'),
    ), 'actions');
    $field_html = '';
    $field_html .= '<select name="gap_product_countdown_action">';
    foreach ($action_options as $option_id => $option_title) {
      $field_html .= '<option value="' . $option_id . '" ' . selected($option_id, $countdown_action, false) . '>' . $option_title . '</option>';
    }
    $field_html .= '</select>';
    $help_tip = ('' != ($help_tip = apply_filters('gap_product_countdown',
      __('Pro version also includes "Cancel sale" and "Make sold out" actions.', 'gap-theme'), 'settings')) ? wc_help_tip($help_tip, true) : '');
    //$table_data[] = array(__('Action', 'gap-theme') . $help_tip, $field_html);

    $html = '';
    $html .= $this->get_table_html($table_data, array('table_heading_type' => 'vertical', 'table_class' => 'widefat striped'));
    $html .= '<p><em>' . __('Current date and time', 'gap-theme') . ': ' . current_time('mysql') . '</em></p>';
    $html .= '<input type="hidden" name="gap_product_countdown_save_post" value="gap_product_countdown_save_post">';
    echo $html;
  }

  function save_counter_meta_box($post_id, $post) {
    // Check that we are saving with current metabox displayed.
    if (!isset($_POST['gap_product_countdown_save_post'])) {
      return;
    }
    update_post_meta($post_id, '_' . 'gap_product_countdown_enabled', $_POST['gap_product_countdown_enabled']);
    update_post_meta($post_id, '_' . 'gap_product_countdown_date', $_POST['gap_product_countdown_date']);
    update_post_meta($post_id, '_' . 'gap_product_countdown_time', $_POST['gap_product_countdown_time']);
    //update_post_meta($post_id, '_' . 'gap_product_countdown_action', $_POST['gap_product_countdown_action']);
  }

  function get_table_html($data, $args = array()) {
    $defaults = array(
      'table_class' => '',
      'table_style' => '',
      'row_styles' => '',
      'table_heading_type' => 'horizontal',
      'columns_classes' => array(),
      'columns_styles' => array(),
    );
    $args = array_merge($defaults, $args);
    $table_class = ('' == $args['table_class'] ? '' : ' class="' . $args['table_class'] . '"');
    $table_style = ('' == $args['table_style'] ? '' : ' style="' . $args['table_style'] . '"');
    $row_styles = ('' == $args['row_styles'] ? '' : ' style="' . $args['row_styles'] . '"');
    $html = '';
    $html .= '<table' . $table_class . $table_style . '>';
    $html .= '<tbody>';
    foreach ($data as $row_number => $row) {
      $html .= '<tr' . $row_styles . '>';
      foreach ($row as $column_number => $value) {
        $th_or_td = ((0 === $row_number && 'horizontal' === $args['table_heading_type']) || (0 === $column_number && 'vertical' === $args['table_heading_type']) ?
          'th' : 'td');
        $column_class = (!empty($args['columns_classes'][$column_number]) ? ' class="' . $args['columns_classes'][$column_number] . '"' : '');
        $column_style = (!empty($args['columns_styles'][$column_number]) ? ' style="' . $args['columns_styles'][$column_number] . '"' : '');
        $html .= '<' . $th_or_td . $column_class . $column_style . '>';
        $html .= $value;
        $html .= '</' . $th_or_td . '>';
      }
      $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    return $html;
  }

  function countdown_html($product) {
    $product_id = get_the_ID();
    $countdown_enabled = get_post_meta($product_id, '_' . 'gap_product_countdown_enabled', true);
    //$countdown_action = get_post_meta($product_id, '_' . 'gap_product_countdown_action', true);
    $finish_time = get_post_meta($product_id, '_' . 'gap_product_countdown_date', true) . ' ' .
      get_post_meta($product_id, '_' . 'gap_product_countdown_time', true);
    $finish_time = strtotime($finish_time);
    $current_time = (int) current_time('timestamp');
    $time_left = ($finish_time - $current_time);

    if ($countdown_enabled === 'yes' && $time_left > 0) {
      printf('<div id="open_countdown" data-product_id="%s">%s</div>', $product_id, gmdate("H:i:s", $time_left));
    }

  }

  public function gap_countdown_ajax() {

    $product_id = $_POST['product_id'];
    $countdown_enabled = get_post_meta($product_id, '_' . 'gap_product_countdown_enabled', true);
    //$countdown_action = get_post_meta($product_id, '_' . 'gap_product_countdown_action', true);
    $finish_time = get_post_meta($product_id, '_' . 'gap_product_countdown_date', true) . ' ' .
      get_post_meta($product_id, '_' . 'gap_product_countdown_time', true);
    $finish_time = strtotime($finish_time);
    $current_time = (int) current_time('timestamp');
    $time_left = ($finish_time - $current_time);

    if ($countdown_enabled === 'yes' && $time_left > 0) {
      echo gmdate("H:i:s", $time_left);
    } else
      echo 'finished';
    exit;
  }
}