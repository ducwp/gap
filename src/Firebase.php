<?php

namespace GAPTheme;

class Firebase {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    //your code here
  }

  public function activate_through_firebase($verificationId, $otp) {
    $firebase_api = 'AIzaSyCYsNFgVTVGHuxSsLQ1D9QO5gRqlQlGTmg';
    /* $response = wp_remote_post("https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=" . $firebase_api, [
      'timeout' => 60,
      'redirection' => 4,
      'blocking' => true,
      'headers' => array('Content-Type' => 'application/json'),
      'body' => wp_json_encode([
        'code' => $otp,
        'sessionInfo' => $verificationId
      ])
    ]);
    $body = wp_remote_retrieve_body($response);
    */

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=' . $firebase_api,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{"code":' . $otp . ',"sessionInfo":\'' . $verificationId . '\'}',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $body = curl_exec($curl);

    curl_close($curl);
    //echo $body;
    //error_log($body);
    return json_decode($body);
  }
}