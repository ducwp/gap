<?php 

add_action('blocksy:hero:after', 'bc', 99);
function bc() {
  $single_hero_element = new stdClass;
  $breadcrumbs_builder = new Blocksy_Breadcrumbs_Builder();

  echo '<div style="
  width: var(--normal-container-max-width);
  margin: 0 auto;
  padding-top: 30px;
">';
  $bc = $breadcrumbs_builder->render([
    'class' => blocksy_visibility_classes(
      blocksy_akg(
        'breadcrumbs_visibility',
        $single_hero_element,
        [
          'desktop' => true,
          'tablet' => true,
          'mobile' => true,
        ]
      )
    )
  ]);
  echo str_replace('Trang chủ', '<i class="home-icon"></i>', $bc);
  echo '</div>';
}