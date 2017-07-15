<?php

class Cambodia_Card_Widget extends WP_Widget {
  function __construct() {
    parent::__construct( 'cambodia_card_widget', 'Card Widget', array(
      'classname' => 'Cambodia_Card_Widget',
      'description' => 'A scrolling list of cards'
    ) );
  }

  function widget( $args, $instance ) { ?>
    <div class="widget widget_cambodia_card_widget">
      <h3 class="widget_title">Hello world!</h3>
    </div>
  <?php }

  function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
}

add_action( "widgets_init", function () {
	register_widget("Cambodia_Card_Widget");
} );

?>
