<?php
add_filter('wpcf7_validate_number', 'gap_number_confirmation_validation_filter', 20, 2);

function gap_number_confirmation_validation_filter($result, $tag)
{
  /* if ('your-email-confirm' == $tag->name) {
    $your_email = isset($_POST['your-email']) ? trim($_POST['your-email']) : '';
    $your_email_confirm = isset($_POST['your-email-confirm']) ? trim($_POST['your-email-confirm']) : '';

    if ($your_email != $your_email_confirm) {
      $result->invalidate($tag, "Are you sure this is the correct address?");
    }
  } */

  $clothes_new_local = absint($_POST['clothes_new_local']);
  $clothes_new_global = absint($_POST['clothes_new_global']);
  $clothes_used_local = absint($_POST['clothes_used_local']);
  $clothes_used_global = absint($_POST['clothes_used_global']);

  if ($clothes_new_local + $clothes_new_global + $clothes_used_local + $clothes_used_global < 5) {
    $result->invalidate($tag, "Tổng số sản phẩm phải từ 5 trở lên.");
  }

  return $result;
}