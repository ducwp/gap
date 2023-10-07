<?php 

namespace GAPTheme;

class WooCommerce {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_filter('woocommerce_loop_add_to_cart_link', [$this, 'loop_add_to_cart_link_filter'], 10, 3);
    add_action('woocommerce_register_form', [$this, 'text_domain_woo_reg_form_fields']);
    add_action('woocommerce_register_post', [$this, 'wooc_validate_extra_register_fields'], 10, 3);
    add_action('woocommerce_created_customer', [$this, 'wooc_save_extra_register_fields']);
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
}