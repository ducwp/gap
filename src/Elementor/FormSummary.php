<?php
namespace GAPTheme\Elementor;

use ElementorPro\Modules\Forms\Classes\Action_Base;

use NumberFormatter;

class FormSummary extends Action_Base {
  public function get_name() {
    return 'form-summary';
  }

  public function get_label() {
    return __('Xem tổng kết', 'gap-theme');
  }

  /**
   * @param \Elementor\Widget_Base $widget
   */
  public function register_settings_section($widget) {

  }

  /**
   * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
   * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
   */
  public function run($record, $ajax_handler) {
    global $wpdb;

    //$ajax_handler->data['pre_amount'];
    $phone = $_POST['form_fields']['phone'];
    $verificationId = $_POST['form_fields']['verificationId'];
    $otp = trim($_POST['form_fields']['zalo_otp']);
    if (empty($otp))
      $ajax_handler->add_error_message('Vui lòng nhập mã OTP');

    $response = \GAPtheme\Firebase::instance()->activate_through_firebase($verificationId, $otp);
    if ($response->error && $response->error->code == 400) {
      error_log($response->error->message);
      /* wp_send_json_error([
        'success' => false,
        'phone_number' => $phone_number,
        'firebase' => $response->error,
        'message' => __('entered code is wrong!', 'login-with-phone-number')
      ]); */
      $ajax_handler->add_error_message('Sai mã OTP!!!');
      return;
    }

    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name
    $results = $wpdb->get_results("SELECT * FROM  $db_table_name WHERE so_dien_thoai='$phone'");
    if ($wpdb->last_error) {
      $ajax_handler->add_error_message(sprintf('Error: %s', $wpdb->last_error));
    }

    if (empty($results)) {
      $summary_result = '<h4 style="text-align: center">Xin lỗi, hiện tại hóa đơn của bạn chưa đến kỳ tổng kết.</h4>';
    } else {
      $html = '<h4 style="text-align: center; margin: 20px 0">Kết quả tổng kết</h4>';
      $html .= '<ul data-products="type-1" class="products columns-3">';
      $xtk_modal = '';
      $ths_arr_1 = ['Mã ký gửi', 'Họ tên khách hàng', 'Số điện thoại', 'P.thức thanh toán', 'Ngày thanh toán', 'Ngày TPTK'];
      $ths_arr_2 = ['Số tài khoản', 'Ngân hàng', 'Ký gửi', 'Bán', 'Tồn', 'Doanh thu', 'Phí', 'Thực nhận'];
      $ths_1 = '<th>' . join('</th><th>', $ths_arr_1) . '</th>';
      $ths_2 = '<th>' . join('</th><th>', $ths_arr_2) . '</th>';

      $text = 'Lập lệnh chuyển khoản theo thứ tự mã ký gửi, thời gian nhận được thanh toán sẽ từ ngày 10-20/09/2023.<ul>
      <li>Khách hàng chọn phương thức chuyển khoản, kế toán sẽ lập lệnh thanh toán cho mình qua số tài khoản đã cung cấp.</li>
      <li>Khách hàng chọn phương thức tiền mặt, vui lòng đến đúng hẹn trong biên nhận ký gửi (10-20/09/2023).</li>
      <li>DANH SÁCH SẼ ĐƯỢC CHỐT VÀO 10 GIỜ NGÀY 9 HÀNG THÁNG, VUI LÒNG KHÔNG ĐỔI PHƯƠNG THỨC SAU KHI DANH SÁCH ĐƯỢC CHỐT</li>
      </ul>';
      
      $img = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/assets/img/xtk_info.jpg');

      foreach ($results as $row) {
        $time_tt = strtotime($row->ngay_thanh_toan);
        $ngay_thanh_toan = date('d/m/Y', $time_tt);

        $time_tptk = strtotime($row->ngay_tinh_phi_ton_kho);
        $ngay_tinh_phi_ton_kho = date('d/m/Y', $time_tptk);

        $text = sprintf('<div class="modal_xtk_img_box"><span class="date1">%s</span> <span class="date2">%s</span> <span class="date3">%s</span>%s</div>', $ngay_thanh_toan, $ngay_thanh_toan, $ngay_tinh_phi_ton_kho , $img);

        $time = strtotime($row->ngay_ky_gui);
        $ngay_ky_gui = date('d/m/Y', $time);

        $html .= '<li class="product xem_tong_ket">';
        $html .= sprintf('<div class="xtk_header">%s</div>', $row->ma_ky_gui);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Ngày ký gửi: </span><span class="xtk_value">%s</span></div>', $ngay_ky_gui);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Số điện thoại: </span><span class="xtk_value">%s</span></div>', $row->so_dien_thoai);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Họ và tên: </span><span class="xtk_value">%s</span></div>', $row->ho_va_ten);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Thực nhận: </span><span class="xtk_value"><b>%s</b> ₫</span></div>', $row->thuc_nhan);
        $html .= sprintf('<div class="xtk_footer"><a href="#xtk_detail_%s" rel="modal:open" class="button button-xtk-detail">Xem chi tiết</a></div>', $row->id);
        $html .= '</li>';

        $i = 1;
        $td_1 = '';
        $td_2 = '';
        $aa = array_values((array) $row);

        for ($i = 2; $i < count($aa); $i++) {
          if ($i < 8)
            $td_1 .= sprintf('<td>%s</td>', $aa[$i]);
          else
            $td_2 .= sprintf('<td>%s</td>', $aa[$i]);
        }


        $xtk_modal .= sprintf('<div id="xtk_detail_%s" class="modal modal_xtk_detail"><div id="contentToPrint">', $row->id);
        $xtk_modal .= '<h3 class="modal_xtk_detail_header">Hóa đơn chi tiết bán hàng</h3>';
        $xtk_modal .= sprintf('<table><thead><tr>%s</tr></thead><tbody>', $ths_1);
        $xtk_modal .= sprintf('<tr>%s</tr>', $td_1);
        $xtk_modal .= '</tbody></table><br>';

        $xtk_modal .= sprintf('<table><thead><tr>%s</tr></thead><tbody>', $ths_2);
        $xtk_modal .= sprintf('<tr>%s</tr>', $td_2);
        $xtk_modal .= '</tbody></table><br>';
        $xtk_modal .= $text . '</div>';
        $xtk_modal .= sprintf('<p class="xtk_detail_download_btn"><a onclick="gap_html2pdf();" href="#" class="xbutton"><img src="%s" /> Tải về</a></p>', get_stylesheet_directory_uri() . '/assets/img/download.svg');
        $xtk_modal .= '</div>';
      }
      $html .= '</ul>';
      $summary_result = $html . $xtk_modal;
      //$summary_result = json_encode($results);
    }

    $ajax_handler->add_response_data('summary_result', $summary_result);
    #$ajax_handler->add_response_data('success_image', $settings['success_image']['url']);
  }

  public function on_export($element) {
    return $element;
  }

}