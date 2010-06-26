<?php
/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/TreePattern.php' );

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Attr
 */
class TestTreePatternAttr extends PHPUnit_Framework_TestCase {  
  
  /** Attribute tag value should overwrite attribute value */
  public static function testAttrOverwrite () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="wrongAttribute">anAttribute</attr>';
    
    self::assertEquals (
        MapFilter_TreePattern::load ( $lazyPattern ),
        MapFilter_TreePattern::load ( $pattern )
    );
  }
  
  /** Compare attributes sat by different ways */
  public static function testCompareAttrs () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="anAttribute"></attr>';
    
    self::assertEquals (
        MapFilter_TreePattern::load ( $lazyPattern ),
        MapFilter_TreePattern::load ( $pattern )
    );
  }
  
  /** Test PatternAttr */
  public static function testAttr () {
    
    $query = Array ( 'attr0' => "value" );

    $attr = new MapFilter_TreePattern_Tree_Leaf_Attr ();

    $pattern = new MapFilter_TreePattern (
        $attr -> setAttribute ( "attr0" )
    );

    $filter = new MapFilter (
        $pattern,
        $query
    );

    self::assertEquals (
        $query,
        $filter->getResults ()
    );
  }
    
  public static function provideAssertEmptyAttr () {
  
    return Array (
        Array ( '<attr attr="" />' ),
        Array ( '<attr attr=""></attr>' ),
        Array ( '<attr></attr>' ),
        Array ( '<attr />' ),
    );
  }
  
  /**
   * Rise an exception in case of no attr value
   * @dataProvider	provideAssertEmptyAttr
   */
  public static function testAssertEmptyAttr ( $pattern ) {
  
    try {
    
      $pattern = MapFilter_TreePattern::load ( $pattern );
      self::fail ( "No exception risen." );

    } catch ( MapFilter_TreePattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_TreePattern_Exception::MISSING_ATTRIBUTE_VALUE,
          $exception->getCode ()
      );

    } catch ( Exception $ex ) {
    
      self::fail ( "Wrong exception: " . $ex->getMessage () );
    }
  }
  
  /**
   * Test default value for both nodes that support iterator attribute
   */
  public static function testDefaultArrayValue () {
  
    $patternNoArrayValue =
        '<pattern><attr iterator="no">an_attr</attr></pattern>'
    ;
    $patternDefault = '<pattern><attr>an_attr</attr></pattern>';
    
    self::assertEquals (
        MapFilter_TreePattern::load ( $patternNoArrayValue ),
        MapFilter_TreePattern::load ( $patternDefault )
    );
    
    $patternNoArrayValue =
        '<pattern><key_attr iterator="no" attr="an_attr"></key_attr></pattern>'
    ;
    $patternDefault = '<pattern><key_attr attr="an_attr"></key_attr></pattern>';
    
    self::assertEquals (
        MapFilter_TreePattern::load ( $patternNoArrayValue ),
        MapFilter_TreePattern::load ( $patternDefault )
    );
  }
  
  public static function provideAttrArrayValue () {
  
    return Array (
        Array (
            Array ( 'an_attr' => Array ( 'val1', 'val2' ) ),
            Array ( 'an_attr' => Array ( 'val1' ) ),
            Array ( 'wrong_attr' => Array ( 'val2' ) ),
            Array ( 'an_attr' )
        ),
        Array (
            Array ( 'an_attr' => Array ( 'val1', 'val1' ) ),
            Array ( 'an_attr' => Array ( 'val1', 'val1' ) ),
            Array (),
            Array ( 'an_attr' )
        ),
        Array (
            Array ( 'an_attr' => Array ( 'val2', 'val2' ) ),
            Array (),
            Array ( 'wrong_attr' => Array ( 'val2', 'val2' ) ),
            Array ()
        ),
        Array (
            Array ( 'an_attr' => Array () ),
            Array (),
            Array ( 'wrong_attr' => 'wrong_attr' ),
            Array (),
        ),
        Array (
            Array (),
            Array (),
            Array ( 'wrong_attr' => 'wrong_attr' ),
            Array ()
        ),
    );
  }
  
  /**
   * Test array filtering
   *
   * @dataProvider      provideAttrArrayValue
   * @group             attrArrayValue
   */
  public static function testAttrArrayValue (
      $query, $results, $asserts, $flags
  ) {
  
    $pattern = '<pattern>
        <attr
            iterator="yes"
            valuePattern="val1"
            assert="wrong_attr"
            flag="an_attr"
        >an_attr</attr>
    </pattern>';
    
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern ),
        $query
    );
    
    self::assertEquals (
        $results,
        $filter->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $asserts,
        $filter->fetchResult ()->getAsserts ()
    );
    
    self::assertEquals (
        $flags,
        $filter->fetchResult ()->getFlags ()
    );
  }
  
  public static function provideAttrArrayValueExceptions () {
  
    return Array (
        Array (
            Array ( 'arrayAttr' => 'scalarValue' ),
            new MapFilter_TreePattern_Tree_Leaf_Exception (
                MapFilter_TreePattern_Tree_Leaf_Exception::SCALAR_ATTR_VALUE,
                Array ( 'arrayAttr', 'string' )
            )
        ),
        Array (
            Array ( 'arrayAttr' => 42 ),
            new MapFilter_TreePattern_Tree_Leaf_Exception (
                MapFilter_TreePattern_Tree_Leaf_Exception::SCALAR_ATTR_VALUE,
                Array ( 'arrayAttr', 'integer' )
            )
        ),
        Array (
            Array ( 'scalarAttr' => Array ( 'arrayMember' ) ),
            new MapFilter_TreePattern_Tree_Leaf_Exception (
                MapFilter_TreePattern_Tree_Leaf_Exception::ARRAY_ATTR_VALUE,
                Array ( 'scalarAttr' )
            )
        ),
        Array (
            Array ( 'scalarAttr' => new ArrayIterator (
                Array ( 'arrayMember' )
            ) ),
            new MapFilter_TreePattern_Tree_Leaf_Exception (
                MapFilter_TreePattern_Tree_Leaf_Exception::ARRAY_ATTR_VALUE,
                Array ( 'scalarAttr' )
            )
        ),
    );
  }
  
  /**
   * @dataProvider      provideAttrArrayValueExceptions
   */
  public static function testAttrArrayValueExceptions ( $query, $expectedException ) {
  
    $pattern = '
    <pattern>
      <opt>
        <attr iterator="yes">arrayAttr</attr>
        <attr iterator="no">scalarAttr</attr>
      </opt>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern )
    );
    
    $filter->setQuery ( $query );

    try {
    
      $filter->fetchResult ()->getResults ();
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Tree_Leaf_Exception $exception ) {
    
      self::assertEquals (
          $expectedException->getMessage (),
          $exception->getMessage ()
      );
    }
  }
}
