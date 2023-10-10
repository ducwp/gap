<?php
//Phuong thuc ky gui
namespace GAPTheme\Phuongthuckygui;

use ElementorPro\Modules\Forms\Classes\Action_Base;
use NumberFormatter;

class Elementor extends Action_Base {
  public function get_name() {
    return 'phuong-thuc-ky-gui';
  }

  public function get_label() {
    return __('Phương thức Ký gửi', 'elmformaction');
  }

  /**
   * @param \Elementor\Widget_Base $widget
   */
  public function register_settings_section($widget) {
    /* $widget->start_controls_section(
      'section_custom',
      [
        'label' => __('Custom', 'elmformaction'),
        'condition' => [
          'submit_actions' => $this->get_name(),
        ],
      ]
    );

    $widget->add_control(
      'success_image',
      [
        'label' => __('Success Image', 'elmformaction'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'label_block' => true,
        'separator' => 'before',
        'description' => __('Select the image to be displayed after the form is submitted successfully.', 'elmformaction'),
      ]
    ); */

    //$widget->end_controls_section();
  }

  /**
   * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
   * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
   */
  public function run($record, $ajax_handler) {
    $settings = $record->get('form_settings');


    /* if (empty($settings['success_image'])) {
      return;
    } */
    //$ajax_handler->data['pre_amount'];
    $luxury_brand = isset($_POST['form_fields']['luxury_brand']) ? $_POST['form_fields']['luxury_brand'] : '';
    $pre_amount = absint($_POST['form_fields']['pre_amount']);

    if ($pre_amount < 100000) {
      $ajax_handler->add_error_message('Vui lòng nhập số tiền từ 100.000');
    }

    switch ($pre_amount) {
      case ($pre_amount < 1000000):
        $percent = 0.26;
        break;
      case (1000000 <= $pre_amount && $pre_amount < 10000000):
        $percent = 0.23;
        break;
      default:
        $percent = 0.1;
        break;
    }

    if ($luxury_brand === 'yes')
      $percent = 0.5;

    $post_amout = $pre_amount - ($pre_amount * $percent);
    $fmt = new NumberFormatter('vi_VN', NumberFormatter::CURRENCY);
    $post_amout = $fmt->formatCurrency($post_amout, "VND");

    $ajax_handler->add_response_data('pre_amount', $pre_amount);
    $ajax_handler->add_response_data('post_amount', $post_amout);
    #$ajax_handler->add_response_data('success_image', $settings['success_image']['url']);
  }

  private function cal_percent($amount, $percent) {
    return $amount - ($amount * $percent);
  }

  public function on_export($element) {
    return $element;
  }
}