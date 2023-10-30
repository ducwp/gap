<?php
namespace GAPTheme;

class Taxonomy {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('product_cat_add_form_fields', [$this, 'add_term_fields']);
    add_action('product_cat_edit_form_fields', [$this, 'edit_term_fields'], 10, 2);
    add_action('created_product_cat', [$this, 'save_term_fields']);
    add_action('edited_product_cat', [$this, 'save_term_fields']);
  }

  function add_term_fields($taxonomy) {
    ?>
    <div class="form-field">
      <label for="vip_cat">Chuyên mục VIP</label>
      <input name="vip_cat" type="hidden" value="0" />
        <label><input name="vip_cat" id="vip_cat" type="checkbox" value="1" /> Chuyên mục
          VIP</label>
      <!-- <p>Field description may go here.</p> -->
    </div>
    <?php
  }

  function edit_term_fields($term, $taxonomy) {

    // get meta data value
    $vip_cat = get_term_meta($term->term_id, 'vip_cat', true);
    var_dump($vip_cat);
    ?><tr class="form-field">
      <th><label for="vip_cat">Chuyên mục VIP</label></th>
      <td>
        <input name="vip_cat" type="hidden" value="0" />
        <label><input name="vip_cat" id="vip_cat" type="checkbox" value="1" <?php checked('1', $vip_cat) ?> /> Chuyên mục
          VIP</label>
        <!-- <p class="description">Field description may go here.</p> -->
      </td>
    </tr>
    <?php
  }

  function save_term_fields($term_id) {

    update_term_meta(
      $term_id,
      'vip_cat',
      sanitize_text_field($_POST['vip_cat'])
    );
  }
}