<?php
/**
* User Tests
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group        User
* @group	User::TreePattern
*/
class MapFilter_Test_User_TreePattern extends PHPUnit_Framework_TestCase {

  /**@{*/
  /** Use slightly wrong pattern */
  public static function testWrongPattern () {
    
    try {
      $pattern = MapFilter_TreePattern::load ( "<lantern></lantern>" );
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  public static function provideWrongCount () {
  
    return Array (
        Array (
            '<patterns><pattern></pattern></patterns>',
        ),
        Array (
            '<patterns></patterns>'
        ),
        Array ( '
            <pattern>
              <all />
              <all />
            </pattern>
        ' ),
        Array ( '
            <patterns>
              <pattern>
                <all />
                <all />
              </pattern>
            </patterns>
        ' ),
        Array ( '
            <node_attr attr="attr">
              <all />
              <all />
            </node_attr>
        ' ),
    );
  }
  
  /**
   * @dataProvider      provideWrongCount
   */
  public static function testWrongCount ( $patternStr ) {
  
    try {
      
      $pattern = MapFilter_TreePattern::load ( $patternStr );
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Exception $exception ) {
    
      self::assertEquals (
          MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
          $exception->getCode (),
          (String) $exception
      );
    }
  }
  
  
  /** Invalid node */
  public static function testWrongTag () {
  
    try {
      $pattern = MapFilter_TreePattern::load (
          "<pattern><wrongnode></wrongnode></pattern>"
      );
    } catch ( MapFilter_TreePattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
          $exception->getCode ()
      );
    }
  }
  
  /** Multiple tree deserialization */
  public static function testMultipleTree () {
  
    try {
      $pattern = MapFilter_TreePattern::load (
          "<pattern><opt></opt><all></all></pattern>"
      );
    } catch ( MapFilter_TreePattern_Exception $exception ) {
      
      self::assertEquals (
          MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
          $exception->getCode ()
      );
    }
  }
  
  /** Invalid attr */
  public static function testWrongAttr () {
  
    $pattern = '
    <pattern>
      <key_attr attr="attrName" wrongattr="wrongAttrName">
        <attr forValue="thisName">thisAttr</attr>
      </key_attr>
    </pattern>
    ';
  
    try {
      MapFilter_TreePattern::load ( $pattern );
    } catch ( MapFilter_TreePattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ATTRIBUTE,
          $exception->getCode ()
      );
    }
  
  }
  
  public static function provideSimpleOneWhitelist () {
  
    return Array (
        Array (
            Array ( '-h' => NULL, '-v' => NULL, '-o' => "a.out" ),
            Array ( '-h' => NULL )
        ),
        Array (
            Array ( '-o' => "a.out" ),
            Array ()
        )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider      provideSimpleOneWhitelist
   */
  public static function testSimpleOneWhitelist ( $query, $result ) {
  
    $pattern = "
    <pattern>
      <one>
        <attr>-h</attr>
        <attr>-v</attr>
      </one>
    </pattern>
    ";
    
    // Instantiate MapFilter with pattent and query
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
  
    $pattern = "
    <pattern>
      <all>
        <attr>-f</attr>
        <attr>-o</attr>
      </all>
    </pattern>
    ";
    
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
            Array ( '-h' => NULL, '-v' => NULL, '-o' => "a.out" ),
            Array ( '-h' => NULL, '-v' => NULL )
        )
    );
  }
  
  /*@{*/
  /**
   * @dataProvider      provideSimpleOptWhitelist
   */
  public static function testSimpleOptWhitelist ( $query, $result ) {
    
    $pattern = "
    <pattern>
      <opt>
        <attr>-h</attr>
        <attr>-v</attr>
      </opt>
    </pattern>
    ";
    
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
