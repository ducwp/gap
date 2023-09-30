<?php
if (!defined('WP_DEBUG')) {
  die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

  #wp_enqueue_style('gap-font','https://db.onlinewebfonts.com/c/5fd740bac1abfb826fb7e35ae963efc9?family=ABChanel+Corpo+Regular', array('parent-style'));
  wp_enqueue_style('gap-style', get_stylesheet_directory_uri() . '/style.css', array('ct-main-styles'));
});


/* Shortcodes */
// Extended subscription function with subscription type variable
function footer_item_shortcode($atts)
{
  extract(shortcode_atts(
    array(
      'icon' => 'map',
      'title' => 'Địa chỉ',
      'content' => '69 Nguyễn Trọng Tuyển, P.15, Q. Phú Nhuận, TP. HCM'
    ), $atts, 'multilink'));

  $map = file_get_contents(__DIR__ . "/img/location.svg");
  $phone = file_get_contents(__DIR__ . "/img/phone.svg");
  $mail = file_get_contents(__DIR__ . "/img/mail.svg");

  $html = '<div class="footer-item">';
  $html .= sprintf('<span class="footer-item-icon">%s</span>', $$icon);
  $html .= sprintf('<div><span class="footer-item-title">%s</span>', $title);
  $html .= sprintf('<span class="footer-item-content">%s</span></div>', $content);
  $html .= '</div>';

  return $html;

}
add_shortcode('footer_item', 'footer_item_shortcode');

add_shortcode('footer_tel_mail', function () {
  $html = '<div class="footer-item">';
  $html .= do_shortcode('[footer_item icon="phone" title="Tel" content="0772066969"][footer_item icon="mail" title="Send us Email" content="info@giveawaypremium.vn"]');
  $html .= '</div>';
  return $html;
});

add_action('blocksy:hero:after', 'bc', 99);
function bc()
{
  $single_hero_element = new stdClass;
  $breadcrumbs_builder = new Blocksy_Breadcrumbs_Builder();

  echo '<div style="
  width: var(--normal-container-max-width);
  margin: 0 auto;
  padding-top: 30px;
">';
  $bc = $breadcrumbs_builder->render([
    'class' => blocksy_visibility_classes(
      blocksy_akg(
        'breadcrumbs_visibility',
        $single_hero_element,
        [
          'desktop' => true,
          'tablet' => true,
          'mobile' => true,
        ]
      )
    )
  ]);
  echo str_replace('Trang chủ', '<i class="home-icon"></i>', $bc);
  echo '</div>';
}