<?php
/**
* User Tests using coffee_Maker.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::CoffeeMaker
*/
class MapFilter_Test_User_TreePattern_CoffeeMaker extends PHPUnit_Framework_TestCase {

  /*@}*/
  public static function provideParseCoffeeMaker () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        // default values will be assigned
        Array (
            Array ( 'beverage' => 'coffee' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid value will be replaced by default
        Array (
            Array ( 'beverage' => 'tea', 'cup' => 2 ),
            Array ( 'beverage' => 'tea', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid value will be replaced by default
        Array (
            Array ( 'beverage' => 'cacao', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'cacao', 'cup' => 'yes', 'sugar' => 0 )
        ),
        // invalid values will be replaced by defaults
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'none', 'sugar' => 'a lot' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'yes', 'sugar' => 0 )
        ),
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        ),
        // disallowed attribute will be truncated
        Array (
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5, 'spoon' => 'please' ),
            Array ( 'beverage' => 'coffee', 'cup' => 'no', 'sugar' => 5 )
        )
    );
  }
  /*@}*/
  
  /**
   * @dataProvider      provideParseCoffeeMaker
   */
  public static function testParseCoffeeMaker ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::COFFEE_MAKER
        ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}