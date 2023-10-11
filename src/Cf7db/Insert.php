<?php
namespace GAPTheme\Cf7db;

class Insert {
  public $user_id;
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->user_id = get_current_user_id();
    add_action('wpcf7_before_send_mail', [$this, 'cf7_before_send_mail']);
  }

  public function cf7_before_send_mail($form_tag) {
    $form_test_id = Init::instance()->form_test_id;

    if ($form_tag->id() === $form_test_id)
      return;

    global $wpdb;
    $cfdb = apply_filters('cf7_gap_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $upload_dir = wp_upload_dir();
    $gap_dirname = $upload_dir['basedir'] . '/gap_cf7_uploads';
    $time_now = time();

    $submission = \WPCF7_Submission::get_instance();
    $contact_form = $submission->get_contact_form();
    $tags_names = array();
    $strict_keys = apply_filters('cf7_gap_strict_keys', false);

    if ($submission) {

      $allowed_tags = array();
      $bl = array('\"', "\'", '/', '\\', '"', "'");
      $wl = array('&quot;', '&#039;', '&#047;', '&#092;', '&quot;', '&#039;');

      if ($strict_keys) {
        $tags = $contact_form->scan_form_tags();
        foreach ($tags as $tag) {
          if (!empty($tag->name))
            $tags_names[] = $tag->name;
        }
        $allowed_tags = $tags_names;
      }

      $not_allowed_tags = apply_filters('cf7_gap_not_allowed_tags', array('g-recaptcha-response'));
      $allowed_tags = apply_filters('cf7_gap_allowed_tags', $allowed_tags);
      $data = $submission->get_posted_data();
      $files = $submission->uploaded_files();
      $uploaded_files = array();


      foreach ($_FILES as $file_key => $file) {
        array_push($uploaded_files, $file_key);
      }
      foreach ($files as $file_key => $file) {
        $file = is_array($file) ? reset($file) : $file;
        if (empty($file))
          continue;
        copy($file, $gap_dirname . '/' . $time_now . '-' . $file_key . '-' . basename($file));
      }

      $form_data = array();

      $form_data['gap_status'] = 'unread';
      foreach ($data as $key => $d) {

        if ($strict_keys && !in_array($key, $allowed_tags))
          continue;

        if (!in_array($key, $not_allowed_tags) && !in_array($key, $uploaded_files)) {

          $tmpD = $d;

          if (!is_array($d)) {
            $tmpD = str_replace($bl, $wl, $tmpD);
          } else {
            $tmpD = array_map(function ($item) use ($bl, $wl) {
              return str_replace($bl, $wl, $item);
            }, $tmpD);
          }

          $key = sanitize_text_field($key);
          $form_data[$key] = $tmpD;
        }
        if (in_array($key, $uploaded_files)) {
          $file = is_array($files[$key]) ? reset($files[$key]) : $files[$key];
          $file_name = empty($file) ? '' : $time_now . '-' . $key . '-' . basename($file);
          $key = sanitize_text_field($key);
          $form_data[$key . '_gap_file'] = $file_name;
        }
      }

      /* cfdb7 before save data. */
      $form_data = apply_filters('cf7_gap_before_save_data', $form_data);

      do_action('cf7_gap_before_save', $form_data);

      $form_post_id = $form_tag->id();
      $form_value = serialize($form_data);
      $form_date = current_time('Y-m-d H:i:s');

      //Datlich
      $form_type = isset($data['form_type']) ? $data['form_type'] : 'other';
      $date = '';
      if ($form_type == 'offline') {
        $date_arr = explode("/", $data['gap_date']);
        $date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];
      }
      $time = isset($data['gap_time']) ? $data['gap_time'] : '';

      $cfdb->insert($table_name, array(
        'form_post_id' => $form_post_id,
        'form_value' => $form_value,
        'form_date' => $form_date,
        'form_type' => $form_type,
        'user_id' => $this->user_id,
        'gap_date' => $date,
        'gap_time' => $time,
      ));

      /* cfdb7 after save data */
      $insert_id = $cfdb->insert_id;
      do_action('cf7_gap_after_save_data', $insert_id);
    }

  }
}