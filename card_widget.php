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
    echo( $args[ 'before_widget' ] );
    global $cards, $card_sets;
    $this->get_card_sets();
    $card_set = new $card_sets[ array_key_exists( $instance[ 'cardset' ], $card_sets ) ? $instance[ 'cardset' ] : 'default' ];
    if ( ! empty( $instance[ 'title' ] ) ) {
      echo( '<h4>' . $instance[ 'title' ] . '</h4>' );
    } ?>
    <div class="widget widget_cambodia_cards cambodia-<?php echo( $instance[ 'direction' ] === 'vertical' ? 'vertical' : 'horizontal' ) ?>">
      <?php

      foreach ( $card_set->cards as $card_id ) {
        $card_class_name;
        $card_args = array();
        if ( is_array( $card_id ) ) {
          $card_class_name = $card_id[0];
          $card_args = $card_id[1];
        } else {
          $card_class_name = $card_id;
        }
        $card_class = array_key_exists( $card_class_name, $cards ) ? $cards[ $card_class_name ] : $cards[ 'default' ];
        $card = new $card_class;
        $card->init( $card_args );
        if ( $card->stopRendering ) {
          continue;
        }
        if ( $card->link ) {
          echo( '<a class="cambodia-link" href="' . $card->link . '">' );
        }
        echo( '<div class="cambodia-card ' );
        if ( $card->hoverable ) {
          echo( 'cambodia-hoverable ' );
        }
        echo( $card->class . '" ' );
        if ( $instance[ 'direction' ] !== 'vertical' ) {
          echo( 'style="height:' . strval( empty( $instance[ 'height' ] ) ? 150 : $instance[ 'height' ] ) . 'px;"');
        }
        echo( '>' );

        $card->card( $card_args );
        echo( '</div>' );
        if ( $card->link ) {
          echo( '</a>' );
        }
      }
      ?>
    </div><?php
    echo( $args[ 'after_widget' ] );
  }

  function field_id( $id ) {
    echo( esc_attr( $this->get_field_id( $id ) ) );
  }

  function field_name( $name ) {
    echo( esc_attr( $this->get_field_name( $name ) ) );
  }

  function form( $instance ) {
    global $card_sets;
    $this->get_card_sets();
    $title = empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ];
    $card_set = empty( $instance[ 'cardset' ] ) ? 'default' : $instance[ 'cardset' ];
    $direction = empty( $instance[ 'direction' ] ) ? 'horizontal' : $instance[ 'direction' ];
    $height = empty( $instance[ 'height' ] ) ? 150 : $instance[ 'height' ];
    ?>

    <p>
      <label for="<?php $this->field_id( 'title' ) ?>"><?php esc_html_e( 'Title' ) ?></label>
      <input class="widefat" id="<?php $this->field_id( 'title' ) ?>" name="<?php $this->field_name( 'title' ) ?>" type="text" value="<?php echo( esc_attr( $title ) ); ?>" />
    </p>
    <p>
      <label for="<?php $this->field_id( 'cardset' ) ?>"><?php esc_html_e( 'Card Set' ) ?></label>
      <select class="widefat" id="<?php $this->field_id( 'cardset' ) ?>" name="<?php $this->field_name( 'cardset' ) ?>">
        <?php
          foreach ($card_sets as $id => $set) { ?>
            <option value="<?php echo( esc_attr( $id ) ); ?>" <?php echo( $id === $instance[ 'cardset' ] ? 'selected' : '' ); ?>><?php echo( esc_html( $set::$name ) ); ?></option>
          <?php }
        ?>
      </select>
    </p>
    <p>
      <label for="<?php $this->field_id( 'direction' ) ?>"><?php esc_html_e( 'Direction' ) ?></label>
      <select class="widefat" id="<?php $this->field_id( 'direction' ) ?>" name="<?php $this->field_name( 'direction' ) ?>">
        <option value="horizontal" <?php echo( $direction !== 'vertical' ? 'selected' : '' ); ?>>Horizontal</option>
        <option value="vertical" <?php echo( $direction === 'vertical' ? 'selected' : '' ); ?>>Vertical</option>
      </select>
    </p>
    <p>
      <label for="<?php $this->field_id( 'height' ) ?>"><?php esc_html_e( 'Card Height (px)' ) ?></label>
      <input class="widefat" id="<?php $this->field_id( 'height' ) ?>" name="<?php $this->field_name( 'height' ) ?>" type="number" value="<?php echo( esc_attr( $height ) ); ?>" />
      <span class="description">This is ignored if the direction is set to vertical.</span>
    </p>

    <?php
  }

  function update( $new_instance, $old_instance ) {
    global $card_sets;
    $this->get_card_sets();

		$instance = array();
    $instance[ 'title' ] = empty( $new_instance[ 'title' ] ) ? '' : esc_html( $new_instance[ 'title' ] );

    if ( ( ! empty( $new_instance[ 'cardset' ] ) ) && array_key_exists( $new_instance[ 'cardset' ], $card_sets ) ) {
      $instance[ 'cardset' ] = $new_instance[ 'cardset' ];
    } else {
      $instance[ 'cardset' ] = 'default';
    }

    $instance[ 'direction' ] = $new_instance[ 'direction' ] === 'vertical' ? 'vertical' : 'horizontal';

    $height = intval( $new_instance[ 'height' ] );
    $instance[ 'height' ] = $height < 2 ? 150 : $height;

    return $instance;
	}
}

class CC_Card {
  public $hoverable = false;
  public $link = false;
  public $class = '';
  public $stopRendering = false;

  function __construct() {

  }

  function init( $args ) {

  }

  function card( $args ) {

  }

  static $id;
}

class CC_Card_Set {
  public $cards = array();
  static $id;
  static $name = "Card Set";
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
  public $hoverable = true;
  public $link = 'https://example.com';
  public $class = 'cambodia-default-card';
  static $id = 'default';

  function card( $args ) {
    ?>
    <h3>Hello, world!</h3>
    <p><?php echo( array_key_exists( 'description', $args ) ? esc_html( $args[ 'description' ] ) : __( "I'm a card and I'm lonely." ) ); ?></p>
    <?php
  }
}

class CC_Default_Card_Set extends CC_Card_Set {
  public $cards = array(
    array( 'default', array(
      'description' => 'Head to the Widgets page to customize this widget!'
    ) ),
    'default'
  );
  static $id = 'default';
  static $name = 'Default';
}

class CC_Testing_Card_Set extends CC_Card_Set {
  public $cards = array(
    'default'
  );
  static $id = 'testing';
  static $name = 'Default 2';
}

add_action( 'register_cc_cards', function () {
  register_cc_card( CC_Default_Card::class );
} );

add_action( 'register_cc_card_sets', function () {
  register_cc_card_set( CC_Default_Card_Set::class );
  register_cc_card_set( CC_Testing_Card_Set::class );
} );

?>
