<?php
namespace GAPTheme\Cf7db;

if (!defined('ABSPATH'))
  exit;

/**
 *
 */
class Details {
  private $form_id;
  private $form_post_id;

  public function __construct() {
    $this->form_post_id = isset($_GET['fid']) ? (int) $_GET['fid'] : 0;
    $this->form_id = isset($_GET['ufid']) ? (int) $_GET['ufid'] : 0;
    $this->form_type = isset($_GET['ftype']) ? $_GET['ftype'] : 'other';
    //$this->form_type = 'offline';

    if ($this->form_type == 'online') //Ky gui online
      $this->form_details_online();
    elseif ($this->form_type == 'offline') //Dat lich cua hang
      $this->form_details_offline();
    else
      $this->form_details_page_others();
  }

  public function form_details_online() {
    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $upload_dir = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'] . '/gap_cf7_uploads';
    $rm_underscore = apply_filters('cfdb7_remove_underscore_data', true);


    $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_post_id = $this->form_post_id AND form_id = $this->form_id LIMIT 1", OBJECT);


    if (empty($results)) {
      wp_die($message = 'Not valid contact form');
    }
    ?>
    <div class="wrap">
      <div id="welcome-panel" class="cfdb7-panel">
        <div class="cfdb7-panel-content">
          <div class="welcome-panel-column-container">
            <?php do_action('cfdb7_before_formdetails_title', $this->form_post_id); ?>
            <h3><?php echo get_the_title($this->form_post_id); ?></h3>
            <?php do_action('cfdb7_after_formdetails_title', $this->form_post_id); ?>
            <p></span><?php echo $results[0]->form_date; ?></p>
            <?php $form_data = unserialize($results[0]->form_value); ?>

            <div class="row">
              <div class="col-lg-4">
                <p><strong>Họ tên</strong>: <?php echo $form_data['name']; ?></p>
              </div>
              <div class="col-lg-4">
                <p><strong>Số điện thoại</strong>: <?php echo $form_data['phone']; ?></p>
              </div>
              <div class="col-lg-4">
                <p><strong>Tên Zalo</strong>: <?php echo $form_data['zalo_name']; ?></p>
              </div>

              <div class="col-lg-4">
                <p><strong>Địa chỉ lấy hàng</strong>: <?php echo $form_data['pickup_address']; ?></p>
              </div>
              <div class="col-lg-4">
                <p><strong>Bạn biết GAP</strong>: <?php echo $form_data['you_know'][0]; ?></p>
              </div>
              <div class="col-lg-4">
                <?php
                $fmt = new \NumberFormatter('vi_VN', \NumberFormatter::CURRENCY);
                $desired_price = $fmt->formatCurrency($form_data['desired_price'], "VND");
                ?>
                <p><strong>Giá bán mong muốn</strong>: <?php echo $desired_price; ?></p>
              </div>


            </div>
            <?php
            //Quần áo
            $clothes_data = array_filter($form_data, function ($key) {
              return strpos($key, 'clothes_') !== false;
            }, ARRAY_FILTER_USE_KEY);

            //Túi
            $bag_data = array_filter($form_data, function ($key) {
              return strpos($key, 'bag_') !== false;
            }, ARRAY_FILTER_USE_KEY);

            //Giày
            $shoe_data = array_filter($form_data, function ($key) {
              return strpos($key, 'shoe_') !== false;
            }, ARRAY_FILTER_USE_KEY);

            //Mỹ phẩm
            $cosmetic_data = array_filter($form_data, function ($key) {
              return strpos($key, 'cosmetic_') !== false;
            }, ARRAY_FILTER_USE_KEY);

            //Nước hoa
            $perfume_data = array_filter($form_data, function ($key) {
              return strpos($key, 'perfume_') !== false;
            }, ARRAY_FILTER_USE_KEY);

            $all_products = [
              'clothes' => $clothes_data,
              'bag' => $bag_data,
              'shoe' => $shoe_data,
              'cosmetic' => $cosmetic_data,
              'perfume' => $perfume_data
            ];

            foreach ($all_products as $product_key => $product_data):
              if (empty($product_data[$product_key . '_checkbox'][0]))
                continue;

              echo '<div style="padding: 10px; border: 1px solid #ddd; margin-bottom: 30px; background-color: #f9f9f9"><div class="row">';
              foreach ($product_data as $key => $data):
                $key = esc_html($key);

                if (strpos($key, '_gap_file') !== false) {
                  $data_val = '<a href="' . $cfdb7_dir_url . '/' . $data . '" target="_blank">' . $data . '</a>';
                } else {
                  if (is_array($data)) {
                    $arr_str_data = implode(', ', $data);
                    $arr_str_data = esc_html($arr_str_data);
                    $data_val = nl2br($arr_str_data);

                  } else {
                    $data = esc_html($data);
                    $data_val = nl2br($data);
                  }
                }

                $key_val = $this->key_to_string($key);
                if (strpos($key, '_checkbox')) {
                  $data_val = $data[0];
                  echo '<div class="col-lg-12 ">';
                  printf('<h3 style="margin: 0; padding: 5px 10px; background-color: #ddd">%s</h3>', $data_val);
                  echo '</div>';
                } else {
                  echo '<div class="col-lg-4"><p>';
                  if ($key_val !== '')
                    printf('<strong>%s</strong>: %s', $key_val, $data_val);
                  else
                    echo $data_val;
                  echo '</p></div>';
                }
              endforeach;
              echo '</div></div>';
            endforeach;

            $form_data['gap_status'] = 'read';
            $form_data = serialize($form_data);
            $form_id = $results[0]->form_id;

            $cfdb->query("UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id' LIMIT 1");
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    do_action('cfdb7_after_formdetails', $this->form_post_id);
  }

