<?php
/**
* User Tests using coffee_Maker.xml
*/  

/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter.php' );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::CoffeeMaker
*/
class TestUserCoffeeMaker extends PHPUnit_Framework_TestCase {

  public static function provideParseCoffeeMaker () {
  
    return Array (
        Array (
            Array ( 'beverage' => 'coffee' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'tea', 'cup' => 2 ),
            Array ( 'beverage' => 'tea', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'cacao', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'cacao', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'none', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        )
    );
  }
  
  /**
   * @dataProvider      provideParseCoffeeMaker
   */
  public static function testParseCoffeeMaker ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile ( Test_Source::COFFEE_MAKER ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}