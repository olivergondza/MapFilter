<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

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
  
  /**
   * Invalid file
   * @expectedException MapFilter_TreePattern_Xml_LibXmlException
   */
  public static function testWrongFile () {
  
    $filter = MapFilter_TreePattern::fromFile ( 'no_such_file.xml' );
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
        
        /** An valueReplacement attribute */
        Array (
            '<pattern><all valueReplacement="pattern" /></pattern>',
            "Node 'all' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><one valueReplacement="pattern" /></pattern>',
            "Node 'one' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><opt valueReplacement="pattern" /></pattern>',
            "Node 'opt' has no attribute like 'valueReplacement'."
        ),
        Array (
            '<pattern><some valueReplacement="pattern" /></pattern>',
            "Node 'some' has no attribute like 'valueReplacement'."
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
        
        /** A validationDefault attribute */
        Array (
            '<pattern><all validationDefault="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><one validationDefault="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><opt validationDefault="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'validationDefault'."
        ),
        Array (
            '<pattern><some validationDefault="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'validationDefault'."
        ),
        
        /** A existenceDefault attribute */
        Array (
            '<pattern><all existenceDefault="defaultValue" /></pattern>',
            "Node 'all' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><one existenceDefault="defaultValue" /></pattern>',
            "Node 'one' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><opt existenceDefault="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'existenceDefault'."
        ),
        Array (
            '<pattern><some existenceDefault="defaultValue" /></pattern>',
            "Node 'some' has no attribute like 'existenceDefault'."
        ),
        
        /** A iterator attribute */
        Array (
            '<pattern><all iterator="yes" /></pattern>',
            "Node 'all' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><one iterator="yes" /></pattern>',
            "Node 'one' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><opt iterator="yes" /></pattern>',
            "Node 'opt' has no attribute like 'iterator'."
        ),
        Array (
            '<pattern><some iterator="yes" /></pattern>',
            "Node 'some' has no attribute like 'iterator'."
        ),

/*        Array (
            '<pattern><attr><attr>an_attr</attr></attr></pattern>',
            "Node 'attr' has no content."
        ),
*/      
    );
  }
  
  /**
   * Get wrong attribute
   *
   * @dataProvider provideWrongAttribute
   */
  public static function testWrongAttribute ( $pattern, $exception ) {

    try {

      MapFilter_TreePattern::load ( $pattern );
      self::fail ( 'No exception risen.' );
    } catch ( MapFilter_TreePattern_InvalidPatternAttributeException $ex ) {

      self::assertEquals ( $exception, $ex->getMessage () );
    }
  }
  
  public static function provideCompareAttached () {
  
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
   * @dataProvider      provideCompareAttached
   */
  public static function testSimpleCompareAttached ( $query, $result ) {

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
  
  public static function provideCompareStringAngFileLoad () {
  
    return Array (
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::LOCATION ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::LOGIN ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::COFFEE_MAKER ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::CAT ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::ACTION ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::FILTER ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DURATION ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::GENERATOR ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DIRECTION ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::PATHWAY ),
        Array ( PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_XML ),
    );
  }
  
  /**
   * @dataProvider      provideCompareStringAngFileLoad
   */
  public static function testCompareStringAngFileLoad ( $url ) {

    $fromFile = MapFilter_TreePattern::fromFile ( $url );
    $fromString = MapFilter_TreePattern::load (
        file_get_contents ( $url )
    );
    
    self::assertEquals ( $fromFile, $fromString );
  }
  
  public static function provideInvalidQueryStructure () {
  
    return Array (
        Array ( TRUE ),
        Array ( NULL ),
        Array ( 0 ),
        Array ( 3.14 ),
        Array ( 'asdf' ),
        Array ( new MapFilter () ),
        Array ( xml_parser_create () ),
    );
  }
  
  /**
   * @dataProvider      provideInvalidQueryStructure
   * @expectedException MapFilter_InvalidStructureException
   * @expectedExceptionMessage Data structure passed as a query can not be parsed using given pattern.
   */
  public static function testInvalidQueryStructure ( $structure ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( '<attr>attr</attr>' ),
        $structure
    );
    
    $filter->fetchResult ();
  }
}
