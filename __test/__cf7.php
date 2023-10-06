<?php 

add_filter( 'wpcf7_form_elements', 'imp_wpcf7_form_elements' );
function imp_wpcf7_form_elements( $content ) {
    $str_pos = strpos( $content, 'name="unique-name"' );
    if ( $str_pos !== false ) {
        $content = substr_replace( $content, ' data-attr="custom" data-msg="Foo Bar 1" ', $str_pos, 0 );
    }
    return $content;
}


add_filter( 'wpcf7_form_tag', function ( $tag ) {
  $datas = [];
  foreach ( (array)$tag['options'] as $option ) {
      if ( strpos( $option, 'data-' ) === 0 ) {
          $option = explode( ':', $option, 2 );
          $datas[$option[0]] = apply_filters('wpcf7_option_value', $option[1], $option[0]);
      }
  }
  if ( ! empty( $datas ) ) {
      $id = uniqid('tmp-wpcf');
      $tag['options'][] = "class:$id";
      add_filter( 'wpcf7_form_elements', function ($content) use ($id, $datas) {
          return str_replace($id, $name, str_replace($id.'"', '"'. wpcf7_format_atts($datas), $content));
      });
  }
  return $tag;
} );


add_filter( 'wpcf7_form_elements', 'imp_wpcf7_form_elements' );

function imp_wpcf7_form_elements( $content ) {
    $str_pos = strpos( $content, 'name="your-email-homepage"' );
    $content = substr_replace( $content, ' aria-describedby="emailHelp" ', $str_pos, 0 );

    $str_pos2 = strpos( $content, 'name="your-fname-homepage"' );
    $content = substr_replace( $content, ' aria-describedby="fnameHelp" ', $str_pos2, 0 );

    $str_pos3 = strpos( $content, 'name="your-lname-homepage"' );
    $content = substr_replace( $content, ' aria-describedby="lnameHelp" ', $str_pos3, 0 );
    return $content;        
}