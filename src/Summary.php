<?php

namespace GAPTheme;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Summary {
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
    register_shutdown_function([$this, "check_abort"]);
  }

  public function scripts() {
    wp_enqueue_script('gap-summary', get_stylesheet_directory_uri() . '/assets/js/summary.js', ['jquery'], '', true);
  }

  public function add_fields($form_fields, $post) {
    if ($post->post_type !== 'attachment' || $post->post_mime_type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
      return $form_fields;

    $form_fields['import_xlxs'] = array(
      'label' => esc_html__('Import file tổng kết', 'lazaff'),
      'input' => 'html',
      'html' => sprintf('<button type="button" id="btnImportXLXSFile" data-id="%s" class="button">%s</button> <button id="btnCancelImport" class="button hidden">Cancel</button>', $post->ID, __('Import Now')),
      'helps' => __('Click vào nút trên để bắt đầu import file tổng kết.'),
    );

    return $form_fields;
  }

  public function summary_import_xlxs() {

    header("Cache-Control: no-store");
    header("Content-Type: text/event-stream");

    global $wpdb;
    $db_table_name = $wpdb->prefix . 'gap_summary'; // table name

    //$platform = 'lazada';
    $file_id = absint($_POST['file_id']);
    $reader = new Xlsx();
    $spreadsheet = $reader->load(get_attached_file($file_id));
    $worksheet = $spreadsheet->getActiveSheet();

    $a1 = $spreadsheet->getActiveSheet()->getCell('A1')->getFormattedValue();
    $platform = $a1 === 'date' ? 'lazada' : 'shopee';

    foreach ($worksheet->getRowIterator(2) as $row) {
      @ob_end_flush();
      flush();

      $ngay_ky_gui = $spreadsheet->getActiveSheet()->getCell('A' . $row->getRowIndex())->getFormattedValue();
      $ma_ky_gui = $spreadsheet->getActiveSheet()->getCell('B' . $row->getRowIndex())->getFormattedValue();
      $ho_va_ten = $spreadsheet->getActiveSheet()->getCell('C' . $row->getRowIndex())->getFormattedValue();
      $so_dien_thoai = $spreadsheet->getActiveSheet()->getCell('D' . $row->getRowIndex())->getFormattedValue();
      $so_tai_khoan = $spreadsheet->getActiveSheet()->getCell('E' . $row->getRowIndex())->getFormattedValue();
      $ngan_hang = $spreadsheet->getActiveSheet()->getCell('F' . $row->getRowIndex())->getFormattedValue();
      $ky_gui = $spreadsheet->getActiveSheet()->getCell('G' . $row->getRowIndex())->getFormattedValue();
      $ban = $spreadsheet->getActiveSheet()->getCell('H' . $row->getRowIndex())->getFormattedValue();
      $ton = $spreadsheet->getActiveSheet()->getCell('I' . $row->getRowIndex())->getFormattedValue();
      $doanh_thu = $spreadsheet->getActiveSheet()->getCell('J' . $row->getRowIndex())->getFormattedValue();
      $phi = $spreadsheet->getActiveSheet()->getCell('K' . $row->getRowIndex())->getFormattedValue();
      $thuc_nhan = $spreadsheet->getActiveSheet()->getCell('L' . $row->getRowIndex())->getFormattedValue();


      $data = array(
        'ngay_ky_gui' => $ngay_ky_gui,
        'ma_ky_gui' => $ma_ky_gui,
        'ho_va_ten' => $ho_va_ten,
        'so_dien_thoai' => $so_dien_thoai,
        'so_tai_khoan' => $so_tai_khoan,
        'ngan_hang' => $ngan_hang,
        'ky_gui' => $ky_gui,
        'ban' => $ban,
        'ton' => $ton,
        'doanh_thu' => $doanh_thu,
        'phi' => $phi,
        'thuc_nhan' => $thuc_nhan,

      );
      
      $id = $wpdb->insert($db_table_name, $data);

      //$link = sprintf('[<a href="%s" target="_blank">%s</a>]', get_permalink($id), $id);
      printf('<p>%s - %s - %s</p>', $row->getRowIndex(), $ma_ky_gui, $ho_va_ten, $so_dien_thoai);

      // Break the loop if the client aborted the connection (closed the page)
      if (connection_aborted())
        break;

      usleep(500000);
    }
  }


  public function check_abort() {
    if (connection_aborted())
      error_log("Script was aborted by the user.");
  }

  public function faul_clean($str) {
    return @preg_replace('/[\x00-\x1F\x7F]/u', '', $str);
  }
  public function check_product_exists($sku) {
    $query = new \WC_Product_Query(
      array(
        'sku' => $sku,
      )
    );

    $products = $query->get_products();
    $product = reset($products);
    if (!empty($product))
      return true;

    return false;
  }

  public function getAmount($money) {
    $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
    $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

    return (float) str_replace(',', '.', $removedThousandSeparator);
  }

}