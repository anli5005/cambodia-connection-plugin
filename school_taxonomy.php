<?php

add_action( 'init', function () {
  register_taxonomy(
    'school',
    'post',
    array(
      'label' => __( 'Schools' )
    )
  );
} );

?>
