<?php

namespace GAPTheme;

class Zalo {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('wp_ajax_' . 'gap_get_otp_zalo', [$this, 'gap_get_otp_zalo_func']);
    add_action('wp_ajax_nopriv_' . 'gap_get_otp_zalo', [$this, 'gap_get_otp_zalo_func']);
  }

  public function gap_get_otp_zalo_func() {
    $phone = trim($_POST['phone']);
    if (!$this->validate_mobile($phone)) {
      wp_send_json_error('Số điện thoại không đúng định dạng.');
    }

    $context = trim($_POST['context']);
    if ($context === 'login') {
      $user = @reset(
        get_users(
          array(
            'meta_key' => 'billing_phone',
            'meta_value' => $phone,
            'number' => 1,
            'count_total' => false
          )
        )
      );

      if (!$user) {
        wp_send_json_error('Tài khoản không tồn tại.');
      }
    }

    //$zalo_otp = WC()->session->set('zalo_otp');
    $newotp = rand(100000, 999999);
    //Gọi API ZNS
    file_put_contents("D:/zns.txt", $newotp);

    $success = true;

    if ($success == true) {
      //Tra ve front-end
      $hashed = base64_encode(hash('sha256', $phone) . hash('sha256', $newotp));
      wp_send_json_success($hashed);
    } else {
      wp_send_json_error('Lỗi');
    }

    die();
  }

  public function verifyZNS($phone, $otp, $code) {
    $hashed = base64_encode(hash('sha256', $phone) . hash('sha256', $otp));
    if ($code === $hashed)
      return true;
    return false;
  }

  function validate_mobile($mobile) {
    //$regex = '/([\+84|84|0]+(3|5|7|8|9|1[2|6|8|9]))+([0-9]{8})\b/';
    $regex = '/^(0[35789]{1})+([0-9]{8})+$/';
    return preg_match($regex, $mobile);
  }

}