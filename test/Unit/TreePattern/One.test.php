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
  
  /**
  * Test OneNode with simple values
  */
  public static function testSimpleOneNode () {

    $accurate = Array ( "Attr0" => 0 );
    $all = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $one = new MapFilter_TreePattern_Tree_Node_One ();

    $pattern = new MapFilter_TreePattern (
        $one -> setContent (
            Array (
                $attr0 -> setAttribute  ( "Attr0" ),
                $attr1 -> setAttribute  ( "Attr1" )
            )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test choose */
    $filter->setQuery ( $all );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );
  }
}