  public function form_details_offline() {
    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $upload_dir = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'] . '/gap_cf7_uploads';
    $rm_underscore = apply_filters('cfdb7_remove_underscore_data', true);

    $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_post_id = $this->form_post_id AND form_id = $this->form_id LIMIT 1", OBJECT);


    if (empty($results)) {
      wp_die($message = 'Not valid contact form');
    }
    ?>
    <div class="wrap" style="font-size: 110%">
      <?php do_action('cfdb7_before_formdetails_title', $this->form_post_id); ?>
      <h3><?php echo get_the_title($this->form_post_id); ?></h3>
      <?php do_action('cfdb7_after_formdetails_title', $this->form_post_id); ?>
      <p></span><?php echo $results[0]->form_date; ?></p>
      <?php $form_data = unserialize($results[0]->form_value); ?>

      <div class="row">
        <div class="col-lg-4">
          <h3>Khách hàng</h3>
          <hr>
          <p><strong>Thời gian</strong>:
            <?php printf('Ngày <b>%s</b> lúc <b>%s</b>', $form_data['gap_date'], $form_data['gap_time']); ?></p>
          <p><strong>Họ tên</strong>: <?php echo $form_data['name']; ?></p>
          <p><strong>Số điện thoại</strong>: <a target="_blank"
              href="https://zalo.me/<?php echo $form_data['phone']; ?>"><?php echo $form_data['phone']; ?></a></p>
          <p><strong>Số lượng</strong>: <?php echo $form_data['quantity']; ?></p>
        </div>
        <div class="col-lg-8">
          <h3>Sản phẩm</h3>
          <hr>

          <?php if ($form_data['clothes_checkbox'][0] === 'Quần áo'): ?>
            <h4>Quần áo</h4>
            <ul>
              <li>Có thương hiệu (local/global): <b><?php echo $form_data['clothes_has_brand'][0]; ?></b></li>
              <li>Số lượng tối thiểu > 5: <b><?php echo $form_data['clothes_min_quantity_5'][0]; ?></b></li>
            </ul>
          <?php endif; ?>

          <?php if ($form_data['bagshoe_checkbox'][0] === 'Túi&#047;giày'): ?>
            <hr>
            <h4>Túi/giày</h4>
            <ul>
              <li>Sản phẩm bạn ký gửi thuộc thương hiệu: <b><?php echo $form_data['bagshoe_brands']; ?></b></li>
              <li>Tình trạng sản phẩm còn mới từ 80%: <b><?php echo $form_data['bagshoe_new_80'][0]; ?></b></li>
            </ul>
          <?php endif; ?>

          <?php if ($form_data['cosmeticperfume_checkbox'][0] === 'Mỹ phẩm&#047;nước hoa'): ?>
            <hr>
            <h4>Mỹ phẩm/nước hoa</h4>
            <ul>
              <li>Còn date > 6 tháng: <b><?php echo $form_data['cosmeticperfume_date_6'][0]; ?></b></li>
              <li>Dung tích > 80%: <b><?php echo $form_data['cosmeticperfume_cap_80'][0]; ?></b></li>
            </ul>
          <?php endif; ?>

          <?php if ($form_data['accessory_checkbox'][0] === 'Phụ kiện (kính mắt, trang sức)'): ?>
            <hr>
            <h4>Phụ kiện (kính mắt, trang sức)</h4>
            <ul>
              <li>Tình trạng còn mới > 80: <b><?php echo $form_data['accessory_new_80'][0]; ?></b></li>
            </ul>
          <?php endif; ?>

        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">

        </div>

        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">

        </div>
      </div>
      <?php
      $form_data['gap_status'] = 'read';
      $form_data = serialize($form_data);
      $form_id = $results[0]->form_id;

      $cfdb->query("UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id' LIMIT 1");
      ?>

    </div>
    <?php
    do_action('cfdb7_after_formdetails', $this->form_post_id);
  }

