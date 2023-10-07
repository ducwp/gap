<?php

namespace GAPTheme;

class Hooks {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_filter('wpcf7_autop_or_not', '__return_false');

    add_action('wp', [$this, 'gap_check_user_login']);
    add_action('blocksy:loop:before', [$this, 'gap_add_bc'], 99);
    add_action('woocommerce_before_single_product', [$this, 'gap_add_bc'], 99);
    add_action('woocommerce_archive_description', [$this, 'gap_add_bc'], 99);
    //add_action('blocksy:content:top', [$this, 'gap_add_bc'], 99);

    add_action('woocommerce_after_add_to_cart_button', [$this, 'gap_woocommerce_after_add_to_cart_button']);
    add_filter('the_title', [$this, 'gap_shorten_woo_product_title'], 10, 2);
    add_filter('woocommerce_loop_add_to_cart_link', [$this, 'gap_woocommerce_loop_add_to_cart_link_filter'], 10, 3);
    add_action('wp_footer', [$this, 'gap_page_scrolling']);
    add_action('woocommerce_register_form', [$this, 'text_domain_woo_reg_form_fields']);
    add_action('woocommerce_register_post', [$this, 'wooc_validate_extra_register_fields'], 10, 3);
    add_action('woocommerce_created_customer', [$this, 'wooc_save_extra_register_fields']);
    add_action('user_profile_update_errors', [$this, 'my_user_profile_update_errors'], 10, 3);

    add_action('user_new_form', [$this, 'my_user_new_form'], 10, 1);
    add_action('show_user_profile', [$this, 'my_user_new_form'], 10, 1);
    add_action('edit_user_profile', [$this, 'my_user_new_form'], 10, 1);

