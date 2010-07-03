<?php
/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_DIR . '/TreePattern.php' );

require_once ( PHP_MAPFILTER_DIR . '/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Opt
 */
class MapFilter_Test_Unit_TreePattern_Opt extends PHPUnit_Framework_TestCase {  
  
  public static function provideSimpleOptNode () {
  
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
        ),
    );
  }
  
  /**
   * Test OptNode with simple values
   *
   * @dataProvider      provideSimpleOptNode
   */
  public static function testSimpleOptNode ( $query, $result ) {
    
    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <opt>
            <attr>Attr0</attr>
            <attr>Attr1</attr>
          </opt>
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
