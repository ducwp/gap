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

    add_action('wp_ajax_' . 'gap_login_zalo_otp', [$this, 'gap_login_otp_func']);
    add_action('wp_ajax_nopriv_' . 'gap_login_zalo_otp', [$this, 'gap_login_otp_func']);
  }

  public function gap_get_otp_zalo_func() {
    $context = trim($_POST['context']);
    if ($context != 'register' && $context != 'login') {
      wp_send_json_error('Lỗi context.');
    }

    if (!wp_verify_nonce($_POST['nonce'], 'gap_ajax_action')) {
      wp_send_json_error(__('Gian lận?', 'graby'));
    }


    /* if (!isset($_POST['fnonce'])
      || !wp_verify_nonce($_POST['fnonce'], $context . '_action')
    ) {
      wp_send_json_error('Gian lận?');
    }
 */
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

    if ($context === 'register' && $user) {
      wp_send_json_error('Tài khoản đã tồn tại.');
    }

    if ($context === 'login' && !$user) {
      wp_send_json_error('Tài khoản không tồn tại.');
    }

    //check blocked
    //user_status
    $user_status = get_user_meta($user->ID, 'user_status', true);
    if($user_status == 'deactive'){
      wp_send_json_error('Tài khoản đã bị khóa.');
    }

    $newotp = rand(100000, 999999);
    //$zalo_otp = WC()->session->set('zalo_otp', $newotp);
    //file_put_contents("D:/newotp.txt", $newotp);

    //Gọi API ZNS
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

  public function gap_login_otp_func() {
    
    if (!wp_verify_nonce($_POST['nonce'], 'gap_ajax_action')) {
      wp_send_json_error(__('Gian lận?', 'graby'));
    }

    global $wp;

    $phone = trim($_POST['phone']);
    $otp = trim($_POST['otp']);
    $code = trim($_POST['code']);

    $verify = $this->verifyZNS($phone, $otp, $code);
    if ($verify) {
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
      if ($user) {
        wp_set_current_user($user->ID, $user->data->user_login);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->data->user_login, $user->data);

        if (is_user_logged_in()) {
          wp_send_json_success(['message' => 'You have successfully logged in', 'redirect' => home_url('/tai-khoan')]);
        } else {
          wp_send_json_error('OTP Matched but not logged in!');
        }
      } else {
        wp_send_json_error('Lỗi!');
      }
    } else {
      wp_send_json_error('Sai OTP!');
    }
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