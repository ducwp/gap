<?php

namespace GAPTheme;

class Blocking {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_action('admin_menu', [$this, 'gap__add_admin_menu']);
    add_action('admin_init', [$this, 'gap__settings_init']);
  }


  function gap__add_admin_menu() {

    add_menu_page('GAP Blocking', 'GAP Blocking', 'manage_options', 'gap_blocking', [$this, 'gap__options_page']);

  }


  function gap__settings_init() {

    register_setting('pluginPage', 'gap__settings');

    add_settings_section(
      'gap__pluginPage_section',
      __('Your section description', 'gap-theme'),
      [$this, 'gap__settings_section_callback'],
      'pluginPage'
    );



    add_settings_field(
      'gap__textarea_field_1',
      __('Settings field description', 'gap-theme'),
      [$this, 'gap__textarea_field_1_render'],
      'pluginPage',
      'gap__pluginPage_section'
    );


  }

  function gap__textarea_field_1_render() {

    $options = get_option('gap__settings');
    print_r($options);
    ?>
    <div class="repeater">
      <div data-repeater-list="gap__settings[blockings]">
        <?php
        if (!empty($options['blockings'])):
          foreach ($options['blockings'] as $blocking): ?>
            <div data-repeater-item>
              
              <select name="gap_date">
                <?php
                for ($i = 0; $i < 30; $i++) {
                  $time = strtotime('+' . $i . ' days');
                  $value = date("Y/m/d", $time);
                  $label = date("d/m/Y", $time);
                  printf('<option value="%s" %s>%s</option>', $value, selected($blocking['gap_date'], $value, false), $label);
                } ?>
              </select>

              <select name="gap_time">
                <?php
                $array_of_time = Datlichcuahang\Ajax::instance()->array_of_time();
                foreach ($array_of_time as $i => $time) {
                  printf('<option value="%s:00" %s>%s</option>', $time, selected($blocking['gap_time'],$time.':00', false), $time);
                } ?>
              </select>

              <input data-repeater-delete type="button" class="button" value="Delete" />
            </div>
          <?php endforeach; endif; ?>


        <div data-repeater-item>
          <select name="gap_date">
            <?php
            for ($i = 0; $i < 30; $i++) {
              $time = strtotime('+' . $i . ' days');
              $value = date("Y/m/d", $time);
              $label = date("d/m/Y", $time);
              printf('<option value="%s">%s</option>', $value, $label);
            } ?>
          </select>

          <select name="gap_time">
            <?php

            $array_of_time = Datlichcuahang\Ajax::instance()->array_of_time();
            foreach ($array_of_time as $i => $time) {
              printf('<option value="%s:00">%s</option>', $time, $time);
            } ?>
          </select>

          <input data-repeater-delete type="button" class="button"  value="Delete" />
        </div>
      </div>
      <input data-repeater-create type="button" class="button button-primary"  value="Add" />

    </div>
    <?php

  }


  function gap__settings_section_callback() {

    echo __('This section description', 'gap-theme');

  }


  function gap__options_page() {

    ?>
    <form action='options.php' method='post'>

      <h2>GAP Blocking Times</h2>

      <?php
      settings_fields('pluginPage');
      do_settings_sections('pluginPage');
      submit_button();
      ?>

    </form>

    <style>
      div[data-repeater-item]{
        margin-bottom: 10px;
      }
    </style>

    <script>
      jQuery(document).ready(function ($) {
        'use strict';

        $('.repeater').repeater({
          //initEmpty: true,
          show: function () {
            $(this).slideDown(100);
          },
          hide: function (deleteElement) {
            if (confirm('Are you sure you want to delete this element?')) {
              $(this).slideUp(deleteElement);
            }
          },
          ready: function (setIndexes) {

          }
        });


      });
    </script>
    <?php

  }

}