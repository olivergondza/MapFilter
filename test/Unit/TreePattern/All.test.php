<?php
/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/TreePattern.php' );

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::All
 */
class TestTreePatternAll extends PHPUnit_Framework_TestCase {  
  
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

    $all = new MapFilter_TreePattern_Tree_Node_All ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    
    $pattern = new MapFilter_TreePattern (
        $all -> setContent (
            Array (
                $attr0 -> setAttribute ( 'Attr0' ),
                $attr1 -> setAttribute ( 'Attr1' )
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
