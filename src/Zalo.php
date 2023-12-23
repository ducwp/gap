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

    $context = trim($_POST['context']);
    if ($context === 'register' && $user) {
      wp_send_json_error('Tài khoản đã tồn tại.');
    }

    if ($context === 'login' && !$user) {
      wp_send_json_error('Tài khoản không tồn tại.');
    }

    //$zalo_otp = WC()->session->set('zalo_otp');
    $newotp = rand(100000, 999999);
    //Gọi API ZNS
    //file_put_contents("D:/newotp.txt", $newotp);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.3ns.com.vn/messages',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POSTFIELDS => '{ "phone": "' . $phone . '", "template_id": 1238, "template_data": {"otp": ' . $newotp . '}}',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer 75|PuxgN8YWHHZXW8P2Bjui88dIaPWS0ziFdTUcU06L',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;

    $rest = json_decode($response, true);

    if ($rest['error'] == '0') {
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