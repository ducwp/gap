<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}

add_action('wp_enqueue_scripts', function () {

  $ver = '1.0.8';

  $theme_uri = get_stylesheet_directory_uri();

  #Fix Elementor Icon
  //https://giveawaypremium.vn/wp-content/plugins/elementor/assets/lib/eicons/css/elementor-icons.min.css?ver=5.23.0
  wp_enqueue_style('elementor-icons', plugins_url('/elementor/assets/lib/eicons/css/elementor-icons.min.css'), [], $ver);

  //Font awesome
  wp_enqueue_style('gap-font-awesome-4.7', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', [], $ver);

  # CSS
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', [], $ver);
  #wp_enqueue_style('gap-font','https://db.onlinewebfonts.com/c/5fd740bac1abfb826fb7e35ae963efc9?family=ABChanel+Corpo+Regular', array('parent-style'));
  wp_enqueue_style('gap', $theme_uri . '/style.css', array('ct-main-styles', 'ct-woocommerce-styles'), $ver);
  wp_enqueue_style('gap-icon', $theme_uri . '/assets/vendor/gap-icon/style.css', array('gap'), $ver);
  //wp_enqueue_style('gap-pcn', $theme_uri . '/assets/vendor/page-content-navigation/page-content-navigation.css', array('gap'));
  //wp_enqueue_style('gap-cf', $theme_uri . '/assets/css/cf.css', array('gap'));
  wp_enqueue_style('gap-flexboxgrid', $theme_uri . '/assets/vendor/flexboxgrid/flexboxgrid.css', array('gap'), $ver);
  wp_enqueue_style('gap-style', $theme_uri . '/assets/css/gap.css', array('gap'), $ver);

  # JS
  wp_enqueue_script('gap-pcn', $theme_uri . '/assets/js/page-content-navigation.js', ['jquery'], $ver, true);
  wp_enqueue_script('gap-html2pdf', $theme_uri . '/assets/js/html2pdf.bundle.min.js', [], $ver, true);
  wp_enqueue_script('gap-summary', $theme_uri . '/assets/js/summary.js', ['jquery'], $ver, true);

  //Owl slider
  wp_enqueue_style('owl-style', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.carousel.min.css', array('gap'), $ver);
  wp_enqueue_style('owl-theme-style', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.theme.default.min.css', array('owl-style'), $ver);
  wp_enqueue_script('owl-slider', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/owl.carousel.min.js', ['jquery'], $ver, true);

  //JQuery Modal
  wp_enqueue_style('gap-jquery-modal', $theme_uri . '/assets/vendor/jquery-modal/jquery.modal.min.css', array('gap'), $ver);
  wp_enqueue_script('gap-jquery-modal', $theme_uri . '/assets/vendor/jquery-modal/jquery.modal.min.js', ['jquery'], $ver, true);

  //Chosen
  wp_enqueue_style('gap-chosen', $theme_uri . '/assets/vendor/chosen/chosen.min.css', [], $ver);
  wp_enqueue_script('gap-chosen', $theme_uri . '/assets/vendor/chosen/chosen.jquery.min.js', ['jquery'], $ver, true);

  //drag-and-drop-file-upload
  wp_enqueue_style('gap-drag-and-drop-file-upload', $theme_uri . '/assets/vendor/drag-and-drop-file-upload/style.css', [], $ver);
  wp_enqueue_script('gap-drag-and-drop-file-upload', $theme_uri . '/assets/vendor/drag-and-drop-file-upload/script.js', [], $ver, true);

  //Calendar
  // wp_enqueue_style('gap-calendar', $theme_uri . '/assets/vendor/calendar/style.css');
  // wp_enqueue_style('gap-calendar-theme', $theme_uri . '/assets/vendor/calendar/theme.css', ['gap-calendar']);
  // wp_enqueue_script('gap-calendar-lib', $theme_uri . '/assets/vendor/calendar/calendar.min.js', ['jquery'], '', true);
  if (is_page(2959))
    wp_enqueue_script('gap-calendar', $theme_uri . '/assets/js/calendar.js', ['jquery'], $ver, true);

  //wp_enqueue_script('gap-formatCurrency', $theme_uri . '/assets/js/jquery.formatCurrency-1.4.0.js', ['jquery'], $ver, true);

  //SCRIPT
  wp_enqueue_script('gap-script', $theme_uri . '/assets/js/script.js', ['jquery', 'gap-chosen', 'owl-slider'], $ver, true);

  $params = array(
    'nonce' => wp_create_nonce('gap_ajax_action'),
    'ajax_url' => admin_url('admin-ajax.php'),
  );
  wp_localize_script('gap-script', 'gap', $params);
});

add_action('admin_enqueue_scripts', function () {
  $ver = '1.0.8';
  $theme_uri = get_stylesheet_directory_uri();
  wp_enqueue_style('gap', $theme_uri . '/assets/css/admin.css', [], $ver);
});

add_image_size('hero-thumb-size', 200, 200, true);

//require_once __DIR__ . '/codestar-framework/codestar-framework.php';
//https://codestarframework.com/documentation/#/
require_once(__DIR__ . '/vendor/autoload.php');
GAPTheme\Init::instance();

/* if (file_exists(__DIR__.'/plugins.txt')) {
  $file_plugins = explode("\n", file_get_contents(__DIR__ . '/plugins.txt'));
  $active_plugins = array_map(function ($item) {
    return explode('/', $item)[0];
  }, get_option('active_plugins'));
  file_put_contents(__DIR__ . '/plugins.txt', join("\n", $active_plugins));
  // if (count($file_plugins) != count($active_plugins)) {
  //   file_put_contents(__DIR__ . '/plugins.txt', join("\n", $active_plugins));
  // }
} */

//Pay with ATM cards

//Pay with international cards

function ur_replace_gravatar_image() {
  /* $img =  'https://cdnphoto.dantri.com.vn/YYg1xb6zAKgELfX9pgu-f0COgi0=/zoom/300_200/2023/12/07/filipnguyendtvndocx-1642309570948-crop-1701915844351.jpeg';
  update_user_meta(1, 'user_registration_profile_pic_url', $img); */
}

/* DISABLE UPDATES NOTIFICATIONS */
//Disable WordPress core update notification
add_filter('pre_site_transient_update_core', 'remove_core_updates');
//Disable automatic theme updates
add_filter('auto_update_theme', '__return_false');
//Disable theme update notifications
add_filter('pre_site_transient_update_themes', 'remove_core_updates');
//Disable automatic theme updates
add_filter('auto_update_theme', '__return_false');
//Disable theme update notifications
add_filter('pre_site_transient_update_themes', 'remove_core_updates');
//Disable automatic theme updates
add_filter('auto_update_theme', '__return_false');
//Disable theme update notifications
add_filter('pre_site_transient_update_themes', 'remove_core_updates');
//Disable automatic theme updates
add_filter('auto_update_theme', '__return_false');
//Disable theme update notifications
add_filter('pre_site_transient_update_themes', 'remove_core_updates');

function remove_core_updates() {
  return true;
}

add_filter('woocommerce_get_order_item_totals', 'customize_email_order_line_totals', 1000, 3);
function customize_email_order_line_totals($total_rows, $order, $tax_display) {
  // Only on emails notifications
  if (!is_wc_endpoint_url() || !is_admin()) {
    // Remove "Shipping" label text from totals rows
    $total_rows['shipping']['label'] = 'Phí giao hàng';

    $value = trim(str_replace(['qua Đồng giá HCM', 'qua Đồng giá'], '', $total_rows['shipping']['value']));
    if ($value == 'Đồng giá HCM' || $value == 'Đồng giá')
      $value = '0₫';
    $total_rows['shipping']['value'] = $value;

    unset($total_rows['cart_subtotal']);
  }

  return $total_rows;
}

//FOR TESTING
//add_action('woocommerce_cart_calculate_fees', 'bbloomer_add_cart_fee_for_states');

//function bbloomer_add_cart_fee_for_states() {
/* $noncontinental = array('ANGIANG', 'BINHDUONG'); // ARRAY OF STATE CODES
if (in_array(WC()->customer->get_shipping_state(), $noncontinental)) {
  $surcharge = 0.05 * WC()->cart->shipping_total; // 5% surcharge based on shipping cost
  $surcharge = 50000;
  WC()->cart->add_fee('Non-continental Shipping', $surcharge);
  //WC()->cart->set_shipping_total(0);
}
$state = WC()->customer->get_shipping_state();
$city = WC()->customer->get_shipping_city();

if ('HOCHIMINH' === $state) {
  $noithanh = ['760', '761', '764', '765', '766', '767', '768', '770', '771', '772', '773', '774', '775', '776', '777', '778'];
  if (in_array($city, $noithanh)) {
    $phigiaohang = 25000;
  } else {
    $phigiaohang = 30000;
  }
} else {
  $phigiaohang = 40000;
} */

//vvip
/* $user = new \GAPTheme\User;
$user_data = $user->get_user_data();
if ($user_data['level'] == 'vvip') {
  $phigiaohang = 0;
} */

//$phigiaohang = WC()->cart->shipping_total;
//WC()->cart->add_fee('Phí Zao Hàng', $phigiaohang, true, '');

//WC()->cart->add_discount('sss');
/* $percentage = 0.5;
$discount = -(WC()->cart->cart_contents_total + WC()->cart->shipping_total) * $percentage;
WC()->cart->add_fee('Discount', $discount, true, ''); */
//}

/* add_filter('woocommerce_package_rates', 'custom_shipping_costs', 10, 2);
function custom_shipping_costs($rates, $package) {
  // Loop through shipping methods rates
  foreach ($rates as $rate_key => $rate) {
    // Targeting all shipping methods except "Free shipping"
    if ('free_shipping' !== $rate->method_id) {
      $has_taxes = false;
      $taxes = [];

      $rates[$rate_key]->cost = 100; // Set to 100
      // Taxes rate cost (if enabled)
      foreach ($rates[$rate_key]->taxes as $key => $tax) {
        if ($tax > 0) {
          $has_taxes = true;
          $taxes[$key] = 0; // Set to 0 (zero)
        }
      }
      if ($has_taxes)
        $rates[$rate_key]->taxes = $taxes;
    }
  }
  return $rates;
} */
