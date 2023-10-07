<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {
  
  $theme_uri = get_stylesheet_directory_uri();

  # CSS
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  #wp_enqueue_style('gap-font','https://db.onlinewebfonts.com/c/5fd740bac1abfb826fb7e35ae963efc9?family=ABChanel+Corpo+Regular', array('parent-style'));
  wp_enqueue_style('gap', $theme_uri . '/style.css', array('ct-main-styles'));
  wp_enqueue_style('gap-icon', $theme_uri . '/assets/vendor/gap-icon/style.css', array('gap'));
  //wp_enqueue_style('gap-pcn', $theme_uri . '/assets/vendor/page-content-navigation/page-content-navigation.css', array('gap'));
  wp_enqueue_style('gap-cf', $theme_uri . '/assets/css/cf.css', array('gap'));
  wp_enqueue_style('gap-flexboxgrid', $theme_uri . '/assets/vendor/flexboxgrid/flexboxgrid.css', array('gap'));
  wp_enqueue_style('gap-style', $theme_uri . '/assets/css/gap.css', array('gap'));

  # JS
  wp_enqueue_script('gap-pcn', $theme_uri . '/assets/js/page-content-navigation.js', ['jquery'], '', true);
  wp_enqueue_script('gap-html2pdf', $theme_uri. '/assets/js/html2pdf.bundle.min.js', [], '', true);
  wp_enqueue_script('gap-summary', $theme_uri . '/assets/js/summary.js', ['jquery'], '', true);
  
  //Owl slider
  wp_enqueue_style('owl-style', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.carousel.min.css', array('gap'));
  wp_enqueue_style('owl-theme-style', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/assets/owl.theme.default.min.css', array('owl-style'));
  wp_enqueue_script('owl-slider', $theme_uri . '/assets/vendor/OwlCarousel2-2.3.4/owl.carousel.min.js', ['jquery'], '', true);

  //JQuery Modal
  wp_enqueue_style('gap-jquery-modal', $theme_uri . '/assets/vendor/jquery-modal/jquery.modal.min.css', array('gap'));
  wp_enqueue_script('gap-jquery-modal', $theme_uri . '/assets/vendor/jquery-modal/jquery.modal.min.js', ['jquery'], '', true);

  //Chosen
  wp_enqueue_style('gap-chosen', $theme_uri . '/assets/vendor/chosen/chosen.min.css');
  wp_enqueue_script('gap-chosen', $theme_uri . '/assets/vendor/chosen/chosen.jquery.min.js', ['jquery'], '', true);

  //drag-and-drop-file-upload
  wp_enqueue_style('gap-drag-and-drop-file-upload', $theme_uri . '/assets/vendor/drag-and-drop-file-upload/style.css');
  wp_enqueue_script('gap-drag-and-drop-file-upload', $theme_uri . '/assets/vendor/drag-and-drop-file-upload/script.js', [], '', true);
  

  //SCRIPT
  wp_enqueue_script('gap-script', $theme_uri . '/assets/js/script.js', ['jquery', 'gap-chosen', 'owl-slider'], '', true);
});

add_image_size('hero-thumb-size', 200, 200, true);

require_once(__DIR__ . '/vendor/autoload.php');
GAPTheme\Init::instance();