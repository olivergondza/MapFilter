<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::One
 */
class MapFilter_Test_Unit_TreePattern_One extends
    PHPUnit_Framework_TestCase
{
  
  public static function provideSimpleOneNode () {
  
    return Array (
        Array (
            Array ( 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 )
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0 )
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0 )
        )
    );
  }
  
  /**
   * Test OneNode with simple values
   *
   * @dataProvider      provideSimpleOneNode
   */
  public static function testSimpleOneNode ( $query, $result ) {
    
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <one>
            <attr>Attr0</attr>
            <attr>Attr1</attr>
          </one>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern );

    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
  }
}
