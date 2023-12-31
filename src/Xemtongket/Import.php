<?php

namespace GAPTheme\Xemtongket;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Import {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('admin_enqueue_scripts', [$this, 'scripts']);
    add_filter('attachment_fields_to_edit', [$this, 'add_fields'], 10, 2);
    add_action('wp_ajax_' . 'summary_import_xlxs', [$this, 'summary_import_xlxs']);
    add_action('wp_ajax_' . 'summary_approve_xlxs', [$this, 'summary_approve_xlxs']);
    add_action('delete_attachment', [$this, 'delete_attachment']);
    //register_shutdown_function([$this, "check_abort"]);
  }

  public function scripts() {
    wp_enqueue_script('gap-summary', get_stylesheet_directory_uri() . '/assets/js/summary.js', ['jquery'], '', true);
  }

  public function add_fields($form_fields, $post) {

    if ($post->post_type !== 'attachment' || $post->post_mime_type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
      return $form_fields;

    $form_fields['import_xlxs'] = array(
      'label' => esc_html__('Import file tổng kết', 'gap-theme'),
      'input' => 'html',
      'html' => sprintf('<button type="button" id="btnImportXLXSFile" data-id="%s" class="button">%s</button> <button id="btnCancelImport" class="button hidden">Hủy</button>', $post->ID, __('Nhập vào')),
      'helps' => __('Click vào nút trên để bắt đầu import file tổng kết.'),
    );

    if (!current_user_can('administrator'))
      return $form_fields;

    $form_fields['review_xlxs'] = array(
      'label' => esc_html__('Duyệt file tổng kết', 'gap-theme'),
      'input' => 'html',
      'html' => sprintf('<button type="button" id="btnReviewXLXSFile" data-id="%s" class="button">%s</button>', $post->ID, __('Phê duyệt')),
      'helps' => __('Click vào nút trên duyệt file tổng kết.'),
    );

    return $form_fields;
  }

  public function summary_import_xlxs() {
    header("Cache-Control: no-store");
    header("Content-Type: text/event-stream");

    global $wpdb;
    $excel_file_id = absint($_POST['file_id']);
    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name
    $reader = new Xlsx();
    $spreadsheet = $reader->load(get_attached_file($excel_file_id));
    $worksheet = $spreadsheet->getActiveSheet();

    foreach ($worksheet->getRowIterator(2) as $row) {
      @ob_end_flush();
      flush();

      $ngay_ky_gui = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getFormattedValue();
      if (empty($ngay_ky_gui))
        break;
      //$ngay_ky_gui = join('-', array_reverse(explode('/', $ngay_ky_gui)));
      $ma_ky_gui = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getFormattedValue();

      /* $ok = preg_replace('~\D~', '', $ma_ky_gui);
      $m = substr($ok, 0, 2);
      $y = substr($ok, 2, 2);
      $date = sprintf('15-%s-20%s', $m, $y);
      $ngay_tong_ket = date('Y-m-d', strtotime($date)); */

      $file = get_post($excel_file_id);
      $ngay_tong_ket = date('Y-m-d', strtotime($file->post_date));

      $ho_va_ten = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getFormattedValue();
      $so_dien_thoai = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getFormattedValue();
      $pthuc_thanhtoan = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getFormattedValue();
      $ngay_thanh_toan = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getFormattedValue();
      //$ngay_thanh_toan = join('-', array_reverse(explode('/', $ngay_thanh_toan)));
      $ngay_tinh_phi_ton_kho = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getFormattedValue();
      //$ngay_tinh_phi_ton_kho = join('-', array_reverse(explode('/', $ngay_tinh_phi_ton_kho)));
      $so_tai_khoan = $spreadsheet->getActiveSheet()->getCell('H' . $row->getRowIndex())->getFormattedValue();
      $ngan_hang = $spreadsheet->getActiveSheet()->getCell('I' . $row->getRowIndex())->getFormattedValue();
      $ky_gui = $spreadsheet->getActiveSheet()->getCell('K' . $row->getRowIndex())->getFormattedValue();
      $ban = $spreadsheet->getActiveSheet()->getCell('K' . $row->getRowIndex())->getFormattedValue();
      $ton = $spreadsheet->getActiveSheet()->getCell('L' . $row->getRowIndex())->getFormattedValue();
      $doanh_thu = $spreadsheet->getActiveSheet()->getCell('M' . $row->getRowIndex())->getFormattedValue();
      $phi = $spreadsheet->getActiveSheet()->getCell('N' . $row->getRowIndex())->getFormattedValue();
      $thuc_nhan = $spreadsheet->getActiveSheet()->getCell('O' . $row->getRowIndex())->getFormattedValue();
      $tinh_trang_thanh_toan = $spreadsheet->getActiveSheet()->getCell('P' . $row->getRowIndex())->getFormattedValue();

      $data = array(
        'ngay_ky_gui' => $ngay_ky_gui,
        'ma_ky_gui' => $ma_ky_gui,
        'ngay_tong_ket' => $ngay_tong_ket,
        'ho_va_ten' => $ho_va_ten,
        'so_dien_thoai' => $so_dien_thoai,
        'phuong_thuc_thanh_toan' => $pthuc_thanhtoan,
        'ngay_thanh_toan' => $ngay_thanh_toan,
        'ngay_tinh_phi_ton_kho' => $ngay_tinh_phi_ton_kho,
        'so_tai_khoan' => $so_tai_khoan,
        'ngan_hang' => $ngan_hang,
        'ky_gui' => $ky_gui,
        'ban' => $ban,
        'ton' => $ton,
        'doanh_thu' => $doanh_thu,
        'phi' => $phi,
        'thuc_nhan' => $thuc_nhan,
        'tinh_trang_thanh_toan' => $tinh_trang_thanh_toan,
        'file_id' => $excel_file_id,
      );

      if ($this->is_update($ma_ky_gui)) {
        $wpdb->update($db_table_name, $data, ['ma_ky_gui' => $ma_ky_gui]);
      } else
        $wpdb->insert($db_table_name, $data);

      //$link = sprintf('[<a href="%s" target="_blank">%s</a>]', get_permalink($id), $id);
      printf('<p>%s - %s - %s</p>', $ma_ky_gui, $ho_va_ten, $so_dien_thoai);

      // Break the loop if the client aborted the connection (closed the page)
      if (connection_aborted())
        break;

      usleep(500000);
    }
  }

  public function is_update($ma_ky_gui) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gap_summary'; // table name
    $total_rows = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ma_ky_gui = '$ma_ky_gui' ");
    if ($total_rows > 0)
      return true;
    return false;

    // $id = stripslashes_deep($_POST['id']); //added stripslashes_deep which removes WP escaping.
    // $title = stripslashes_deep($_POST['title']);
    // $message = stripslashes_deep($_POST['message']);

    // $wpdb->update('table_name', array('id' => $id, 'title' => $title, 'message' => $message), array('id' => $id));
  }


  public function summary_approve_xlxs() {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name
    $file_id = $_POST['file_id'];
    try {
      $update = $wpdb->update($db_table_name, ['status' => 'done'], ['file_id' => $file_id]);
      wp_send_json_success($update);
    } catch (Exception $e) {
      wp_send_json_error($e->get_error_message());
    }
  }

  public function delete_attachment($post_id) {
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name
    $delete = $wpdb->delete($db_table_name, ['file_id' => $post_id]);
  }

  public function check_abort() {
    if (connection_aborted())
      error_log("Tiến trình bị người dùng hủy.");
  }
}