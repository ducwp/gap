<?php
namespace GAPTheme\Xemtongket;

class View {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_shortcode('xemtongket', [$this, 'view_data']);
  }

  public function view_data($record, $ajax_handler) {
    global $wpdb;

    $current_user = wp_get_current_user();
    $phone = $current_user->billing_phone;

    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name
    //$results = $wpdb->get_results("SELECT * FROM  $db_table_name WHERE (MONTH(ngay_ky_gui) = '$curmon' AND so_dien_thoai='$phone') ");
    $results = $wpdb->get_results("SELECT * FROM  $db_table_name WHERE MONTH(ngay_tong_ket) >= MONTH(NOW()-interval 2 month) AND so_dien_thoai='$phone' AND status='done'");
    if ($wpdb->last_error) {
      $ajax_handler->add_error_message(sprintf('Error: %s', $wpdb->last_error));
    }

    if (empty($results)) {
      return $summary_result = $phone. '<h4 style="text-align: center">Xin lỗi, hiện tại hóa đơn của bạn chưa đến kỳ tổng kết.</h4>';
    } else {
      $html = '<h4 style="text-align: center; margin: 20px 0">Kết quả tổng kết</h4>';
      $html .= '<ul data-products="type-1" class="products columns-3">';
      $xtk_modal = '';
      $ths_arr_1 = ['Mã ký gửi', 'Khách hàng', 'Số điện thoại', 'P.thức thanh toán', 'Ngày thanh toán', 'Ngày Tính phí tổng kết'];
      $ths_arr_2 = ['Số tài khoản', 'Ngân hàng', 'Ký gửi', 'Bán', 'Tồn', 'Doanh thu', 'Phí', 'Thực nhận', 'Tình trạng thanh toán'];
      $ths_1 = '<th>' . join('</th><th>', $ths_arr_1) . '</th>';
      $ths_2 = '<th>' . join('</th><th>', $ths_arr_2) . '</th>';

      $text_top = 'Lập lệnh chuyển khoản theo thứ tự mã ký gửi, thời gian nhận được thanh toán sẽ từ ngày <b>{NGAY_THANH_TOAN}</b>.<ul class="text_top" style="padding: 0; padding-left: 20px;"><li>Khách hàng chọn phương thức chuyển khoản, kế toán sẽ lập lệnh thanh toán cho mình qua số tài khoản đã cung cấp.</li><li>Khách hàng chọn phương thức tiền mặt, vui lòng đến đúng hẹn trong biên nhận ký gửi <b>{NGAY_THANH_TOAN}</b>.</li><li>DANH SÁCH SẼ ĐƯỢC CHỐT VÀO 10 GIỜ NGÀY 9 HÀNG THÁNG, VUI LÒNG KHÔNG ĐỔI PHƯƠNG THỨC SAU KHI DANH SÁCH ĐƯỢC CHỐT</li></ul>';

      $text_right = '<ul>';
      $text_right .= '<li><b>File tổng kết sẽ không bao gồm thông tin chi tiết sản phẩm còn tồn (nếu có)</b>. Quý khách vui lòng kiểm tra kỹ số tiền và số lượng hàng tồn đã nhận tại quầy.</li>';
      $text_right .= '<li>Sau khi rời quầy, mọi thắc mắc, khiếu nại shop sẽ không giải quyết. Rủi ro hư hại trong quá trình thử đồ là không tránh khỏi và ngoài tầm kiểm soát của cửa hàng. Mong các bạn thông cảm!</li>';
      $text_right .= '<li>Nếu còn sản phẩm tồn, các chị đến shop nhận lại sản phẩm tồn kèm theo biên nhận ký gửi ạ.</li>';
      $text_right .= '<li>Sau ngày <b>{NGAY_TINH_PHI_TON_KHO}</b> sẽ tính phí tồn kho cho các sản phẩm tồn ạ.</li>';
      $text_right .= '<li>Phí tồn kho: 50.000vnđ/1 mã ký gửi/1 tháng</li>';
      $text_right .= '</ul>';

      $img_bieu_phi = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/assets/img/bieu_phi.png');
      $img_footer = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/assets/img/footer.png');
      $img = sprintf('<img src="%s" />', get_stylesheet_directory_uri() . '/assets/img/xtk_info.jpg');

      foreach ($results as $row) {
        $time_tt = strtotime($row->ngay_thanh_toan);
        $ngay_thanh_toan = date('d/m/Y', $time_tt);
        $text_top = str_replace('{NGAY_THANH_TOAN}', $ngay_thanh_toan, $text_top);

        $time_tptk = strtotime($row->ngay_tinh_phi_ton_kho);
        $ngay_tinh_phi_ton_kho = date('d/m/Y', $time_tptk);
        $text_right = str_replace('{NGAY_TINH_PHI_TON_KHO}', $ngay_tinh_phi_ton_kho, $text_right);

        /* $text = sprintf('<div class="modal_xtk_img_box"><span class="date1">%s</span> <span class="date2">%s</span> <span class="date3">%s</span>%s</div>', $ngay_thanh_toan, $ngay_thanh_toan, $ngay_tinh_phi_ton_kho , $img); */

        // $time = strtotime($row->ngay_ky_gui);
        // $ngay_ky_gui = date('d/m/Y', $time);
        $ngay_ky_gui = $row->ngay_ky_gui;

        $tinh_trang_thanh_toan = !empty($row->tinh_trang_thanh_toan) ? $row->tinh_trang_thanh_toan : 'N/A';
        $html .= '<li class="product xem_tong_ket">';
        $html .= sprintf('<div class="xtk_header">%s</div>', $row->ma_ky_gui);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Ngày ký gửi: </span><span class="xtk_value">%s</span></div>', $ngay_ky_gui);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Số điện thoại: </span><span class="xtk_value">%s</span></div>', $row->so_dien_thoai);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Họ và tên: </span><span class="xtk_value">%s</span></div>', $row->ho_va_ten);
        $html .= sprintf('<div class="xtk_row"><span class="xtk_title">Thực nhận: </span><span class="xtk_value"><b>%s</b> ₫</span></div>', $row->thuc_nhan);
        //$html .= sprintf('<div class="xtk_row"><span class="xtk_title">Tình trạng thanh toán: </span><span class="xtk_value"><b>%s</b></span></div>', $tinh_trang_thanh_toan);
        $html .= '<div class="xtk_footer">';
        $html .= sprintf('<a href="#xtk_detail_%s" rel="modal:open" class="button button-xtk-detail">Xem chi tiết</a>', $row->id);

        $html .= sprintf('<a onclick="gap_html2pdf();" href="#" class="button button-download-mobile">Tải về</a>');

        $html .= '</div>';

        $html .= '</li>';

        $i = 1;
        $td_1 = '';
        $td_2 = '';
        $aa = array_values((array) $row);

        for ($i = 2; $i < count($aa); $i++) {
          if ($i == 3)
            continue;
          if ($i < 9)
            $td_1 .= sprintf('<td>%s</td>', $aa[$i]);
          elseif ($i > 9 && $i <= 17)
            $td_2 .= sprintf('<td>%s</td>', $aa[$i]);
          else
            continue;
        }


        $xtk_modal .= sprintf('<div id="xtk_detail_%s" class="modal modal_xtk_detail"><div id="contentToPrint">', $row->id);
        $xtk_modal .= '<h3 class="modal_xtk_detail_header">Hóa đơn chi tiết bán hàng</h3>';
        $xtk_modal .= sprintf('<table><thead><tr>%s</tr></thead><tbody>', $ths_1);
        $xtk_modal .= sprintf('<tr>%s</tr>', $td_1);
        $xtk_modal .= '</tbody></table><br>';

        $xtk_modal .= sprintf('<table><thead><tr>%s</tr></thead><tbody>', $ths_2);
        $xtk_modal .= sprintf('<tr>%s</tr>', $td_2);
        $xtk_modal .= '</tbody></table><br>';
        $xtk_modal .= $text_top;
        $xtk_modal .= '<div class="modal_xtk_box"><div class="col_left" style="float: left; width: 50%;">' . $img_bieu_phi . '</div>';
        $xtk_modal .= '<div class="col_right" style="float: right; width: 50%;">' . $text_right . '</div><br style="clear: both"></div>';
        $xtk_modal .= $img_footer . '</div>';
        $xtk_modal .= sprintf('<p class="xtk_detail_download_btn"><a onclick="gap_html2pdf();" href="#" class="xbutton"><img src="%s" /> Tải về</a></p>', get_stylesheet_directory_uri() . '/assets/img/download.svg');



        $xtk_modal .= '</div>';
      }
      $html .= '</ul>';

      $summary_result = $html . $xtk_modal;

      return $summary_result;
    }
  }
}