<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::All
 */
class MapFilter_Test_Unit_TreePattern_All extends PHPUnit_Framework_TestCase {  
  
  public static function provideSimpleAllNode () {
  
    return Array (
        Array (
            Array ( 'Attr0' => 0 ),
            Array ()
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 )
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 )
        )
    
    );
  }
  
  /**
   * Test AllNode with simple values
   *
   * @dataProvider      provideSimpleAllNode
   */
  public static function testSimpleAllNode ( $query, $result ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <all>
            <attr>Attr0</attr>
            <attr>Attr1</attr>
          </all>
        </pattern>
    '
    );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
  }
}