  protected function key_to_string($key) {
    $key_strings = [
      'name' => 'Họ tên',
      'phone' => 'Số điện thoại',
      'zalo_name' => 'Tên Zalo',
      'you_know' => 'Bạn biết GAP',
      'desired_price' => 'Giá bán mong muốn',
      'pickup_address' => 'Địa chỉ lấy hàng',

      //Quần áo new
      'clothes_new_local' => 'Quần áo mới Local',
      'clothes_new_local_brand' => 'Quần áo mới Local Brand',
      'clothes_new_global' => 'Quần áo mới Global',
      'clothes_new_global_brand' => 'Quần áo mới Global Brand',
      'clothes_photo_gap_file' => 'Hình quần áo',

      //Quần áo used
      'clothes_used_80' => 'Quần áo cũ mới > 80%',
      'clothes_used_local' => 'Quần áo cũ Local',
      'clothes_used_local_brand' => 'Quần áo cũ Local Brand',
      'clothes_used_global' => 'Quần áo cũ Global',
      'clothes_used_global_brand' => 'Quần áo cũ Global Brand',

      //Túi
      'bag_new' => 'Túi mới',
      'bag_new_brand' => 'Túi mới Brand',
      'bag_used' => 'Túi cũ',

      'bag_used_90' => 'Túi cũ mới > 90%',
      'bag_used_brand' => 'Túi cũ Brand',
      'bag_photo' => 'Hình túi',
      'bag_photo_gap_file' => 'Hình túi',

      //Giày
      'shoe_new' => 'Giày mới',
      'shoe_new_brand' => 'Giày mới Brand',
      'shoe_used' => 'Giày cũ',
      'shoe_used_brand' => 'Giày cũ Brand',
      'shoe_used_90' => 'Giày củ còn mới > 90%',
      'shoe_photo_gap_file' => 'Hình giày',

      //Mỹ phẩm
      'cosmetic_new_6' => 'Mỹ phẩm mới date > 6 tháng',
      'cosmetic_new' => 'Mỹ phẩm mới',
      'cosmetic_new_brand' => 'Mỹ phẩm mới Brand',

      'cosmetic_used_6' => 'Mỹ phẩm cũ date > 6 tháng',
      'cosmetic_used_capacity_80' => 'Mỹ phẩm cũ dung tích > 80%',
      'cosmetic_used' => 'Mỹ phẩm cũ',
      'cosmetic_used_brand' => 'Mỹ phẩm cũ Brand',
      'cosmetic_photo_gap_file' => 'Hình mỹ phẩm',

      //Nước hoa
      'perfume_new' => 'Nước hoa mới',
      'perfume_new_brand' => 'Nước hoa mới Brand',

      'perfume_used' => 'Nước hoa cũ',
      'perfume_used_brand' => 'Nước hoa cũ Brand',
      'perfume_used_capacity_80' => 'Nước hoa cũ > 80%',
      'perfume_photo_gap_file' => 'Hình nước hoa'
      // 'cosmetic_used_capacity_80' => 'Mỹ phẩm cũ dung tích > 80%',
      // 'cosmetic_used' => 'Mỹ phẩm cũ',
      // 'cosmetic_used_brand' => 'Mỹ phẩm cũ Brand',
    ];

    return isset($key_strings[$key]) ? $key_strings[$key] : $key;

  }

