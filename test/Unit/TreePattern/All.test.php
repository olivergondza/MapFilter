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
  
  /**
  * Test AllNode with simple values
  */
  public static function testSimpleAllNode () {

    $less = Array ( "Attr0" => 0 );
    $accurate = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $all = new MapFilter_TreePattern_Tree_Node_All ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    
    $pattern = new MapFilter_TreePattern (
        $all -> setContent (
            Array (
                $attr0 -> setAttribute ( "Attr0" ),
                $attr1 -> setAttribute ( "Attr1" )
            )
        )
    );

    $filter = new MapFilter ( $pattern );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test error */
    $filter->setQuery ( $less );

    self::assertEquals (
      Array (),
      $filter->getResults ()
    );
  }
}
