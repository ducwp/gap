<?php

namespace GAPTheme;

class Firebase {
  public $firebase_api;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {

    if(!is_page('ky-gui-online')) return;

    $this->firebase_api = 'AIzaSyCYsNFgVTVGHuxSsLQ1D9QO5gRqlQlGTmg';

    add_action('wp_enqueue_scripts', function () {
      $theme_uri = get_stylesheet_directory_uri();
      wp_enqueue_script('gap-firebase', 'https://www.gstatic.com/firebasejs/8.3.1/firebase.js', [], '', true);
      $data =  [
        'apiKey' => $this->firebase_api,
        'authDomain' =>'phone-otp-3e0bb.firebaseapp.com',
        'databaseURL' =>'https://chat-anonystick.firebaseio.com',
        'projectId' =>'phone-otp-3e0bb',
        'storageBucket' =>'chat-anonystick.appspot.com',
        'messagingSenderId' =>'949881420138',
        'appId' =>'1:949881420138:web:504c7a20732600e57d8d1',
        'measurementId' =>'G-SSMFLBC1XP'
      ];
      wp_localize_script('gap-firebase', 'GAPfirebaseConfig', $data);

      //wp_enqueue_script('gap-firebase-settings', $theme_uri . '/assets/js/firebase-settings.js', ['gap-firebase'], '', true);
      wp_enqueue_script('gap-firebase-action', $theme_uri . '/assets/js/firebase.js', ['gap-firebase'], '', true);
    });
    
    //add_action('wp_footer', [$this, 'firebase_settings']);
  }

  public function firebase_settings() {
    ?>
    <script>
      var firebaseConfig = {
        apiKey: "<?php echo $this->firebase_api; ?>",
        authDomain: "phone-otp-3e0bb.firebaseapp.com",
        databaseURL: "https://chat-anonystick.firebaseio.com",
        projectId: "phone-otp-3e0bb",
        storageBucket: "chat-anonystick.appspot.com",
        messagingSenderId: "949881420138",
        appId: "1:949881420138:web:504c7a20732600e57d8d1",
        measurementId: "G-SSMFLBC1XP"
      };

      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      firebase.analytics();
    </script>

  <?php
  }

  public function activate_through_firebase($verificationId, $otp) {
    /* $response = wp_remote_post("https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=" . $this->firebase_api, [
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
      CURLOPT_URL => 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=' . $this->firebase_api,
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