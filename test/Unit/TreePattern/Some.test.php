<?php
/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/TreePattern.php' );

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Some
 */
class TestTreePatternSome extends PHPUnit_Framework_TestCase {  
  
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

    $more = Array ( 'Attr0' => 0, 'Attr1' => 1, 'Attr2' => 2 );
    $one = Array ( 'Attr0' => 0 );
    $all = Array ( 'Attr0' => 0, 'Attr1' => 1 );
    $nothing = Array ();
    
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_TreePattern_Tree_Node_Some ();

    $pattern = new MapFilter_TreePattern (
        $opt -> setContent (
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