  public function form_details_page_others() {
    global $wpdb;
    $cfdb = apply_filters('cfdb7_database', $wpdb);
    $table_name = $cfdb->prefix . 'gap_cf7';
    $upload_dir = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'] . '/gap_cf7_uploads';
    $rm_underscore = apply_filters('cfdb7_remove_underscore_data', true);


    $results = $cfdb->get_results("SELECT * FROM $table_name WHERE form_post_id = $this->form_post_id AND form_id = $this->form_id LIMIT 1", OBJECT);


    if (empty($results)) {
      wp_die($message = 'Not valid contact form');
    }
    ?>
    <div class="wrap">
      <div id="welcome-panel" class="cfdb7-panel">
        <div class="cfdb7-panel-content">
          <div class="welcome-panel-column-container">
            <?php do_action('cfdb7_before_formdetails_title', $this->form_post_id); ?>
            <h3><?php echo get_the_title($this->form_post_id); ?></h3>
            <?php do_action('cfdb7_after_formdetails_title', $this->form_post_id); ?>
            <p></span><?php echo $results[0]->form_date; ?></p>
            <?php $form_data = unserialize($results[0]->form_value);

            foreach ($form_data as $key => $data):

              $matches = array();
              $key = esc_html($key);

              if ($key == 'gap_status')
                continue;
              if ($rm_underscore)
                preg_match('/^_.*$/m', $key, $matches);
              if (!empty($matches[0]))
                continue;

              if (strpos($key, '_gap_file') !== false) {

                $key_val = str_replace('_gap_file', '', $key);
                $key_val = str_replace('your-', '', $key_val);
                $key_val = str_replace(array('-', '_'), ' ', $key_val);
                $key_val = ucwords($key_val);
                echo '<p><b>' . $key_val . '</b>: <a href="' . $cfdb7_dir_url . '/' . $data . '">'
                  . $data . '</a></p>';
              } else {


                if (is_array($data)) {

                  $key_val = str_replace('your-', '', $key);
                  $key_val = str_replace(array('-', '_'), ' ', $key_val);
                  $key_val = ucwords($key_val);
                  $arr_str_data = implode(', ', $data);
                  $arr_str_data = esc_html($arr_str_data);
                  echo '<p><b>' . $key_val . '</b>: ' . nl2br($arr_str_data) . '</p>';

                } else {

                  $key_val = str_replace('your-', '', $key);
                  $key_val = str_replace(array('-', '_'), ' ', $key_val);

                  $key_val = ucwords($key_val);
                  $data = esc_html($data);
                  echo '<p><b>' . $key_val . '</b>: ' . nl2br($data) . '</p>';
                }
              }

            endforeach;

            $form_data['gap_status'] = 'read';
            $form_data = serialize($form_data);
            $form_id = $results[0]->form_id;

            $cfdb->query("UPDATE $table_name SET form_value =
                        '$form_data' WHERE form_id = '$form_id' LIMIT 1"
            );
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    do_action('cfdb7_after_formdetails', $this->form_post_id);
  }

}