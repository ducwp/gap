<?php

namespace GAPTheme;

class WooCommerce {
  public $gap_settings;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->gap_settings = get_option("gap_settings", array());
    /* $username = '1 duc';
    if (!$this->validate_username($username)) {
      echo "LOI USENAME";
    }  */
    add_filter('woocommerce_loop_add_to_cart_link', [$this, 'loop_add_to_cart_link_filter'], 10, 3);
    add_action('woocommerce_register_form', [$this, 'text_domain_woo_reg_form_fields']);
    add_action('woocommerce_register_post', [$this, 'wooc_validate_extra_register_fields'], 10, 3);
    add_action('woocommerce_created_customer', [$this, 'wooc_save_extra_register_fields']);

    /* add_action('woocommerce_login_form_end', function () {
      if (!wc_get_raw_referer())
        return;
      echo '<input type="hidden" name="redirect" value="' . wp_validate_redirect(wc_get_raw_referer(), wc_get_page_permalink('myaccount')) . '" />';
    }); */

    // Change add to cart text on single product page
    add_filter('woocommerce_product_single_add_to_cart_text', function () {
      return __('Thêm giỏ hàng', 'woocommerce');
    });


    // Change add to cart text on product archives page
    add_filter('woocommerce_product_add_to_cart_text', function () {
      return __('Thêm giỏ hàng', 'woocommerce');
    });

    //add_filter('woocommerce_coupons_enabled', [$this, 'bbloomer_disable_coupons_cart_page']);
    //remove_action('woocommerce_before_checkout_form', [$this, 'woocommerce_checkout_coupon_form'], 10);
    add_action('woocommerce_review_order_before_submit', [$this, 'bbloomer_checkout_coupon_below_payment_button']);

    //https://www.businessbloomer.com/category/woocommerce-tips/visual-hook-series/
    //https://www.businessbloomer.com/woocommerce-visual-hook-guide-checkout-page/
    //https://www.businessbloomer.com/woocommerce-visual-hook-guide-account-pages/

    add_action('woocommerce_account_dashboard', function () {
      $current_user = Hooks::instance()->current_user;

      $level_1 = Init::instance()->level_1; //1000
      $level_2 = Init::instance()->level_2; //2000
      $point = absint(get_user_meta($current_user->ID, '_nrp_points', true));

      if ($point < $level_1) {
        $icon = "star.svg";
        $level_label = "0";
      } elseif ($point >= $level_1 && $point < $level_2) {
        $icon = "medal.svg";
        $level_label = "1";
      } else {
        $icon = "trophy.svg";
        $level_label = "2";
      }
      echo $icon;

      $badge = get_stylesheet_directory_uri() . '/assets/img/' . $icon;
      echo '<p style="text-align: center">';
      printf('<img src="%s" width="100" /><br><br>', $badge);
      printf('<b>%s</b><br>', $current_user->display_name);
      if ($level_label != '0')
        printf(__('Chúc mừng! Bạn đã đạt <b>Hạng %s</b><br> vì đã kiếm được <b>%s</b> điểm.'), $level_label, $point);
      else
        printf(__('Chúc mừng! Bạn đã kiếm được <b>%s</b> điểm.'), $point);
      echo '</p>';
    });

    add_action('woocommerce_account_dashboard', function () {
      if (isset($this->gap_settings['woo_account_page_dashboard'])) {
        echo $this->gap_settings['woo_account_page_dashboard'];
      }
    });

    //add_filter('woocommerce_get_shop_coupon_data', [$this, 'set_wc_coupon_data'], 99, 3);
    //add_filter( 'woocommerce_coupon_get_discount_amount', [$this, 'coupon_get_discount_amount_filter'], 10, 4 );
    add_action('woocommerce_checkout_before_order_review', [$this, 'ts_apply_discount_to_cart']);

    add_action('wp_head', function () {
      $freeshipping_gold_coupon = isset($this->gap_settings['freeshipping_gold']) ? $this->gap_settings['freeshipping_gold'] : 'gap_freeshipping_gold';
      printf('<style>.cart-discount.coupon-%s {display: none;}</style>', $freeshipping_gold_coupon);

    });
  }

  function getUserPoints() {
    $current_user = Hooks::instance()->current_user;
    return absint(get_user_meta($current_user->ID, '_nrp_points', true));
  }

  function ts_apply_discount_to_cart() {
    if ($this->getUserPoints() < Init::instance()->level_2)
      return;
    $coupon_code = 'gap_freeshipping_gold';

    if (!WC()->cart->has_discount($coupon_code)) {
      if (!WC()->cart->apply_coupon($coupon_code)) {
        wc_print_notices();
      }
    }

    /* $discount_amount = 37000;
    $coupon = new \WC_Coupon();
    $coupon->set_code($coupon_code);
    $coupon->set_discount_type('shipping_discount');
    $coupon->set_amount($discount_amount);
    $coupon->set_virtual(true); */
  }




  function loop_add_to_cart_link_filter($class, $product, $args) {

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
      <input type="text" class="input-text" name="zalo_otp" id="form-field-zalo_otp" value="<?php if (!empty($_POST['zalo_otp']))
        esc_attr_e($_POST['zalo_otp']); ?>" placeholder="Nhập mã OTP vào đây" />

      <input type="hidden" name="verificationId" id="form-field-verificationId" value="" />
    </p>

    <div class="clear"></div>
    <?php
  }


  function wooc_validate_extra_register_fields($username, $email, $validation_errors) {

    ///^\w+$/
    if (isset($_POST['username']) && empty($_POST['username'])) {

      if (!$this->validate_username($_POST['username'])) {
        $validation_errors->add('username_error', __('Yêu cầu tên người dùng: a-z, A-Z và _', 'woocommerce'));
      }
    }

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

  function validate_username($usename) {
    //$regex = '/([\+84|84|0]+(3|5|7|8|9|1[2|6|8|9]))+([0-9]{8})\b/';
    $regex = '/^\w+$/';
    return preg_match($regex, $usename);
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

  function bbloomer_disable_coupons_cart_page() {
    if (is_cart())
      return false;
    return true;
  }

  function bbloomer_checkout_coupon_below_payment_button() {
    echo '<hr>';
    woocommerce_checkout_coupon_form();
  }
}