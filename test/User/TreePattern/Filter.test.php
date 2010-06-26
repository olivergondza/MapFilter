<?php
/**
* User Tests using filter.xml
*/  

/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter.php' );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Filter
*/
class TestUserFilter extends PHPUnit_Framework_TestCase {

  public static function provideParseFilterUtility () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( '-a' => NULL ),
            Array ( '-a' => NULL )
        ),
        Array (
            Array ( '-c' => NULL ),
            Array ( '-c' => NULL )
        ),
        Array (
            Array ( '-c' => NULL, '-l' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL )
        ),
        Array (
            Array ( '-c' => NULL, '-l' => NULL, '-h' => NULL ),
            Array ( '-c' => NULL, '-l' => NULL, '-h' => NULL )
        )
    );
  }
  
  /**
   * Test parse external source and validate
   * @dataProvider      provideParseFilterUtility
   */
  public static function testParseFilterUtility ( $query, $result ) {

    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile ( Test_Source::FILTER ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}