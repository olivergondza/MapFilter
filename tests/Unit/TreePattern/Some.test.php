<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Some
 */
class MapFilter_Test_Unit_TreePattern_Some extends
    PHPUnit_Framework_TestCase
{
  
  public static function provideSimpleSomeNode () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 )
        ),
        Array (
            Array ( 'Attr0' => 0 ),
            Array ( 'Attr0' => 0 )
        ),
        Array (
            Array ( 'Attr0' => 0, 'Attr1' => 1 ),
            Array ( 'Attr0' => 0, 'Attr1' => 1 )
        )
    );
  }
  
  /**
   * Test OptNode with simple values
   *
   * @dataProvider      provideSimpleSomeNode
   */
  public static function testSimpleSomeNode ( $query, $result ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <some>
            <attr>Attr0</attr>
            <attr>Attr1</attr>
          </some>
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
