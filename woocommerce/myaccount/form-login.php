<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')): ?>

  <div class="u-columns col2-set" id="customer_login">

    <div class="u-column1 col-1">

    <?php endif; ?>

    <h2><?php esc_html_e('Login', 'woocommerce'); ?></h2>

    

      <?php do_action('woocommerce_login_form_start'); ?>

      <p class="form-row">
        <label for="username"><?php _e('Số điện thoại (Zalo)', 'text_domain'); ?><span class="required">*</span></label>

      <div style="display: flex; margin-bottom: 10px; ">
        <input type="text" class="input-text" name="username" id="username" value="<?php if (!empty($_POST['username']))
          esc_attr_e($_POST['username']); ?>" style="width: auto;flex-grow: 1;margin-right: 10px;" />
        <button data-context="login" class="btnGetOTPZNS button" type="button">Xác thực</button>
      </div>

      <!-- <div id="recaptcha-container"></div> -->
      </p>

      <p class="form-row">
        <label for="zalo_otp_login"><?php _e('Mã OTP Zalo', 'text_domain'); ?><span class="required">*</span></label>
        <?php $zalo_otp_login = !empty($_POST['zalo_otp_login']) ? $_POST['zalo_otp_login'] : '';?>
        <input type="text" class="input-text" name="zalo_otp_login" id="zalo_otp_login" value="<?php echo $zalo_otp_login; ?>" placeholder="Nhập mã OTP vào đây" />

        <?php $verificationId_login = isset($_POST['verificationId_login']) ? trim($_POST['verificationId_login']) : ''; 
        //$verificationId_login = 'Mzg0ZjdlMjFhZDgzMWVjZjUzZGMyZDM4MTg2Y2U4NzY5NTA3ODY0NTA2ZmZiOGQ5MTQ1OTljYjQ2YjIyZTc5Yjg1YTE2YjQzOTM5MDZlNDc4MTBlMmU5MTIwYTc5ZjYzYWM4YTZjZmZmYmJlYTAzNzhlZDY3ZTZkY2VmNjEyZjA=';
        ?>
        <input type="hidden" name="verificationId_login" id="verificationId_login" value="<?php echo $verificationId_login; ?>" />
      </p>

      <input class="woocommerce-Input woocommerce-Input--text input-text" type="hidden" name="password" id="password"
        autocomplete="current-password" value="<?php echo wp_generate_password(8); ?>"" />

      <?php do_action('woocommerce_login_form'); ?>

      <p class=" form-row">
      <!-- <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
          <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox"
            id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
        </label> -->
      <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
      <button type="button" class="button" id="LoginBtn" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
      </p>

      <?php //wp_nonce_field('login_action', 'login_nonce_field'); ?>
      <?php do_action('woocommerce_login_form_end'); ?>

    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')): ?>

    </div>

    <div class="u-column2 col-2">
      <h2><?php esc_html_e('Register', 'woocommerce'); ?></h2>

      <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

        <?php do_action('woocommerce_register_form_start'); ?>

        <?php if ('no' === get_option('woocommerce_registration_generate_username')): ?>

          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span
                class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
              id="reg_username" autocomplete="username"
              value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
          </p>

        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span
              class="required">*</span></label>
          <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email"
            autocomplete="email"
            value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <!-- <p class="form-row form-row-first"></p>
        <p class="form-row form-row-last"></p> -->

        <p class="form-row">
          <label for="billing_phone"><?php _e('Số điện thoại (Zalo)', 'text_domain'); ?><span
              class="required">*</span></label>


        <div style="display: flex; margin-bottom: 10px; ">
          <input type="text" class="input-text" name="billing_phone" id="billing_phone" value="<?php if (!empty($_POST['billing_phone']))
            esc_attr_e($_POST['billing_phone']); ?>" style="width: auto;flex-grow: 1;margin-right: 10px;" />
          <button data-context="register" class="btnGetOTPZNS button" type="button">Xác thực</button>
        </div>

        <!-- <div id="recaptcha-container"></div> -->
        </p>

        <p class="form-row">
          <label for="zalo_otp_register"><?php _e('Mã OTP Zalo', 'text_domain'); ?><span class="required">*</span></label>
          <input type="text" class="input-text" name="zalo_otp_register" id="zalo_otp_register" value="<?php if (!empty($_POST['zalo_otp_register']))
            esc_attr_e($_POST['zalo_otp_register']); ?>" placeholder="Nhập mã OTP vào đây" />

          <?php $verificationId_register = isset($_POST['verificationId_register']) ? trim($_POST['verificationId_register']) : ''; ?>

          <input type="hidden" id="verificationId_register" value="<?php echo $verificationId_register; ?>" />
        </p>

        <div class="clear"></div>

        <?php if ('no' === get_option('woocommerce_registration_generate_password')): ?>

          <input type="hidden" class="woocommerce-Input woocommerce-Input--text input-text" name="password"
            id="reg_password" autocomplete="new-password" value="<?php echo wp_generate_password(8); ?>" />

        <?php else: ?>

          <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>

        <?php endif; ?>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-form-row form-row">
          <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
          <button type="submit"
            class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
            name="register"
            value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
        </p>

        <?php //wp_nonce_field('register_action', 'register_nonce_field'); ?>

        <?php do_action('woocommerce_register_form_end'); ?>

      </form>

    </div>

  </div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>