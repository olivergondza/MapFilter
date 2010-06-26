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
  
  /**
  * Test OptNode with simple values
  */
  public static function testSimpleSomeNode () {

    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    $one = Array ( "Attr0" => 0 );
    $all = Array ( "Attr0" => 0, "Attr1" => 1 );
    $nothing = Array ();
    
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_TreePattern_Tree_Node_Some ();

    $pattern = new MapFilter_TreePattern (
        $opt -> setContent (
            Array (
                $attr0 -> setAttribute  ( "Attr0" ),
                $attr1 -> setAttribute  ( "Attr1" )
            )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $all,
      $filter->getResults ()
    );

    /** Test accurate */
    $filter->setQuery ( $one );

    self::assertEquals (
      $one,
      $filter->getResults ()
    );

    /** Test All */
    $filter->setQuery ( $all );

    self::assertEquals (
      $all,
      $filter->getResults ()
    );
    
    /** Test nothing */
    $filter->setQuery ( $nothing );

    self::assertEquals (
      $nothing,
      $filter->getResults ()
    );
  }
}
