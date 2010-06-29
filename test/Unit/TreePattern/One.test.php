<?php
/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/TreePattern.php' );

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::One
 */
class TestTreePatternOne extends PHPUnit_Framework_TestCase {  
  
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
    
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $one = new MapFilter_TreePattern_Tree_Node_One ();

    $pattern = new MapFilter_TreePattern (
        $one -> setContent (
            Array (
                $attr0 -> setAttribute  ( 'Attr0' ),
                $attr1 -> setAttribute  ( 'Attr1' )
            )
        )
    );

    $filter = new MapFilter ( $pattern );

    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
  }
}
