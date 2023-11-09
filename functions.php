<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}

add_action('wp_enqueue_scripts', function () {

  $ver = '1.0.7';

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
  if(is_page()) wp_enqueue_script('gap-calendar', $theme_uri . '/assets/js/calendar.js', ['jquery'], $ver, true);

  //SCRIPT
  wp_enqueue_script('gap-script', $theme_uri . '/assets/js/script.js', ['jquery', 'gap-chosen', 'owl-slider'], $ver, true);

  $params = array(
    'nonce' => wp_create_nonce('gap_ajax_action'),
    'ajax_url' => admin_url('admin-ajax.php'),
  );
  wp_localize_script('gap-script', 'gap', $params);
});

add_action('admin_enqueue_scripts', function () {
  $ver = '1.0.6';
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