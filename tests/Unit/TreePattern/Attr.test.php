<?php
/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_DIR . '/TreePattern.php' );

require_once ( PHP_MAPFILTER_DIR . '/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Attr
 */
class MapFilter_Test_Unit_TreePattern_Attr extends
    PHPUnit_Framework_TestCase
{  
  
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
  
  /**
   * Test PatternAttr
   * @group     Unit::TreePattern::Attr::testAttr
   */
  public static function testAttr () {
    
    $query = Array ( 'attr0' => 'value' );

    $pattern = MapFilter_TreePattern::load (
        '<pattern><attr>attr0</attr></pattern>'
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
   *
   * @dataProvider	provideAssertEmptyAttr
   */
  public static function testAssertEmptyAttr ( $pattern ) {
  
    try {
    
      $pattern = MapFilter_TreePattern::load ( $pattern );
      self::fail ( 'No exception risen.' );

    } catch ( MapFilter_TreePattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_TreePattern_Exception::MISSING_ATTRIBUTE_VALUE,
          $exception->getCode ()
      );

    } catch ( Exception $ex ) {
    
      self::fail ( 'Wrong exception: ' . $ex->getMessage () );
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
  
  /**
   * Use unsupported value as an iterator depth indicator
   */
  public static function testInvalidIteratorValue () {
  
    try {
    
      MapFilter_TreePattern::load ( '
          <pattern>
            <attr iterator="auto">attr</attr>
          </pattern>
      ' );
      
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Tree_Leaf_Exception $ex ) {
    
      self::assertEquals (
          MapFilter_TreePattern_Tree_Leaf_Exception::INVALID_DEPTH_INDICATOR,
          $ex->getCode ()
      );
    }
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
   * @group             Unit::TreePattern::Attr::testAttrArrayValue
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
  
  public static function provideValidationAndExistenceDefaults () {
  
    return Array (
        Array (
            // Keep valid
            Array ( 'attr' => 'Valid value' ),
            Array ( 'attr' => 'Valid value' )
        ),
        Array (
            // Set existence default
            Array (),
            Array ( 'attr' => 'New value' )
        ),
        Array (
            // Substitude validation default
            Array ( 'attr' => '6' ),
            Array ( 'attr' => 'Better value' )
        )
    );
  }
  
  /**
   * @dataProvider      provideValidationAndExistenceDefaults
   */
  public static function testValidationAndExistenceDefaults (
      $query, $result
  ) {
  
    $pattern = '
    <pattern>
      <attr
          existenceDefault="New value"
          validationDefault="Better value"
          valuePattern="[^0-9]*"
      >attr</attr>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public static function provideValidationAndExistenceDefaultsOnArray () {
  
    return Array (
        Array (
            Array (),
            Array ( 'attr' => Array ( 'New value' ) )
        ),
        Array (
            Array ( 'attr' => Array () ),
            Array ( 'attr' => Array ( 'Better value' ) ),
        ),
        Array (
            Array ( 'attr' => Array ( '0' ) ),
            Array ( 'attr' => Array ( 'Better value' ) ),
        )
    );
  }
  
  /**
   * @dataProvider      provideValidationAndExistenceDefaultsOnArray
   */
  public static function testValidationAndExistenceDefaultsOnArray (
      $query, $result
  ) {
  
    $pattern = '
    <pattern>
      <attr
          existenceDefault="New value"
          validationDefault="Better value"
          valuePattern="[^0-9]*"
          iterator="1"
      >attr</attr>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  
  public static function provideLargeDepthIterator () {
  
    return Array (
        Array (
            // default value assigned
            Array (),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // valid value kept
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ) ) )
        ),
        Array (
            // invalid value replaced by default
            Array ( 'weight' => Array ( Array ( Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // multiple values
            Array ( 'weight' => Array ( Array ( Array ( 1, 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1, 2 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 2 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 2 ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 2 ) ) ) )
        ),
        Array (
            // multiple values with invalid pieces
            Array ( 'weight' => Array ( Array ( Array ( 1, 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1, 0 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ), Array ( 0 ) ) ) )
        ),
        Array (
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 'heawy' ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 1 ) ), Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // too flat
            Array ( 'weight' => 5 ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // still too flat
            Array ( 'weight' => Array ( 5 ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // still too flat
            Array ( 'weight' => Array ( Array ( 5 ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // too deep
            Array ( 'weight' => Array ( Array ( Array ( Array ( 1 ) ) ) ) ),
            Array ( 'weight' => Array ( Array ( Array ( 0 ) ) ) )
        ),
        Array (
            // incavated cube with heawy corners
            Array ( 'weight' => Array (
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) ),
                Array ( Array ( 1, 1, 1 ), Array ( 1, 0, 1 ), Array ( 1, 1, 1 ) ),
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) )
            ) ),
            Array ( 'weight' => Array (
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) ),
                Array ( Array ( 1, 1, 1 ), Array ( 1, 0, 1 ), Array ( 1, 1, 1 ) ),
                Array ( Array ( 2, 1, 2 ), Array ( 1, 1, 1 ), Array ( 2, 1, 2 ) )
            ) )
        )
        
    );
  }
  
  /**
   * @dataProvider      provideLargeDepthIterator
   */
  public static function testLargeDepthIterator (
      Array $query, Array $result
  ) {
  
    $pattern = '
    <pattern>
      <attr
          valuePattern="[0-9][0-9]*"
          default="0"
          iterator="3"
      >weight</attr>
    </pattern>
    ';
    
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
}
