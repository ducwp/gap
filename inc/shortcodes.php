<?php

/* Hero slider */
function hero_slider_shortcode($atts)
{
  extract(
    shortcode_atts(
      array(
        'ids' => '',
        'title_id' => ''
      ),
      $atts
    )
  );

  $html = '<div class="gap-hero-slider">';
  $html .= '<div id="sync1" class="owl-carousel owl-theme">';


  $arr_ids = explode(",", $ids);
  $html_thumb = '';
  foreach ($arr_ids as $id) {
    $src = wp_get_attachment_image_url($id, 'fullsize');
    $onepx = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/img/onepx.gif');
    $html .= sprintf('<div class="item" style="background-image: url(%s)">%s</div>', $src, $onepx);
    $html_thumb .= sprintf('<div class="item">%s</div>', wp_get_attachment_image($id, 'hero-thumb-size'));
  }

  $html .= '</div>';


  $html .= '<div class="gap-hero-slider-thumbs"><div class="gap-hero-slider-thumbs-inner">';
  $html .= wp_get_attachment_image($title_id, 'fullsize', '', ['class' => 'hero-slider-title']);
  $html .= '<div id="sync2" class="owl-carousel owl-theme">';
  $html .= $html_thumb;
  $html .= '</div></div></div></div>';

  return $html;

}

add_shortcode('hero_slider', 'hero_slider_shortcode');

/* Footer */
function footer_item_shortcode($atts)
{
  extract(
    shortcode_atts(
      array(
        'icon' => 'map',
        'title' => 'Địa chỉ',
        'content' => '69 Nguyễn Trọng Tuyển, P.15, Q. Phú Nhuận, TP. HCM'
      ),
      $atts,
      'multilink'
    )
  );

  $map = file_get_contents(dirname(__DIR__, 1) . "/img/location.svg");
  $phone = file_get_contents(dirname(__DIR__, 1) . "/img/phone.svg");
  $mail = file_get_contents(dirname(__DIR__, 1) . "/img/mail.svg");

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