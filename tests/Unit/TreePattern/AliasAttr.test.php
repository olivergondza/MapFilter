<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::AliasAttr
 */
class
    MapFilter_Test_Unit_TreePattern_AliasAttr
extends
    PHPUnit_Framework_TestCase
{  
  
  public static function provideEmptyAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' => 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => 'hey' )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array (),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array (),
            Array ( 'fName' ),
            Array ()
        ),
    );
  }
  
  /**
   * Test empty alias
   *
   * @dataProvider      provideEmptyAliasAttr
   */
  public static function testEmptyAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/"/>
        </pattern>
    ' );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
    
    self::assertEquals (
      $flags,
      $filter->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
      $asserts,
      $filter->fetchResult ()->getAsserts ()
    );
  }
  
  /**
   * @expectedException MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException
   * @expectedExceptionMessage  Only allowed follower for AliasAttribute is Attr.
   */
  public static function testDisallowedFollowerException () {
  
    $pattern = '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <alias attr="other_num" />
          </alias>
        </pattern>
    ';
  
    $pattern = MapFilter_TreePattern::load ( $pattern );
  }
  
  public static function provideOneToOneAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' => 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => 'hey' )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array ( 'number' => 0 ),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array ( 'number' => 0 ),
            Array ( 'fName' ),
            Array ()
        ),

    );
  }
  
  /**
   * Test transalte num to number
   *
   * @dataProvider      provideOneToOneAliasAttr
   */
  public static function testOneToOneAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
          </alias>
        </pattern>
    '
    );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
    
    self::assertEquals (
      $flags,
      $filter->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
      $asserts,
      $filter->fetchResult ()->getAsserts ()
    );
  }
  
  public static function provideOneToManyAliasAttr () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'aName' => 'aName' )
        ),
        Array (
            Array ( 'num' => 'hey' ),
            Array (),
            Array (),
            Array ( 'aName' => 'hey' )
        ),
        Array (
            Array ( 'num' => 0 ),
            Array ( 'number' => 0, 'value' => 'yes' ),
            Array ( 'fName' ),
            Array ()
        ),
        Array (
            Array ( 'num' => 0, 'name' => 'asdf' ),
            Array ( 'number' => 0, 'value' => 'yes' ),
            Array ( 'fName' ),
            Array ()
        ),

    );
  }
  
  /**
   * Test transalte num to number
   *
   * @dataProvider      provideOneToManyAliasAttr
   */
  public static function testOneToManyAliasAttr (
      $query, $result, $flags, $asserts
  ) {

    $pattern = MapFilter_TreePattern::load ( '
        <pattern>
          <alias attr="num" flag="fName" assert="aName" valuePattern="/\d/">
            <attr>number</attr>
            <attr valuePattern="/(?!)/" default="yes">value</attr>
          </alias>
        </pattern>
    '
    );

    $filter = new MapFilter ( $pattern );
    
    $filter->setQuery ( $query );

    self::assertEquals (
      $result,
      $filter->getResults ()
    );
    
    self::assertEquals (
      $flags,
      $filter->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
      $asserts,
      $filter->fetchResult ()->getAsserts ()
    );
  }
}
