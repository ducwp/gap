<?php
namespace GAPTheme\Kyguionline;

class Form {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_filter('wpcf7_validate_number', [$this, 'form_validation'], 20, 2);
    add_filter("wpcf7_form_tag", [$this, 'modify_brands'], 10, 2);
  }

  function form_validation($result, $tag) {
    /* if ('your-email-confirm' == $tag->name) {
      $your_email = isset($_POST['your-email']) ? trim($_POST['your-email']) : '';
      $your_email_confirm = isset($_POST['your-email-confirm']) ? trim($_POST['your-email-confirm']) : '';

      if ($your_email != $your_email_confirm) {
        $result->invalidate($tag, "Are you sure this is the correct address?");
      }
    } */

    $clothes_new_local = absint($_POST['clothes_new_local']);
    $clothes_new_global = absint($_POST['clothes_new_global']);
    $clothes_used_local = absint($_POST['clothes_used_local']);
    $clothes_used_global = absint($_POST['clothes_used_global']);

    if ($clothes_new_local + $clothes_new_global + $clothes_used_local + $clothes_used_global < 5) {
      $tag = new \WPCF7_Shortcode($tag);
      if (in_array($tag->name, ['clothes_new_local', 'clothes_new_global', 'clothes_used_local', 'clothes_used_global'])) { // validate name field only
        $result->invalidate($tag, "Tổng sản phẩm quần áo phải từ 5 trở lên.");
      }


    }

    return $result;
  }

  function modify_brands($scanned_tag, $replace) {

    //Shoe brands
    if (in_array($scanned_tag["name"], ['shoe_new_brand', 'shoe_used_brand'])) {
      $shoe_brands = ['Adidas', 'Nike', 'Reebox', 'Converse', 'Skechers', 'Fila', 'Puma'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($shoe_brands as $shoe_brand) {
        $scanned_tag['values'][] = $shoe_brand;
        $scanned_tag['labels'][] = $shoe_brand;
      }
    }

    //Cosmetic brands
    if (in_array($scanned_tag["name"], ['cosmetic_new_brand', 'cosmetic_used_brand'])) {
      $cosmetic_brands = ['L\'Oréal', 'Estee Lauder', 'MAC Cosmetics', 'Maybelline', 'Urban Decay', 'Lancôme', 'Clinique', 'Dior', 'Chanel', 'Clarins'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($cosmetic_brands as $cosmetic_brand) {
        $scanned_tag['values'][] = $cosmetic_brand;
        $scanned_tag['labels'][] = $cosmetic_brand;
      }
    }

    //Shoe brands
    if (in_array($scanned_tag["name"], ['perfume_new_brand', 'perfume_used_brand'])) {
      $perfume_brands = ['Dior', 'Yves Saint Laurent', 'Guerlain', 'Calvin Klein', 'Burberry', 'Bvlgari', 'Gucci'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($perfume_brands as $perfume_brand) {
        $scanned_tag['values'][] = $perfume_brand;
        $scanned_tag['labels'][] = $perfume_brand;
      }
    }

    return $scanned_tag;

  }
}