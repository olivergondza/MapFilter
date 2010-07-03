<?php
/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_DIR . '/TreePattern.php' );

require_once ( PHP_MAPFILTER_DIR . '/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 */
class MapFilter_Test_Unit_TreePattern extends PHPUnit_Framework_TestCase {  
  
  /**
   * Test whether MapFilter_TreePattern implements
   * MapFilter_Pattern_Interface
   */
  public static function testInterface () {
  
    self::assertTrue (
        is_a (
            MapFilter_TreePattern::load ( '<attr>attr</attr>' ),
            'MapFilter_Pattern_Interface'
        )
    );
  }
  
  /** Parse a tag that hes not been wrapped in <pattern> tags */
  public static function testUnwrapped () {

    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<pattern><attr>anAttribute</attr></pattern>';
    $deepPattern = '<patterns><pattern><attr>anAttribute</attr></pattern></patterns>';
  
    self::assertEquals (
        MapFilter_TreePattern::load ( $pattern ),
        MapFilter_TreePattern::load ( $lazyPattern )
    );
    
    self::assertEquals (
        MapFilter_TreePattern::load ( $pattern ),
        MapFilter_TreePattern::load ( $deepPattern )
    );
  }
  
  /** Invalid file */
  public static function testWrongFile () {
  
    try {

      $filter = MapFilter_TreePattern::fromFile ( "no_such_file.xml" );
      self::fail ( "No exception risen." );
      
    } catch ( MapFilter_TreePattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_TreePattern_Exception::LIBXML_WARNING,
          $exception->getCode ()
      );
    } catch ( Exception $ex ) {
    
      self::fail ( "Wrong exception: " . (String) $ex );
    }
  }
  
  public static function provideWrongAttribute () {
  
    return Array (
        /** An attr attribute */
        Array (
            '<pattern><all attr="attrName" /></pattern>',
            "Node 'all' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><one attr="attrName" /></pattern>',
            "Node 'one' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><opt attr="attrName" /></pattern>',
            "Node 'opt' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><some attr="attrName" /></pattern>',
            "Node 'some' has no attribute like 'attr'."
        ),
        
        /** An valuePattern attribute */
        Array (
            '<pattern><all valuePattern="pattern" /></pattern>',
            "Node 'all' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><one valuePattern="pattern" /></pattern>',
            "Node 'one' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><opt valuePattern="pattern" /></pattern>',
            "Node 'opt' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><some valuePattern="pattern" /></pattern>',
            "Node 'some' has no attribute like 'valuePattern'."
        ),
        
        /** A default attribute */
        Array (
            '<pattern><all default="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'default'."
        ),
        Array (
            '<pattern><one default="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'default'."
        ),
        Array (
            '<pattern><opt default="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'default'."
        ),
        Array (
            '<pattern><some default="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'default'."
        ),
        
        /** A iterator attribute */
        Array (
            '<pattern><all iterator="auto" /></pattern>',
            "Node 'all' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><one iterator="auto" /></pattern>',
            "Node 'one' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><opt iterator="auto" /></pattern>',
            "Node 'opt' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><some iterator="auto" /></pattern>',
            "Node 'some' has no attribute like 'iterator'."
        ),

        Array (
            '<pattern><attr><attr>an_attr</attr></attr></pattern>',
            "Node 'attr' has no content."
        ),
        
    );
  }
  
  /**
   * Get wrong attribute
   * @dataProvider provideWrongAttribute
   */
  public static function testWrongAttribute ( $pattern, $exception ) {

    try {

      MapFilter_TreePattern::load ( $pattern );
      self::fail ( "No exception risen." );

    } catch ( MapFilter_TreePattern_Exception $ex ) {

      self::assertEquals ( $exception, (String) $ex );
    } catch ( Exception $ex ) {
    
      self::fail ( "Wrong exception: " . (String) $exception );
    }
  }
  
  public static function provideCompare () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'a' => 'val' ),
            Array ( 'a' => 'val' )
        ),
        Array (
            Array ( 'b' => 'val' ),
            Array ()
        ),
        Array (
            Array ( 'a' => 'val', 'b' => 'var' ),
            Array ( 'a' => 'val' )
        )
    );
  }
  
  /**
   * @dataProvider      provideCompare
   */
  public static function testSimpleCompare ( $query, $result ) {

    $simple = MapFilter_TreePattern::load ( '
        <pattern>
            <all>
              <attr>a</attr>
            </all>
        </pattern>
    ' );
    $simple = new MapFilter ( $simple, $query );
    
    $assembled = MapFilter_TreePattern::load ( '
        <patterns>
          <pattern>
            <all attachPattern="second" />
          </pattern>
          <pattern name="second">
            <attr>a</attr>
          </pattern>
        </patterns>
    ' );

    $assembled = new MapFilter ( $assembled, $query );

    self::assertEquals (
        $result,
        $simple->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
  }
}
