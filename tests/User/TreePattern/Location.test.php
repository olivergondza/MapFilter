<?php
/**
* User Tests using location.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Location
*/
class MapFilter_Test_User_TreePattern_Location extends
    PHPUnit_Framework_TestCase
{

  public static function provideParseLocation () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'nick' => "myLocation" ),
            Array ( 'action' => "delete", 'nick' => "myLocation" )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 ),
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2, 'a' => 0 ),
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1, 'z' => 2 )
        ),
        Array (
            Array ( 'action' => "delete", 'x' => 1, 'y' => 1 ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'nick' => "myLocation", 'duration' => "permanent" ),
            Array ( 'action' => "delete", 'nick' => "myLocation" )
        )
    );
  }
  
  /**
   * Test parse external source and validate
   * @dataProvider      provideParseLocation
   */
  public static function testParseLocation ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::LOCATION
        ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}