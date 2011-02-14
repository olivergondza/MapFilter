<?php
/**
* User Tests
*/  

require_once PHP_MAPFILTER_CLASS;

/**
 * @group        User
 * @group	User::TreePattern
 */
class MapFilter_Test_User_TreePattern extends PHPUnit_Framework_TestCase {

  /**@{*/
  /**
   * Use slightly wrong pattern
   * @expectedException MapFilter_TreePattern_InvalidPatternElementException
   * @expectedExceptionMessage  Invalid pattern element 'lantern'.
   */
  public static function testWrongPattern () {

    $pattern = MapFilter_TreePattern::load ( '<lantern></lantern>' );
    self::fail ( 'No exception risen' );
  }
  
  public static function provideWrongCount () {
  
    return Array (
        Array (
            '<patterns><pattern></pattern></patterns>',
            0,
            'pattern'
        ),
        Array (
            '<patterns></patterns>',
            0,
            'pattern'
        ),
        Array ( '
            <pattern>
              <all />
              <all />
            </pattern>',
            2,
            'pattern'
        ),
        Array ( '
            <patterns>
              <pattern>
                <opt></opt>
                <all></all>
              </pattern>
            </patterns>',
            2,
            'pattern'
        ),
        Array ( '
            <node_attr attr="attr">
              <all />
              <all />
            </node_attr>',
            2,
            'node_attr'
        ),
    );
  }
  
  /**
   * @dataProvider      provideWrongCount
   */
  public static function testWrongCount ( $patternStr, $count, $node ) {

    try {

      $pattern = MapFilter_TreePattern::load ( $patternStr );
    } catch ( MapFilter_TreePattern_NotExactlyOneFollowerException $ex ) {

      self::assertEquals (
          "The '$node' node must have exactly one follower but $count given.",
          $ex->getMessage ()
      );
    }
  }
  
  /**
   * Invalid node
   *
   * @expectedException MapFilter_TreePattern_InvalidPatternElementException
   * @expectedExceptionMessage  Invalid pattern element 'wrongnode'.
   */
  public static function testWrongTag () {
  
    $pattern = MapFilter_TreePattern::load (
        '<pattern><wrongnode></wrongnode></pattern>'
    );
  }
  
  /**
   * Multiple tree deserialization
   * @expectedException MapFilter_TreePattern_NotExactlyOneFollowerException
   * @expectedExceptionMessage  The 'pattern' node must have exactly one follower but 2 given.
   */
  public static function testMultipleTree () {
  
    $pattern = MapFilter_TreePattern::load (
        '<pattern><opt></opt><all></all></pattern>'
    );
  }
  
  /**
   * Invalid attr
   * @expectedException MapFilter_TreePattern_InvalidPatternAttributeException
   * @expectedExceptionMessage Node 'key_attr' has no attribute like 'wrongattr'.
   */
  public static function testWrongAttr () {
  
    $pattern = '
        <pattern>
          <key_attr attr="attrName" wrongattr="wrongAttrName">
            <attr forValue="thisName">thisAttr</attr>
          </key_attr>
        </pattern>
    ';
  
    MapFilter_TreePattern::load ( $pattern );
  }
  
  public static function provideSimpleOneWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => 'a.out' ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-o' => 'a.out' ),
            Array ()
        )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider      provideSimpleOneWhitelist
   */
  public static function testSimpleOneWhitelist ( $query, $result ) {
  
    $pattern = '
        <pattern>
          <one>
            <attr>-h</attr>
            <attr>-v</attr>
          </one>
        </pattern>
    ';
    
    // Instantiate MapFilter with pattern and query
    $filter = new MapFilter (
        MapFilter_TreePattern::load ( $pattern ),
        $query
    );
    
    // Get desired result
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  /**@}*/

  public static function provideSimpleAllWhitelist () {
  
    return Array (
        Array (
            Array ( '-f' => NULL, '-o' => NULL, '-v' => NULL ),
            Array ( '-f' => NULL, '-o' => NULL )
        )
    );
  }

  /*@{*/
  /**
   * @dataProvider      provideSimpleAllWhitelist
   */
  public static function testSimpleAllWhitelist ( $query, $result ) {
  
    $pattern = '
        <pattern>
          <all>
            <attr>-f</attr>
            <attr>-o</attr>
          </all>
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
  /*@}*/
  
  public static function provideSimpleOptWhitelist () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL ),
            Array ( '-h' => NULL, '-v' => NULL )
        ),
        Array (
            Array ( '-v' => NULL ),
            Array ( '-v' => NULL )
        ),
        Array (
            Array ( '-h' => NULL ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => 'a.out' ),
            Array ( '-h' => NULL, '-v' => NULL )
        )
    );
  }
  
  /*@{*/
  /**
   * @dataProvider      provideSimpleOptWhitelist
   */
  public static function testSimpleOptWhitelist ( $query, $result ) {
    
    $pattern = '
        <pattern>
          <opt>
            <attr>-h</attr>
            <attr>-v</attr>
          </opt>
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
  /*@{*/
}
