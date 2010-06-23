<?php
/**
 * Require tested class
 */
require_once ( dirname ( __FILE__ ) . '/../MapFilter/TreePattern.php' );

require_once ( dirname ( __FILE__ ) . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 */
class TestTreePattern extends PHPUnit_Framework_TestCase {  
  
    /** Test whether a fetch get deprecated error */
  public static function testDeprecatedKeyAttrNode () {
  
    require_once (
        dirname ( __FILE__ )
        . '/../MapFilter/TreePattern/Tree/Node/KeyAttr.php'
    );
  
    $filter = @ new MapFilter_TreePattern_Tree_Node_KeyAttr ();
    
    $error = error_get_last ();
    
    self::assertEquals (
        'MapFilter_TreePattern_Tree_Node_KeyAttr is deprecated. Use MapFilter_TreePattern_Tree_Leaf_KeyAttr instead.',
        $error[ 'message' ]
    );
    
    $level =  ( defined ( 'E_USER_DEPRECATED' ) )
        ? E_USER_DEPRECATED
        : E_USER_NOTICE
    ;

    self::assertEquals (
        $level,
        $error[ 'type' ]
    );
  }
  
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
  
    self::assertEquals (
        MapFilter_TreePattern::load ( $pattern ),
        MapFilter_TreePattern::load ( $lazyPattern )
    );
  }
  
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
  
  /** Obtain an attribute from KeyAttr node*/
  public static function testKeyAttrAttribute () {
  
    $attr = 'An attribute';
    
    $pattern = new MapFilter_TreePattern_Tree_Leaf_KeyAttr ();
    $pattern -> setAttribute ( $attr );
    
    self::assertEquals (
        $attr,
        $pattern -> getAttribute ()
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
  
  public static function provideLoadFromFileComparison () {
  
    return Array (
          Array ( Test_Source::LOCATION ),
          Array ( Test_Source::LOGIN ),
          Array ( Test_Source::COFFEE_MAKER ),
          Array ( Test_Source::ACTION ),
          Array ( Test_Source::FILTER ),
          Array ( Test_Source::DURATION )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider provideLoadFromFileComparison
   */
  public static function testLoadFromFileComparison ( $filename ) {
  
    $string = file_get_contents ( $filename );
  
    $filePattern = MapFilter_TreePattern::fromFile ( $filename );
    $stringPattern = MapFilter_TreePattern::load ( $string );
    
    self::assertEquals (
        $filePattern,
        $stringPattern
    );
  }
  /**@}*/
  
  /** Test MapFilter_TreePattern at first */
  public static function testBuild () {

    /** Create complex pattern tree */
    $all = new MapFilter_TreePattern_Tree_Node_All ();
    $one = new MapFilter_TreePattern_Tree_Node_One ();
    $opt = new MapFilter_TreePattern_Tree_Node_Opt ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $pattern = new MapFilter_TreePattern (
        $all -> setContent (
            Array (
                $attr0 -> setAttribute (
                    "value"
                ),
                $one -> setContent (
                    Array (
                        $opt -> setContent (
                            Array ()
                        ),
                        $attr1 -> setAttribute (
                            "value"
                        )
                    )
                )
            )
        )
    );
    
    /** Create tree by chunks */
    $step0 = new MapFilter_TreePattern_Tree_Node_Opt ();
    $step0 -> setContent ( Array () );
    
    $step1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $step1 -> setAttribute ( "value" );
    
    $step2 = new MapFilter_TreePattern_Tree_Node_One ();
    $step2 -> setContent ( Array ( $step0, $step1) );
    
    $step3 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $step3 -> setAttribute ( "value" );
    
    $step4 = new MapFilter_TreePattern_Tree_Node_All ();
    
    $check = new MapFilter_TreePattern (
        $step4 -> setContent ( Array ( $step3, $step2 ) )
    );
    
    self::assertEquals (
        $check,
        $pattern
    );
    return;
  }
  
  /** Test location.xml parsing */
  public static function testLocation () {
    
    $all0 = new MapFilter_TreePattern_Tree_Node_All ();
    $all1 = new MapFilter_TreePattern_Tree_Node_All ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $one = new MapFilter_TreePattern_Tree_Node_One ();
    
    $location = new MapFilter_TreePattern (
        $all0 -> setContent (
            Array (
                $attr0 -> setAttribute ( "action" ),
                $one -> setContent (
                    Array (
                        $attr1 -> setAttribute ( "nick" ),
                        $all1 -> setContent (
                            Array (
                                $attr2 -> setAttribute ( "x" ),
                                $attr3 -> setAttribute ( "y" ),
                                $attr4 -> setAttribute ( "z" ),
                            )
                        )
                    )
                )
            )
        )
    );

    $pattern = MapFilter_TreePattern::fromFile (
        Test_Source::LOCATION
    );

    self::assertEquals (
        $location,
        $pattern
    );
  }
  
  /** Test login.xml parsing */
  public static function testLogin () {

    $all0 = new MapFilter_TreePattern_Tree_Node_All ();
    $all1 = new MapFilter_TreePattern_Tree_Node_All ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr5 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_TreePattern_Tree_Node_Opt ();
    $one = new MapFilter_TreePattern_Tree_Node_One ();
    $login = new MapFilter_TreePattern (
        $all0 -> setFlag ( "login" ) -> setContent (
            Array (
                $attr0 -> setAttribute ( "name" ) -> setAssert ( "no_name" ),
                $attr1 -> setAttribute ( "pass" ) -> setAssert ( "no_password" ),
                $opt -> setContent (
                    Array (
                        $attr2 -> setAttribute ( "use-https" ) -> setFlag ( "use_https" ) -> setValuePattern ( "yes" ),
                        $all1 -> setFlag ( "remember" ) -> setContent (
                            Array (
                                $attr3 -> setAttribute ( "remember" ),
                                $one -> setAssert (
                                    "no_remember_method"
                                ) -> setContent (
                                    Array (
                                        $attr4 -> setAttribute ( "user" ),
                                        $attr5 -> setAttribute ( "server" ),
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    );
    
    $pattern = MapFilter_TreePattern::fromFile (
        Test_Source::LOGIN
    );
    
    self::assertEquals (
        $login,
        $pattern
    );
    
    return;
  }

  /** Test action.xml parsing */  
  public static function testAction () {

    $keyattr = new MapFilter_TreePattern_Tree_Leaf_KeyAttr ();
    $all0 = new MapFilter_TreePattern_Tree_Node_All ();
    $all1 = new MapFilter_TreePattern_Tree_Node_All ();
    $attr0 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr5 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr6 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $attr7 = new MapFilter_TreePattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_TreePattern_Tree_Node_Opt ();
    $one0 = new MapFilter_TreePattern_Tree_Node_One ();
    $one1 = new MapFilter_TreePattern_Tree_Node_One ();
    
    $action = new MapFilter_TreePattern (
        $keyattr -> setAttribute ( "action" ) -> setContent (
            Array (
                $one0 -> setValueFilter ( "delete" ) -> setContent (
                    Array (
                        $attr0 -> setAttribute ( "id" ),
                        $attr1 -> setAttribute ( "file_name" )
                    )
                ) ,
                $all0 -> setValueFilter ( "create" ) -> setContent (
                    Array (
                        $attr2 -> setAttribute ( "new_file" ),
                        $opt -> setContent (
                            Array (
                                $attr3 -> setAttribute ( "new_name" )
                            )
                        )
                    )
                ) ,
                $all1 -> setValueFilter ( "rename" ) -> setContent (
                    Array (
                        $one1 -> setContent (
                            Array (
                                $attr4 -> setAttribute ( "id" ),
                                $attr5 -> setAttribute ( "old_name" )
                            )
                        ),
                        $attr6 -> setAttribute ( "new_name" )
                    )
                ),
                $attr7 -> setValueFilter ( "report" ) -> setAttribute ( "id" )
            )
        ) 
    );

    $pattern = MapFilter_TreePattern::fromFile (
        Test_Source::ACTION
    );

    self::assertEquals (
        $action,
        $pattern
    );
    
    return;
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
    
      self::fail ( "Wrong exception: " . $ex->getMessage () );
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

      self::assertEquals ( $exception, $ex->getMessage () );
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
  
  public static function provideKeyAttrArrayValue () {
  
    return Array (
        Array (
            Array (),
            Array ( 'auto' => 'defaultValue' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr' ),
            Array ()
        ),
        Array (
            Array ( 'order' => Array () ),
            Array ( 'auto' => 'defaultValue' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'order' => new ArrayIterator ( Array () ) ),
            Array ( 'auto' => 'defaultValue' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'order' => new EmptyIterator () ),
            Array ( 'auto' => 'defaultValue' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr' ),
            Array (),
        ),
        Array (
            Array ( 'order' => Array ( 'first' ) ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator ( Array ( 'first' ) ) ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => Array ( 'first', 'first' ) ),
            Array ( 'order' => Array ( 'first', 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first', 'first' ) )
            ),
            Array ( 'order' => Array ( 'first', 'first' ), 'attr0' => 0 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first' )
            ), 'attr0' => -1 ),
            Array ( 'order' => Array ( 'first' ), 'attr0' => -1 ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        10 => Array (
            Array ( 'order' => Array ( 'first', 'second' ), 'attrn' => 'n' ),
            Array ( 'order' => Array ( 'first', 'second' ), 'attr0' => '0', 'attr1' => '1' ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'order' => new ArrayIterator (
                Array ( 'first', 'second' )
            ), 'attrn' => 'n' ),
            Array ( 'order' => Array ( 'first', 'second' ), 'attr0' => '0', 'attr1' => '1' ),
            Array (),
            Array ( 'a_keyattr' ),
        ),
        Array (
            Array ( 'auto' => 'attr0' ),
            Array ( 'auto' => 'attr0' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator ( Array ( 'attr0' ) ) ),
            Array ( 'auto' => Array ( 'attr0' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator (
                Array ( 'attr0', 'attr1' )
            ) ),
            Array ( 'auto' => Array ( 'attr0', 'attr1' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => 'value' ),
            Array ( 'auto' => 'defaultValue' ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => Array ( 'value' ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),
        Array (
            Array ( 'auto' => new ArrayIterator ( Array ( 'value' ) ) ),
            Array ( 'auto' => Array ( 'defaultValue' ) ),
            Array ( 'wrong_keyattr' => 'wrong_keyattr'  ),
            Array (),
        ),

    );
  }
  
  /**
   * Test array filtering
   *
   * @dataProvider      provideKeyAttrArrayValue
   */
  public static function testKeyAttrArrayValue (
      $query, $results, $asserts, $flags
  ) {
  
    $pattern = '
    <pattern>
      <one>
        <key_attr
            attr="order"
            iterator="yes"
            assert="wrong_keyattr"
            flag="a_keyattr"
        >
          <attr forValue="first"  default="0">attr0</attr>
          <attr forValue="second" default="1">attr1</attr>
          <attr forValue="(?!first|second).*"  default="n">attrn</attr>
        </key_attr>
        <attr
            iterator="auto"
            valuePattern="attr."
            default="defaultValue"
        >auto</attr>
      </one>
    </pattern>
    ';
    
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
  
  public static function provideKeyAttrArrayValueExceptions () {
  
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
   * @dataProvider      provideKeyAttrArrayValueExceptions
   */
  public static function testKeyAttrArrayValueExceptions ( $query, $expectedException ) {
  
    $pattern = '
    <pattern>
      <opt>
        <key_attr iterator="yes" attr="arrayAttr"></key_attr>
        <key_attr iterator="no" attr="scalarAttr"></key_attr>
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
  
  public static function provideKeyAttrDefaultValuePattern () {
  
    return Array (
        Array (
            Array (),
            Array ( 'keyattr' => 'value', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'INVALID_VALUE' ),
            Array ( 'keyattr' => 'value', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'INVALID_VALUE' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'value' ),
            Array ( 'keyattr' => 'value', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value' ) ),
            Array ( 'keyattr' => Array ( 'value' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'value1' ),
            Array ( 'keyattr' => 'value1', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value1' ) ),
            Array ( 'keyattr' => Array ( 'value1' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'value0' ),
            Array ( 'keyattr' => 'value0', 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value0' ) ),
            Array ( 'keyattr' => Array ( 'value0' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'value11' ),
            Array ( 'keyattr' => 'value11', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11' ) ),
            Array ( 'keyattr' => Array ( 'value11' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => 'value10' ),
            Array ( 'keyattr' => 'value10', 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value10' ) ),
            Array ( 'keyattr' => Array ( 'value10' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value10', 'value42' ) ),
            Array ( 'keyattr' => Array ( 'value10', 'value42' ), 'odd' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11', 'value41' ) ),
            Array ( 'keyattr' => Array ( 'value11', 'value41' ), 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value11', 'value42' ) ),
            Array ( 'keyattr' => Array ( 'value11', 'value42' ), 'odd' => 'yes', 'even' => 'yes' )
        ),
        Array (
            Array ( 'keyattr' => Array ( 'value12', 'value41' ) ),
            Array ( 'keyattr' => Array ( 'value12', 'value41' ), 'odd' => 'yes', 'even' => 'yes' )
        ),
    );
  }
  
  /**
   * @dataProvider      provideKeyAttrDefaultValuePattern
   */
  public static function testKeyAttrDefaultValuePattern ( $query, $result ) {
  
    $pattern = '
    <pattern>
      <key_attr
          iterator="auto"
          attr="keyattr"
          default="value"
          valuePattern="value[0-9]*"
      >
        <attr forValue="value([0-9]*[13579])?" default="yes">even</attr>
        <attr forValue="value([0-9]*[02468])" default="yes">odd</attr>
      </key_attr>
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
