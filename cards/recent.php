<?php

class CC_Recent_Card extends CC_Card {

  static $id = 'recent';

  public $hoverable = true;
  public $class = 'cc-recent-card';
  public $post = NULL;

  function init( $args ) {
    $post_array = get_posts(
      array(
        'posts_per_page' => 1,
        'offset' => $args[ 'index' ]
      )
    );

    if ( count( $post_array ) < 1 ) {
      $this->stopRendering = true;
    } else {
      $this->post = $post_array[0];
      $this->link = esc_url( get_permalink( $this->post ) );
    }
  }

  function card( $args ) { ?>
    <div class="cc-card-content <?php echo( $args[ 'color' ] ); ?>">
      <h3><?php echo( esc_html( $this->post->post_title ) ); ?></h3>
      <p class="author">by <?php echo( esc_html( get_userdata( $this->post->post_author )->display_name ) ); ?></p>
    </div>
  <?php }

}

add_action( 'register_cc_cards', function () {
  register_cc_card( CC_Recent_Card::class );
} );

?>
