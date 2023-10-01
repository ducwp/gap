<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {
  # CSS
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  #wp_enqueue_style('gap-font','https://db.onlinewebfonts.com/c/5fd740bac1abfb826fb7e35ae963efc9?family=ABChanel+Corpo+Regular', array('parent-style'));
  wp_enqueue_style('gap', get_stylesheet_directory_uri() . '/style.css', array('ct-main-styles'));
  wp_enqueue_style('owl-style',get_stylesheet_directory_uri() . '/assets/OwlCarousel2-2.3.4/assets/owl.carousel.min.css', array('gap'));
  wp_enqueue_style('owl-theme-style', get_stylesheet_directory_uri() . '/assets/OwlCarousel2-2.3.4/assets/owl.theme.default.min.css', array('owl-style'));
  wp_enqueue_style('gap-style', get_stylesheet_directory_uri() . '/css/gap.css', array('gap'));

  # JS
  wp_enqueue_script('owl-slider', get_stylesheet_directory_uri() . '/assets/OwlCarousel2-2.3.4/owl.carousel.min.js', ['jquery'], '', true);
  wp_enqueue_script('gap-script', get_stylesheet_directory_uri() . '/js/script.js', ['jquery', 'owl-slider'], '', true);
});

add_image_size('hero-thumb-size', 200, 200, true);

require_once(__DIR__ . '/inc/shortcodes.php');
require_once(__DIR__ . '/inc/hooks.php');