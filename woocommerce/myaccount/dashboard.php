<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$allowed_html = array(
  'a' => array(
    'href' => array(),
  ),
);
?>

<p>
  <?php
  printf(
    /* translators: 1: user display name 2: logout url */
    wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'), $allowed_html),
    '<strong>' . esc_html($current_user->display_name) . '</strong>',
    esc_url(wc_logout_url())
  );
  ?>
</p>

<?php
$user_obj = new \GAPTheme\User;
$gap_settings = get_option("gap_settings", array());

//Icon
$user_data = $user_obj->get_user_data();

$badge = get_stylesheet_directory_uri() . '/assets/img/icon-' . $user_data['level'] . '.svg';
echo '<p style="text-align: center">';
printf('<img src="%s" width="100" /><br><br>', $badge);
printf('Xin chào: <b>%s</b><br>', $user_data['name']);
if ($user_data['level'] != 'member') {
  printf(__('Chúc mừng! Bạn đã đạt hạng thành viên <b>%s</b>.'), strtoupper($user_data['level']));
  printf('<br><span>Số điểm hiện có: <b>%s</b></span>', $user_data['point']);
} else
  if ($user_data['point'] > 0)
    printf(__('Chúc mừng! Bạn đã kiếm được <b>%s</b> điểm.'), $user_data['point']);
  else
    echo 'Bạn chưa có điểm nào, hãy mua hàng để tích lũy điểm.';
echo '</p>';

//VIP cats
if ($user_data['level'] !== 'member') {
  //$cat_ids = !empty($this->gap_settings['vip_cats']) ? explode(',', $this->gap_settings['vip_cats']) : 0;
  $args = array(
    'hide_empty' => false, // also retrieve terms which are not used yet
    'meta_query' => array(
      array(
        'key' => 'vip_cat',
        'value' => '1',
        'compare' => '=='
      )
    ),
    'taxonomy' => 'product_cat',
  );

  $terms = get_terms('product_cat', $args);

  if (!empty($terms)) {

    $cat_ids = [];
    foreach ($terms as $term) {
      $cat_ids[] = $term->term_id;
    }

    if (!empty($cat_ids)) {
      $cat_ids = array_map(function ($id) {
        return (int) trim($id);
      }, $cat_ids);

      echo '<ul class="button_links"><li><b>Đặc quyền danh mục sản phẩm dành riêng cho VIP/ VVIP</b>:</li>';
      foreach ($cat_ids as $cat_id) {
        //$cat = get_the_category_by_ID($cat_id);
        $cat = get_term($cat_id);
        printf('<li><a href="%s">%s</a></li>', get_category_link($cat->term_id), $cat->name);
      }
      echo '</ul>';

    }
  }

}

//Progress bar
$percent = ceil(absint($user_data['point'])*0.1);
if($percent > 100) $percent = 100;
$percent = $percent.'%';  

$ico_member = get_stylesheet_directory_uri() . '/assets/img/icon-level-bar/member.svg';
$ico_vip = get_stylesheet_directory_uri() . '/assets/img/icon-level-bar/vip.svg';
$ico_vvip = get_stylesheet_directory_uri() . '/assets/img/icon-level-bar/vvip.svg';
$progress_html = '<p class="membership-bar-wrap">';
$progress_html .= '<span class="membership-level">';
$progress_html .= sprintf('<img src="%s" />', $ico_member);
$progress_html .= sprintf('<img src="%s" />', $ico_vip);
$progress_html .= sprintf('<img src="%s" />', $ico_vvip);
$progress_html .= '</span>';
$progress_html .= '<span class="membership-bar">';
$progress_html .= sprintf('<span class="membership-bar-load load-point-%2$s" style="width: %1$s">%2$s</span>', $percent, $user_data['point']);
$progress_html .= '</span><span class="membership-point-level">';
$progress_html .= '<span></span><span>500</span><span>1000</span>';
$progress_html .= '</span></p>';
echo $progress_html;


$link_dieu_khoan = get_page_link(3320);
echo '<p><ul class="button_links"><li><a href="/tai-khoan/nrp-points/">Lịch sử điểm</a></li><li><a target="_blank" href="'.$link_dieu_khoan.'">Điều khoản và điều kiện thành viên Give Away Premium Phú Nhuận.</a></li></ul></p>';

if (isset($gap_settings['woo_account_page_dashboard'])) {
  echo '<p>' . $gap_settings['woo_account_page_dashboard'] . '</p>';
}
?>

<!-- <p>
  <?php
  /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
  $dashboard_desc = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
  if (wc_shipping_enabled()) {
    /* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
    $dashboard_desc = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
  }
  printf(
    wp_kses($dashboard_desc, $allowed_html),
    esc_url(wc_get_endpoint_url('orders')),
    esc_url(wc_get_endpoint_url('edit-address')),
    esc_url(wc_get_endpoint_url('edit-account'))
  );
  ?>
</p> -->

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