    add_action( 'wp_footer', function(){
      $current_user = wp_get_current_user();
      printf('<input type="hidden" id="current_user_billing_phone" value="%s">', $current_user->billing_phone);
    });
  }

  function gap_check_user_login() {
    if (!is_page(['ky-gui-online']))
      return;

    if (!is_user_logged_in()) {
      $myaccount = get_permalink(wc_get_page_id('myaccount'));
      wp_safe_redirect($myaccount);
      exit;
    }

  }

  /* add_action('blocksy:header:after', function () {
    if (is_front_page() || !is_page() || is_page(['ve-chung-toi', 'ky-gui', 'tai-khoan']))
      return;

    bc();

  }, 99);
   */



  function gap_add_bc() {
    /* if (!is_archive() && !is_single())
      return; */
    echo do_shortcode('[gap_breadcrumbs]');
  }



  function gap_woocommerce_after_add_to_cart_button($product) {
    global $post;
    if (is_singular('product') && !empty($post) && ($product = wc_get_product($post)) && $product->is_type('external')) {
      //print_r($product);
      //echo "AA";
      //$prod_id = get_the_ID();

      $platform = wp_get_post_terms(get_the_ID(), 'product_platform')[0];
      $logo_id = get_term_meta($platform->term_id, 'logo_id', true);

      if ($logo_id) {
        $image = wp_get_attachment_thumb_url($logo_id);
      } else {
        $image = wc_placeholder_img_src();
      }
      printf('<img src="%s" style="width: 55px;order: 0;"/>', $image);
    }
  }




  function gap_shorten_woo_product_title($title, $id) {
    if (!is_singular(array('product')) && get_post_type($id) === 'product') {
      return wp_trim_words($title, 16, '...'); // change last number to the number of words you want
    } else {
      return $title;
    }
  }



  function gap_woocommerce_loop_add_to_cart_link_filter($class, $product, $args) {

    // filter...
    /* $class = sprintf(
    '<div class="wp-block-button wc-block-components-product-button %1$s %2$s">
    <%3$s href="%4$s" class="%5$s" style="%6$s" %7$s>%8$s</%3$s>
  </div>',
    esc_attr( $text_align_styles_and_classes['class'] ?? '' ),
    esc_attr( $classname . ' ' . $custom_width_classes ),
    $html_element,
    esc_url( $product->add_to_cart_url() ),
    isset( $args['class'] ) ? esc_attr( $args['class'] ) : '',
    esc_attr( $styles_and_classes['styles'] ),
    isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
    esc_html( $product->add_to_cart_text() )
  ); */

    //print_r($product);

    #$icon = '<i class="fa fa-shopping-cart"></i>';
    $class = sprintf('<a href="%s" class="%s" %s target="_blank">%s</a>', esc_url($product->add_to_cart_url()), $args['class'], isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '', $product->add_to_cart_text());
    return $class;
  }


  function gap_page_scrolling() {
    if (!is_front_page())
      return;

    $arrow_up = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . "/assets/img/scroll/arrow_up.svg");
    $arrow_down = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . "/assets/img/scroll/arrow_down.svg");
    ?>
    <div class="gap_page_scrolling">
      <span class="ps_prev"><?php echo $arrow_up; ?></span>
      <ul class="ps_dots">
        <li class="ps_dot active"><a href="#hero_slider"><span class="ps_text">Hình ảnh cửa hàng</span>
            <svg id="_x31_" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512"
              xmlns="http://www.w3.org/2000/svg">
              <g>
                <path
                  d="m17.453 24c-.168 0-.34-.021-.51-.066l-15.463-4.141c-1.06-.292-1.692-1.39-1.414-2.45l1.951-7.272c.072-.267.346-.422.612-.354.267.071.425.346.354.612l-1.95 7.27c-.139.53.179 1.082.71 1.229l15.457 4.139c.531.14 1.079-.176 1.217-.704l.781-2.894c.072-.267.346-.426.613-.353.267.072.424.347.353.613l-.78 2.89c-.235.89-1.045 1.481-1.931 1.481z" />
              </g>
              <g>
                <path
                  d="m22 18h-16c-1.103 0-2-.897-2-2v-12c0-1.103.897-2 2-2h16c1.103 0 2 .897 2 2v12c0 1.103-.897 2-2 2zm-16-15c-.551 0-1 .449-1 1v12c0 .551.449 1 1 1h16c.551 0 1-.449 1-1v-12c0-.551-.449-1-1-1z" />
              </g>
              <g>
                <path
                  d="m9 9c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm0-3c-.551 0-1 .449-1 1s.449 1 1 1 1-.449 1-1-.449-1-1-1z" />
              </g>
              <g>
                <path
                  d="m4.57 16.93c-.128 0-.256-.049-.354-.146-.195-.195-.195-.512 0-.707l4.723-4.723c.566-.566 1.555-.566 2.121 0l1.406 1.406 3.892-4.67c.283-.339.699-.536 1.142-.54h.011c.438 0 .853.19 1.139.523l5.23 6.102c.18.209.156.525-.054.705-.209.18-.524.157-.705-.054l-5.23-6.102c-.097-.112-.231-.174-.38-.174-.104-.009-.287.063-.384.18l-4.243 5.091c-.09.108-.221.173-.362.179-.142.01-.277-.046-.376-.146l-1.793-1.793c-.189-.188-.518-.188-.707 0l-4.723 4.723c-.097.097-.225.146-.353.146z" />
              </g>
            </svg></a></li>
        <li class="ps_dot"><a href="#san_pham_moi"><span class="ps_text">Sản phẩm mới</span> <svg
              xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 64 64" width="512" height="512">
              <path
                d="M62.819,38.427l-7-10a1,1,0,0,0-.5-.374l-7.44-2.588a1,1,0,0,0-.024-.98l-2.485-4.142,4.143-2.486a1,1,0,0,0,0-1.714l-4.143-2.486,2.485-4.142A1,1,0,0,0,47,8H42V4a1,1,0,0,0-1.371-.929L36.241,4.826,32.707,1.293a1,1,0,0,0-1.414,0L27.759,4.826,23.371,3.071A1,1,0,0,0,22,4V8H17a1,1,0,0,0-.857,1.515l2.485,4.142-4.143,2.486a1,1,0,0,0,0,1.714l4.143,2.486-2.485,4.142a1,1,0,0,0-.024.98L8.677,28.054a1,1,0,0,0-.5.373l-7,10a1,1,0,0,0,.491,1.517L8,42.145V55a1,1,0,0,0,.709.957l23,7a1.006,1.006,0,0,0,.582,0l23-7A1,1,0,0,0,56,55V42.145l6.328-2.2a1,1,0,0,0,.491-1.517ZM32,34.954,12.226,28.936,20.667,26H22v4a1,1,0,0,0,1.371.929l4.388-1.755,3.534,3.533a1,1,0,0,0,1.414,0l3.534-3.533,4.388,1.755A1,1,0,0,0,42,30V26h1.333l8.441,2.936ZM16.943,17l3.572-2.143a1,1,0,0,0,.342-1.372L18.767,10H23a1,1,0,0,0,1-1V5.477l3.629,1.452a1,1,0,0,0,1.078-.222L32,3.414l3.293,3.293a1,1,0,0,0,1.078.222L40,5.477V9a1,1,0,0,0,1,1h4.233l-2.09,3.485a1,1,0,0,0,.342,1.372L47.057,17l-3.572,2.143a1,1,0,0,0-.342,1.372L45.233,24H41a1,1,0,0,0-1,1v3.523l-3.629-1.452a1,1,0,0,0-1.078.222L32,30.586l-3.293-3.293a1,1,0,0,0-1.078-.222L24,28.523V25a1,1,0,0,0-1-1H18.767l2.09-3.485a1,1,0,0,0-.342-1.372ZM9.4,30.168,30.45,36.573,24.581,45.8l-21-7.306ZM10,42.841l14.672,5.1A.983.983,0,0,0,25,48a1,1,0,0,0,.844-.463L31,39.434V60.651L10,54.259ZM54,54.259,33,60.651V39.434l5.156,8.1A1,1,0,0,0,39,48a.983.983,0,0,0,.328-.056L54,42.841ZM39.419,45.8,33.55,36.573,54.6,30.168l5.825,8.322Z" />
              <path d="M24,16.3l2.168,3.252A1,1,0,0,0,28,19V12H26v3.7l-2.168-3.252A1,1,0,0,0,22,13v7h2Z" />
              <path d="M30,20h3V18H31V17h1V15H31V14h2V12H30a1,1,0,0,0-1,1v6A1,1,0,0,0,30,20Z" />
              <path d="M35,20h6a1,1,0,0,0,1-1V12H40v6H39V15H37v3H36V12H34v7A1,1,0,0,0,35,20Z" />
            </svg>
          </a></li>
        <li class="ps_dot"><a href="#ky_gui"><span class="ps_text">Ký gửi</span> <svg enable-background="new 0 0 512 512"
              height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
              <g id="_x34_4_Promotion">
                <g>
                  <g>
                    <path
                      d="m476.861 159.118-12.084-34.032 1.632-9.256c2.476-13.983-2.047-28.332-12.092-38.39l-26.666-26.666 32.681-32.681c3.125-3.125 3.125-8.186 0-11.311s-8.186-3.125-11.311 0l-32.681 32.681-26.663-26.663c-10.046-10.038-24.325-14.588-38.386-12.1l-73.639 12.987c-8.85 1.562-16.888 5.749-23.239 12.104l-209.082 209.079c-17.026 17.018-17.038 44.599 0 61.629l94.141 94.138-4.489 16.238c-6.575 23.757 7.341 48.314 31.113 54.887l175.751 48.587c23.808 6.574 48.361-7.495 54.891-31.113l81.075-293.302c2.462-8.869 2.126-18.142-.952-26.816zm-420.219 87.063 209.082-209.079c4.023-4.023 9.108-6.671 14.709-7.659l73.639-12.987c8.944-1.578 17.997 1.353 24.294 7.655l26.664 26.662-23.284 23.283c-9.62-4.633-21.926-3.182-29.705 4.603-10.038 10.038-10.038 26.372 0 36.417 10.069 10.053 26.403 10.018 36.417-.004 8.052-8.056 9.53-20.115 4.664-29.77l23.218-23.219 26.665 26.664c6.359 6.366 9.218 15.447 7.655 24.298l-12.983 73.631c-1 5.609-3.648 10.698-7.663 14.713l-209.081 209.083c-10.421 10.421-28.582 10.421-39.003 0l-125.288-125.285c-10.772-10.772-10.782-28.224 0-39.006zm320.505-156.211c3.796 3.8 3.796 9.987 0 13.791-3.812 3.804-9.983 3.804-13.795 0-3.796-3.804-3.796-9.991 0-13.787 3.816-3.815 9.957-3.842 13.795-.004zm85.247 91.696-81.075 293.306c-4.203 15.217-20.044 24.145-35.206 19.958l-175.751-48.587c-15.238-4.209-24.177-19.959-19.958-35.202l2.073-7.499 18.143 18.142c17.019 17.035 44.587 17.038 61.625 0l209.082-209.078c6.351-6.347 10.53-14.381 12.1-23.243l5.718-32.427 2.641 7.438c1.975 5.556 2.186 11.501.608 17.192z" />
                    <path
                      d="m309.086 234.299c0-4.417-3.578-7.999-7.999-8.003l-136.474-.062c-4.413 0-7.999 3.578-7.999 7.995s3.578 7.999 7.999 8.003l136.474.062c4.414 0 7.999-3.577 7.999-7.995z" />
                    <path
                      d="m205.685 262.569c-9.163 9.171-9.163 24.079 0 33.234 9.148 9.156 24.077 9.157 33.23.004 9.234-9.242 9.149-24.097 0-33.238-8.897-8.909-24.309-8.917-33.23 0zm21.919 21.919v.004c-2.812 2.804-7.796 2.808-10.608-.004-2.929-2.922-2.921-7.683 0-10.604 2.952-2.952 7.622-2.986 10.608 0 2.923 2.923 2.96 7.645 0 10.604z" />
                    <path
                      d="m260.014 205.967c0-.004 0-.004 0-.004 9.155-9.163 9.155-24.071 0-33.238-9.178-9.163-24.091-9.147-33.238.004-9.155 9.163-9.155 24.071 0 33.234 9.133 9.141 24.054 9.172 33.238.004zm-21.927-21.923c2.939-2.946 7.602-3.01 10.616-.004 2.921 2.925 2.921 7.687 0 10.612-2.812 2.804-7.804 2.804-10.616 0-2.921-2.925-2.921-7.686 0-10.608z" />
                  </g>
                </g>
              </g>
            </svg></a></li>
      </ul>
      <span class="ps_next"><?php echo $arrow_down; ?></span>
    </div>

    <?php
  }

  //add_action( 'wp_footer', 'gap_contact_form_footer' );

  function gap_contact_form_footer() {
    echo '<div class="gap_contact_form_footer">';
    echo do_shortcode('[contact-form-7 id="36f8be0" title="Form liên hệ 1"]');
    echo '</div>';
  }

  /* Woocommerce Registration */

  function text_domain_woo_reg_form_fields() {
    ?>
    <!-- <p class="form-row form-row-first"></p>
  <p class="form-row form-row-last"></p> -->

    <p class="form-row">
      <label for="billing_phone"><?php _e('Số điện thoại (có Zalo)', 'text_domain'); ?><span
          class="required">*</span></label>
    <div style="display: flex; margin-bottom: 10px">
      <input type="text" class="input-text" name="billing_phone" id="form-field-phone" value="<?php if (!empty($_POST['billing_phone']))
        esc_attr_e($_POST['billing_phone']); ?>" style="
      width: auto;
      flex-grow: 1;
      margin-right: 10px;
  " />
      <button onclick="phoneAuth();" class="button" type="button">Xác minh</button>
    </div>

    <div id="recaptcha-container"></div>
    </p>

    <p class="form-row">
      <label for="zalo_otp"><?php _e('Mã OTP Zalo', 'text_domain'); ?><span class="required">*</span></label>
      <input type="text" class="input-text" name="zalo_otp" id="zalo_otp" value="<?php if (!empty($_POST['zalo_otp']))
        esc_attr_e($_POST['zalo_otp']); ?>" placeholder="Nhập mã OTP vào đây" />

      <input type="hidden" name="verificationId" id="form-field-verificationId" value="" />
    </p>

    <div class="clear"></div>
    <?php
  }


  function wooc_validate_extra_register_fields($username, $email, $validation_errors) {
    if (isset($_POST['billing_phone'])) {
      if (empty($_POST['billing_phone'])) {
        $validation_errors->add('billing_phone_error', __('Vui lòng nhập số điện thoại.', 'woocommerce'));
      }
      if (!$this->validate_mobile($_POST['billing_phone'])) {
        $validation_errors->add('billing_phone_error', __('Số điện thoại không đúng định dạng.', 'woocommerce'));
      }
    }

    if (isset($_POST['zalo_otp'])) {
      if (empty($_POST['zalo_otp'])) {
        $validation_errors->add('zalo_otp_error', __('Vui lòng nhập OTP.', 'woocommerce'));
      }

      $verificationId = $_POST['verificationId'];
      $otp = $_POST['zalo_otp'];
      $response = Firebase::instance()->activate_through_firebase($verificationId, $otp);
      if ($response->error && $response->error->code == 400) {
        error_log($response->error->message);
        $validation_errors->add('billing_phone_error', 'Sai mã OTP!!!');
      }

    }
  }

  function validate_mobile($mobile) {
    //$regex = '/([\+84|84|0]+(3|5|7|8|9|1[2|6|8|9]))+([0-9]{8})\b/';
    $regex = '/^(0[35789]{1})+([0-9]{8})+$/';
    return preg_match($regex, $mobile);
  }

  //https://www.cloudways.com/blog/add-woocommerce-registration-form-fields/
  function wooc_save_extra_register_fields($customer_id) {
    if (isset($_POST['billing_phone'])) {
      // Phone input filed which is used in WooCommerce
      update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    }
  }


  // This will suppress empty email errors when submitting the user form

  function my_user_profile_update_errors($errors, $update, $user) {
    $errors->remove('empty_email');
  }

  // This will remove javascript required validation for email input
// It will also remove the '(required)' text in the label
// Works for new user, user profile and edit user forms

  function my_user_new_form($form_type) {
    ?>
    <script type="text/javascript">
      jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
      // Uncheck send new user email option by default
      <?php if (isset($form_type) && $form_type === 'add-new-user'): ?>
        jQuery('#send_user_notification').removeAttr('checked');
      <?php endif; ?>
    </script>
    <?php
  }
  //https://woostify.com/woocommerce-phone-number/

} //Class