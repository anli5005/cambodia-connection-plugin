<?php

$cc_cards = FALSE;
$cc_card_sets = FALSE;

class Cambodia_Card_Widget extends WP_Widget {
  function __construct() {
    parent::__construct( 'cambodia_card_widget', 'Card Widget', array(
      'classname' => 'Cambodia_Card_Widget',
      'description' => 'A scrolling list of cards'
    ) );
  }

  function get_card_sets() {
    global $cards, $card_sets;

    if ( ! is_array( $cards ) ) {
      $cards = array();
      do_action( 'register_cc_cards' );
    }

    if ( ! is_array( $card_sets ) ) {
      $card_sets = array();
      do_action( 'register_cc_card_sets' );
    }
  }

  function widget( $args, $instance ) {
    global $cards, $card_sets;
    $this->get_card_sets();
    $card_set = new $card_sets[ 'default' ]; ?>
    <div class="widget widget_cambodia_cards cambodia-horizontal">
      <?php

      foreach ( $card_set->cards as $card_id ) {
        $card_class = array_key_exists( $card_id, $cards ) ? $cards[ $card_id ] : $cards[ 'default' ];
        $card = new $card_class;
        echo( '<div class="cambodia-card ' );
        if ( $card->hoverable ) {
          echo( 'cambodia-hoverable ' );
        }
        echo( $card->class . '">' );
        $card->card();
        echo( '</div>' );
      }

      ?>
    </div>
  <?php }

  function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
}

class CC_Card {
  public $hoverable = false;
  public $class = '';

  function __construct() {

  }

  function card() {

  }

  static $id;
}

class CC_Card_Set {
  public $cards = array();
  static $id;
}

function register_cc_card( $card_class ) {
  global $cards;
  if ( is_array( $cards ) ) {
    if ( $card_class::$id ) {
      $cards[ $card_class::$id ] = $card_class;
    } else {
      throw ErrorException( 'Failure to supply an ID string' );
    }
  } else {
    throw ErrorException( 'Cannot use register_cc_card before the register_cc_cards action is fired' );
  }
}

function register_cc_card_set( $card_set ) {
  global $card_sets;
  if ( is_array( $card_sets ) ) {
    if ( $card_set::$id ) {
      $card_sets[ $card_set::$id ] = $card_set;
    } else {
      throw ErrorException( 'Failure to supply an ID string' );
    }
  } else {
    throw ErrorException( 'Cannot use register_cc_card_set before the register_cc_card_sets action is fired' );
  }
}

add_action( 'widgets_init', function () {
	register_widget( 'Cambodia_Card_Widget' );
} );

add_action( 'wp_enqueue_scripts', function () {
  wp_enqueue_style( 'cc_card', plugins_url( '/card_widget.css', __FILE__ ) );
} );

class CC_Default_Card extends CC_Card {
  public $hoverable = false;
  public $class = 'cambodia-default-card';
  static $id = 'default';

  function card() {
    ?>
    <h3>Hello, world!</h3>
    <p>I'm a card and I'm lonely.</p>
    <?php
  }
}

class CC_Default_Card_Set extends CC_Card_Set {
  public $cards = array( 'default', 'default' );
  static $id = 'default';
}

add_action( 'register_cc_cards', function () {
  register_cc_card( CC_Default_Card::class );
} );

add_action( 'register_cc_card_sets', function () {
  register_cc_card_set( CC_Default_Card_Set::class );
} );

?>
