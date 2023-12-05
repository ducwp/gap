<?php

namespace GAPTheme;

use stdClass;
use Blocksy_Breadcrumbs_Builder;

class Shortcodes {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_shortcode('hero_slider', [$this, 'hero_slider_shortcode']);
    /* Breadcrumbs */
    add_shortcode('gap_breadcrumbs', [$this, 'gap_breadcrumbs']);
    add_shortcode('footer_item', [$this, 'footer_item_shortcode']);

    add_shortcode('footer_tel_mail', function () {
      $html = '<div class="footer-item">';
      $html .= do_shortcode('[footer_item icon="phone" title="Tel" content="<a href=tel:0772066969>0772066969</a>"][footer_item icon="mail" title="Send us Email" content="<a href=\'mailto:info@giveawaypremium.vn\'>info@giveawaypremium.vn</a>"]', );
      $html .= '</div>';
      return $html;
    });

    add_shortcode('gap_footer_contact_icon', [$this, 'gap_footer_contact_icon']);
    add_action( 'wp_footer', [$this, 'footer_google_map'] );
  }



  /* Hero slider */

  function hero_slider_shortcode($atts) {
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
      $onepx = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/assets/img/onepx.gif');
      $html .= sprintf('<div class="item" style="background-image: url(%s)">%s</div>', $src, $onepx);
      $html_thumb .= sprintf('<div class="item">%s</div>', wp_get_attachment_image($id, 'hero-thumb-size'));
    }

    $html .= '</div>';


    // $html .= '<div class="gap-hero-slider-thumbs"><div class="gap-hero-slider-thumbs-inner">';
    // $html .= wp_get_attachment_image($title_id, 'fullsize', '', ['class' => 'hero-slider-title']);
    // $html .= '<div style="display:flex"><span class="cprev"><svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px; " d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg></span>';
    // $html .= '<div id="sync2" class="owl-carousel owl-theme">';
    // $html .= $html_thumb . '</div>';
    // $html .= '<span class="cnext"><svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px; " d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg></span>';
    // $html .= '</div></div></div></div>';

    $tips_mua_hang = sprintf('<img src="%s">', get_stylesheet_directory_uri() . '/assets/img/tips_mua_hang.svg');

    $html .= '<div class="gap-hero-slider-thumbs"><div class="gap-hero-slider-thumbs-inner">';
    $html .= '<div class="section-title">';
    $html .= sprintf('<div class="section-title-left">%s</div>', $tips_mua_hang);
    $html .= '<div class="section-title-right">SĂN HÀNG NHANH NHẤT KHI ĐẾN CỬA HÀNG</div></div>';
    //$html .= wp_get_attachment_image($title_id, 'fullsize', '', ['class' => 'hero-slider-title']);
    $html .= '<div style="padding: 0 40px; position: relative"><span class="cprev"><svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px; " d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg></span>';
    $html .= '<div id="sync2" class="owl-carousel owl-theme">';
    $html .= $html_thumb . '</div>';
    $html .= '<span class="cnext"><svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px; " d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg></span></div>';
    $html .= '</div></div></div>';

    return $html;

  }



  function gap_breadcrumbs() {
    //if(!class_exists('Blocksy_Breadcrumbs_Builder')) return;
    $single_hero_element = new stdClass;
    $breadcrumbs_builder = new Blocksy_Breadcrumbs_Builder();
    $html = $breadcrumbs_builder->render([
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
    echo '<div class="gap_breadcrumbs">'.$html.'</div>';
  }

  /* Footer */
  function footer_item_shortcode($atts) {
    $map_link = 'https://www.google.com/maps/dir//69+Nguy%E1%BB%85n+Tr%E1%BB%8Dng+Tuy%E1%BB%83n+Ph%C6%B0%E1%BB%9Dng+15+Ph%C3%BA+Nhu%E1%BA%ADn+Th%C3%A0nh+ph%E1%BB%91+H%E1%BB%93+Ch%C3%AD+Minh/@10.797195,106.679336,16z/data=!4m8!4m7!1m0!1m5!1m1!1s0x317528d6f515471f:0x5d6b04deca839deb!2m2!1d106.679336!2d10.797195?entry=ttu';
    extract(
      shortcode_atts(
        array(
          'icon' => 'map',
          'title' => 'Địa chỉ',
          'content' => '<a href="'.$map_link.'" target="_blank">69 Nguyễn Trọng Tuyển, P.15, Q. Phú Nhuận</a>'
        ),
        $atts,
        'multilink'
      )
    );

    $map = file_get_contents(dirname(__DIR__, 1) . "/assets/img/location.svg");
    $phone = file_get_contents(dirname(__DIR__, 1) . "/assets/img/phone.svg");
    $mail = file_get_contents(dirname(__DIR__, 1) . "/assets/img/mail.svg");

    $html = '<div class="footer-item">';
    $html .= sprintf('<span class="footer-item-icon">%s</span>', $$icon);
    $html .= sprintf('<div><span class="footer-item-title">%s</span>', $title);
    $html .= sprintf('<span class="footer-item-content">%s</span></div>', $content);
    $html .= '</div>';

    return $html;

  }


  function gap_footer_contact_icon() {
    $html = '<div class="gap_contact_items">';
    $html .= '<input id="triggerButton" class="triggerButton" type="checkbox">';
    $html .= '<label for="triggerButton"></label>';
    $html .= '<a class="one" href="https://m.me/giveawaypremiumphunhuan1" target="_blank"><svg id="Layer_1" enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg"><g id="_x33_4.Messenger"><path id="Icon_78_" d="m50 10c-22.1 0-40 16.6-40 37.1 0 11.7 5.8 22.1 14.9 28.9l.2 13c0 .7.8 1.1 1.4.8l12.5-7c3.5.9 7.2 1.4 11.1 1.4 22.1 0 40-16.6 40-37.1s-18.1-37.1-40.1-37.1zm2.9 49.2-8.5-8.9c-.5-.5-1.3-.6-1.9-.3l-15 8.1c-.6.3-1.1-.4-.7-.9l17.7-18.8c.5-.5 1.4-.5 1.9 0l8.5 9.1c.5.5 1.3.7 1.9.3l14.7-8c.6-.3 1.1.4.7.9l-17.4 18.5c-.5.5-1.4.5-1.9 0z"/></g></svg></a>';
    $html .= '<a class="two" href="tel:0772066969"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 513.64 513.64" style="enable-background:new 0 0 513.64 513.64" xml:space="preserve"><path d="m499.66 376.96-71.68-71.68c-25.6-25.6-69.12-15.359-79.36 17.92-7.68 23.041-33.28 35.841-56.32 30.72-51.2-12.8-120.32-79.36-133.12-133.12-7.68-23.041 7.68-48.641 30.72-56.32 33.28-10.24 43.52-53.76 17.92-79.36l-71.68-71.68c-20.48-17.92-51.2-17.92-69.12 0L18.38 62.08c-48.64 51.2 5.12 186.88 125.44 307.2s256 176.641 307.2 125.44l48.64-48.64c17.921-20.48 17.921-51.2 0-69.12z"/></svg></a>';
    $html .= '<a class="three" href="#gap_map" rel="modal:open"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><path d="M256 120c-33.091 0-60 26.909-60 60 0 33.249 26.861 60 60 60 33.733 0 60-27.255 60-60 0-33.091-26.909-60-60-60z"/><path d="M256 0C156.742 0 76 80.742 76 180c0 33.53 9.287 66.255 26.851 94.644l142.896 230.259c5.925 9.569 19.895 9.423 25.635-.205L410.63 272.212C427.211 244.424 436 212.534 436 180 436 80.742 355.258 0 256 0zm0 270.2c-50.345 0-90.2-40.979-90.2-90.2 0-49.629 40.571-90.2 90.2-90.2s90.2 40.571 90.2 90.2c0 48.787-39.319 90.2-90.2 90.2z"/></svg></a>';
    $html .= '<a class="four" href="https://zalo.me/0772066969" target="_blank"><svg width="300" height="105" viewBox="0 0 1249 439" xmlns="http://www.w3.org/2000/svg"><path d="M649.69 129.68v-23.37h70.02v328.67h-40.06c-16.49 0-29.87-13.32-29.96-29.78-.01.01-.02.01-.03.02-28.2 20.62-63.06 32.87-100.71 32.87-94.3 0-170.76-76.41-170.76-170.65S454.65 96.8 548.95 96.8c37.65 0 72.51 12.24 100.71 32.86.01.01.02.01.03.02zM360.05.62v10.65c0 19.88-2.66 36.1-15.57 55.14l-1.56 1.78c-2.82 3.2-9.44 10.71-12.59 14.78L105.57 365.08h254.48v39.94c0 16.55-13.43 29.96-29.98 29.96H.34v-18.83c0-23.07 5.73-33.35 12.97-44.07L252.92 75.51H10.33V.62h349.72zm444.58 434.36c-13.77 0-24.97-11.19-24.97-24.94V.62h74.94v434.36h-49.97zm271.56-340.24c94.95 0 171.91 76.98 171.91 171.79 0 94.9-76.96 171.88-171.91 171.88-94.96 0-171.91-76.98-171.91-171.88 0-94.81 76.95-171.79 171.91-171.79zm-527.24 273.1c55.49 0 100.46-44.94 100.46-100.4 0-55.37-44.97-100.32-100.46-100.32s-100.47 44.95-100.47 100.32c0 55.46 44.98 100.4 100.47 100.4zm527.24-.17c55.82 0 101.12-45.27 101.12-101.14 0-55.78-45.3-101.05-101.12-101.05-55.91 0-101.13 45.27-101.13 101.05 0 55.87 45.22 101.14 101.13 101.14z" fill-rule="evenodd" style="fill:#fff"/></svg></a>';
    $html .= '</div>';

    echo $html;
  }

  function footer_google_map() {
    $html = '<div id="gap_map" class="modal"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.184264921083!2d106.67714731455506!3d10.797194992307451!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528d6f515471f%3A0x5d6b04deca839deb!2zNjkgTmd1eeG7hW4gVHLhu41uZyBUdXnhu4NuLCBQaMaw4budbmcgMTUsIFBow7ogTmh14bqtbiwgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1599103515172!5m2!1svi!2s" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe></div>';
    echo $html;
  }
}