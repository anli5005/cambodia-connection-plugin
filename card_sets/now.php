<?php
class CC_Now_Card_Set extends CC_Card_Set {
  static $id = 'now';
  static $name = 'Now';

  public $cards = array(
    array( 'recent', array(
      'index' => 0,
      'color' => 'blue'
    ) ),
    array( 'recent', array(
      'index' => 1,
      'color' => 'blue'
    ) ),
    array( 'recent', array(
      'index' => 2,
      'color' => 'blue'
    ) ),
    array( 'recent', array(
      'index' => 3,
      'color' => 'blue'
    ) ),
    array( 'recent', array(
      'index' => 4,
      'color' => 'white'
    ) ),
    'default'
  );
}

add_action( 'register_cc_card_sets', function () {
  register_cc_card_set( CC_Now_Card_Set::class );
} );
?>
