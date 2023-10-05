<?php

add_action('init', 'log_the_user_in');
function log_the_user_in()
{
  if (!isset($_POST['btn_login']))
    return;

  if (!wp_verify_nonce($_POST['login_nonce'], 'login_nonce')) {
    return new WP_Error('invalid_data', 'Invalid data.');
  }

  if (empty($_POST['user_login']) || empty($_POST['user_password'])) {
    return new WP_Error('empty', 'Both fields are required.');
  }

  if (is_email($_POST['user_login'])) {

    // check user by email

    $user = get_user_by('email', $_POST['user_login']);
  } elseif (is_numeric($_POST['user_login'])) {

    // check user by phone number

    global $wpdb;
    $tbl_usermeta = $wpdb->prefix . 'usermeta';
    $user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $tbl_usermeta WHERE meta_key=%s AND meta_value=%s", 'user_phone', $_POST['user_login']));

    $user = get_user_by('ID', $user_id);
  } else {

    // check user by username

    $user = get_user_by('login', $_POST['user_login']);
  }

  if (!$user) {
    return new WP_Error('wrong_credentials', 'Invalid credentials.');
  }


  // check the user's login with their password.

  if (!wp_check_password($_POST['user_password'], $user->user_pass, $user->ID)) {
    return new WP_Error('wrong_credentials', 'Invalid password.');
  }

  wp_clear_auth_cookie();
  wp_set_current_user($user->ID);
  wp_set_auth_cookie($user->ID);

  wp_redirect(get_bloginfo('url'));
  exit;
}