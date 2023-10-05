<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {
  # CSS
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  #wp_enqueue_style('gap-font','https://db.onlinewebfonts.com/c/5fd740bac1abfb826fb7e35ae963efc9?family=ABChanel+Corpo+Regular', array('parent-style'));
  wp_enqueue_style('gap', get_stylesheet_directory_uri() . '/style.css', array('ct-main-styles'));
  wp_enqueue_style('owl-style', get_stylesheet_directory_uri() . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.carousel.min.css', array('gap'));
  wp_enqueue_style('owl-theme-style', get_stylesheet_directory_uri() . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.theme.default.min.css', array('owl-style'));
  wp_enqueue_style('gap-icon', get_stylesheet_directory_uri() . '/assets/vendor/gap-icon/style.css', array('gap'));
  wp_enqueue_style('gap-pcn', get_stylesheet_directory_uri() . '/assets/vendor/page-content-navigation/page-content-navigation.css', array('gap'));
  wp_enqueue_style('gap-jquery-modal', get_stylesheet_directory_uri() . '/assets/vendor/jquery-modal/jquery.modal.min.css', array('gap'));
  wp_enqueue_style('gap-cf', get_stylesheet_directory_uri() . '/assets/css/cf.css', array('gap'));
  wp_enqueue_style('gap-flexboxgrid', get_stylesheet_directory_uri() . '/assets/css/flexboxgrid.css', array('gap'));
  wp_enqueue_style('gap-drag-and-drop-file-upload', get_stylesheet_directory_uri() . '/assets/vendor/drag-and-drop-file-upload/style.css', array('gap'));
  wp_enqueue_style('gap-style', get_stylesheet_directory_uri() . '/assets/css/gap.css', array('gap'));

  # JS
  wp_enqueue_script('gap-drag-and-drop-file-upload', get_stylesheet_directory_uri() . '/assets/vendor/drag-and-drop-file-upload/script.js', ['jquery'], '', true);
  wp_enqueue_script('gap-jquery-modal', get_stylesheet_directory_uri() . '/assets/vendor/jquery-modal/jquery.modal.min.js', ['jquery'], '', true);
  wp_enqueue_script('gap-pcn', get_stylesheet_directory_uri() . '/assets/vendor/page-content-navigation/page-content-navigation.js', ['jquery'], '', true);
  wp_enqueue_script('owl-slider', get_stylesheet_directory_uri() . '/assets/vendor/OwlCarousel2-2.3.4/owl.carousel.min.js', ['jquery'], '', true);
  wp_enqueue_script('gap-script', get_stylesheet_directory_uri() . '/assets/js/script.js', ['jquery', 'owl-slider'], '', true);
  wp_enqueue_script('gap-summary', get_stylesheet_directory_uri() . '/assets/js/summary.js', ['jquery'], '', true);
});

add_image_size('hero-thumb-size', 200, 200, true);

require_once(__DIR__ . '/vendor/autoload.php');
GAPTheme\Init::instance();
