<?php
/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_DIR . '/TreePattern.php' );

require_once ( PHP_MAPFILTER_DIR . '/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::NodeAttr
 */
class MapFilter_Test_Unit_TreePattern_NodeAttr extends
    PHPUnit_Framework_TestCase
{
  
  /**
   * try attach nothing
   */
  public static function testWrongPatternAttachment () {
  
    $pattern = '
        <pattern>
          <all attachPattern="NoSuchPattern" />
        </pattern>
    ';
    
    $filter = new MapFilter (
          MapFilter_TreePattern::load ( $pattern )
    );
    
    try {
    
      $filter->fetchResult ();

      self::fail ( 'No exception risen' );
      
    } catch ( MapFilter_TreePattern_Exception $exception ) {
    
      self::assertEquals (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_NAME,
          $exception->getCode ()
      );
    }
  }
  
  /**
   * More than one node_attr follower
   */
  public static function testMultipleFollower () {
  
    $pattern = '
    <pattern>
      <node_attr attr="attr">
        <attr>a</attr>
        <attr>b</attr>
      </node_attr>
    </pattern>
    ';
  
    try {
    
      MapFilter_TreePattern::load ( $pattern );
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Exception $exception ) {

      $expectedException = new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
          Array ( 'NodeAttr', 2 )
      );

      self::assertEquals (
          $expectedException->getMessage (),
          $exception->getMessage ()
      );
    }
  }
  
  /**
   * No node_attr follower
   */
  public static function testNoFollower () {
  
    $pattern = '
    <pattern>
      <node_attr attr="attr">
      </node_attr>
    </pattern>
    ';
  
    $query = Array ( 'attr' => Array ( 'attr' ) );
  
    try {
      
      $filter = new MapFilter (
          MapFilter_TreePattern::load ( $pattern ),
          $query
      );
      $filter->fetchResult ();
      
      self::fail ( 'No exception risen' );
    } catch ( MapFilter_TreePattern_Exception $exception ) {

      $expectedException = new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
          Array ( 'node_attr', 0 )
      );

      self::assertEquals (
          $expectedException->getCode (),
          $exception->getCode ()
      );
    }
  }
  
  public static function provideAssertAndFlags () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr1' ),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr0' => 'val0' ) ),
            Array (),
            Array (),
            Array ( 'attr1' => 'attr1' )
        ),
        Array (
            Array ( 'attr0' => Array ( 'attr1' => 'val1', 'attr0' => 'val0' ) ),
            Array ( 'attr0' => Array ( 'attr1' => 'val1' ) ),
            Array ( 'attr1' ),
            Array ()
        ),
        Array (
            Array ( 'attr0' => Array () ),
            Array (),
            Array (),
            Array ()
        )
    );
  }
  
  /**
   * @dataProvider      provideAssertAndFlags
   */
  public static function testAssertAndFlags (
      $query, $result, $flags, $asserts
  ) {
  
    $assembled = MapFilter_TreePattern::load ( '
          <pattern>
            <node_attr attr="attr0">
              <attr flag="attr1" assert="attr1">attr1</attr>
            </node_attr>
          </pattern>
    ' );

    $assembled = new MapFilter ( $assembled, $query );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $flags,
        $assembled->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
        $asserts,
        $assembled->fetchResult ()->getAsserts ()
    );
  }
  
  public static function provideMultipleCompare () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'value' => 1 ),
            Array ()
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one' ),
            Array ()
        ),
        Array (
            Array ( 'value' => 1, 'zero' => 'zero' ),
            Array ()
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one', 'left' => 'hand' ),
            Array ( 'value' => 1, 'one' => 'one', 'left' => 'hand' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'left' => 'hand' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'left' => 'hand' )
        ),

        Array (
            Array ( 'value' => 1, 'one' => 'one', 'right' => 'hand' ),
            Array ( 'value' => 1, 'one' => 'one', 'right' => 'hand' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'right' => 'hand' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'right' => 'hand' )
        ),
        Array (
            Array ( 'value' => 2, 'one' => 'one', 'left' => 'hand' ),
            Array ()
        ),
        Array (
            Array ( 'value' => 1, 'one' => 'one', 'up' => 'up', 'down' => 'down' ),
            Array ( 'value' => 1, 'one' => 'one', 'up' => 'up', 'down' => 'down' )
        ),
        Array (
            Array ( 'value' => 0, 'zero' => 'zero', 'up' => 'up', 'down' => 'down' ),
            Array ( 'value' => 0, 'zero' => 'zero', 'up' => 'up', 'down' => 'down' )
        ),
    );
  }
  
  /**
   * @dataProvider      provideMultipleCompare
   */
  public static function testMultipleCompare ( $query, $result ) {
  
    $simple = MapFilter_TreePattern::load ( '
        <pattern>
            <all>
              <key_attr attr="value">
                <attr forValue="1">one</attr>
                <attr forValue="0">zero</attr>
              </key_attr>
              <one>
                <attr>left</attr>
                <attr>right</attr>
                <all>
                  <attr>up</attr>
                  <attr>down</attr>
                </all>
              </one>
            </all>
        </pattern>
    ' );
    $simple = new MapFilter ( $simple, $query );
    
    $assembled = MapFilter_TreePattern::load ( '
        <patterns>
          <pattern>
            <all>
              <all attachPattern="KeyAttrPattern"/>
              <all attachPattern="OnePattern"/>
            </all>
          </pattern>

          <pattern name="KeyAttrPattern">
            <key_attr attr="value">
              <attr forValue="1">one</attr>
              <attr forValue="0">zero</attr>
            </key_attr>
          </pattern>

          <pattern name="OnePattern">
            <one>
              <attr>left</attr>
              <attr>right</attr>
              <all attachPattern="AllPattern"/>
            </one>
          </pattern>
          
          <pattern name="AllPattern">
            <all>
              <attr>up</attr>
              <attr>down</attr>
            </all>
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
    
    /** Try pattern resuse */
    $simple->setQuery ( $query );
    $assembled->setQuery ( $query );

    self::assertEquals (
        $result,
        $simple->fetchResult ()->getResults ()
    );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
  }
  
  public static function provideCyclicParse () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'left' => Array ( 'value' => 1 ) ),
            Array ()
        ),
        Array (
            Array ( 'left' => 1 ),
            Array ()
        ),
        Array (
            Array ( 'value' => 42 ),
            Array ( 'value' => 42 )
        ),
        Array (
            Array (
                'left' => Array (
                    'value' => 1
                ),
                'right' => Array (
                    'value' => 0
                )
            ),
            Array (
                'left' => Array (
                    'value' => 1
                ),
                'right' => Array (
                    'value' => 0
                )
            )
        ),
        Array (
            Array (
                'left' => Array (
                    'left' => Array ( 'value' => 0 ),
                    'right' => Array ( 'value' => 1 )
                ),
                'right' => Array (
                    'value' => 0
                )
            ),
            Array (
                'left' => Array (
                    'left' => Array ( 'value' => 0 ),
                    'right' => Array ( 'value' => 1 )
                ),
                'right' => Array (
                    'value' => 0
                )
            )
        )
    );
  }
  
  /**
   * @dataProvider      provideCyclicParse
   */
  public static function testCyclicParse ( $query, $result ) {
  
    $assembled = MapFilter_TreePattern::load ( '
        <patterns>
          <pattern>
            <one>
              <all>
                <node_attr attr="left" attachPattern="main"/>
                <node_attr attr="right" attachPattern="main"/>
              </all>
              <attr>value</attr>
            </one>
          </pattern>
        </patterns>
    ' );

    $assembled = new MapFilter ( $assembled, $query );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
    
    /** Try pattern resuse */
    $assembled->setQuery ( $query );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
  }
  
  public static function provideIteratorParse () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'links' => 'links' )
        ),
        Array (
            Array ( 'url' => 'my.url.com' ),
            Array (),
            Array (),
            Array ( 'links' => 'links' )
        ),
        Array (
            Array ( 'links' => 'my.url.com'),
            Array (),
            Array (),
            Array ( 'links' => 'links' )
        ),
        Array (
            Array ( 'links' => Array ( 'link' => 'my.url.com' ) ),
            Array (),
            Array (),
            Array ( 'links' => 'links' )
        ),
        Array (
            Array ( 'links' => Array ( 'url' => 'my.url.com', 'title' => 'A' ) ),
            Array (),
            Array (),
            Array ( 'links' => 'links' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'my.url.com' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ( 'title' => 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ( 'title' => 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'title' => 'A' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ( 'title' => 'title' )
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com', 'title' => 'B' )
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    Array ( 'url' => 'my.url.com', 'title' => 'B' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' ),
                    'url' => 'my.url.com'
                )
            ),
            Array (
                'links' => Array (
                    Array ( 'url' => 'my.url.com', 'title' => 'A' )
                )
            ),
            Array ( 'links', 'url', 'title' ),
            Array ()
        ),
        Array (
            Array (
                'links' => Array ( Array () )
            ),
            Array (),
            Array (),
            Array ( 'links' => 'links', 'title' => 'title' ),
        ),
        Array (
            Array (
                'links' => Array ()
            ),
            Array (),
            Array (),
            Array ( 'links' => 'links' ),
        ),
    );
  }
  
  /**
   * @dataProvider      provideIteratorParse
   */
  public static function testIteratorParse (
      $query, $result, $flags, $asserts
  ) {
  
    $assembled = MapFilter_TreePattern::load ( '
        <pattern>
          <node_attr attr="links" iterator="yes" flag="links" assert="links">
            <all>
              <attr flag="url" assert="title">url</attr>
              <attr flag="title" assert="title">title</attr>
            </all>
          </node_attr>
        </pattern>
    ' );

    $assembled = new MapFilter ( $assembled, $query );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $flags,
        $assembled->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
        $asserts,
        $assembled->fetchResult ()->getAsserts ()
    );
    
    /** Try pattern resuse */
    $assembled->setQuery ( $query );

    self::assertEquals (
        $result,
        $assembled->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $flags,
        $assembled->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
        $asserts,
        $assembled->fetchResult ()->getAsserts ()
    );
  }
}
