<?php
/**
* Require tested class
*/
require_once ( dirname ( __FILE__ ) . '/../MapFilter/Pattern.php' );

class TestPattern extends PHPUnit_Framework_TestCase {  
  
  /** Parse a tag that hes not been wrapped in <pattern> tags */
  public static function testUnwrapped () {

    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<pattern><attr>anAttribute</attr></pattern>';
  
    self::assertEquals (
        MapFilter_Pattern::load ( $pattern ),
        MapFilter_Pattern::load ( $lazyPattern )
    );
  }
  
  /** Attribute tag value should overwrite attribute value */
  public static function testAttrOverwrite () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="wrongAttribute">anAttribute</attr>';
    
    self::assertEquals (
        MapFilter_Pattern::load ( $lazyPattern ),
        MapFilter_Pattern::load ( $pattern )
    );
  }
  
  /** Compare attributes sat by different ways */
  public static function testCompareAttrs () {
  
    $lazyPattern = '<attr>anAttribute</attr>';
    $pattern = '<attr attr="anAttribute"></attr>';
    
    self::assertEquals (
        MapFilter_Pattern::load ( $lazyPattern ),
        MapFilter_Pattern::load ( $pattern )
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
    
      $pattern = MapFilter_Pattern::load ( $pattern );
      self::fail ( "No exception risen." );

    } catch ( MapFilter_Pattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_Pattern_Exception::MISSING_ATTRIBUTE_VALUE,
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
  
    $filePattern = MapFilter_Pattern::fromFile ( $filename );
    $stringPattern = MapFilter_Pattern::load ( $string );
    
    self::assertEquals (
        $filePattern,
        $stringPattern
    );
  }
  /**@}*/
  
  /** Test MapFilter_Pattern at first */
  public static function testBuild () {

    /** Create complex pattern tree */
    $all = new MapFilter_Pattern_Tree_Node_All ();
    $one = new MapFilter_Pattern_Tree_Node_One ();
    $opt = new MapFilter_Pattern_Tree_Node_Opt ();
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $pattern = new MapFilter_Pattern (
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
    $step0 = new MapFilter_Pattern_Tree_Node_Opt ();
    $step0 -> setContent ( Array () );
    
    $step1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $step1 -> setAttribute ( "value" );
    
    $step2 = new MapFilter_Pattern_Tree_Node_One ();
    $step2 -> setContent ( Array ( $step0, $step1) );
    
    $step3 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $step3 -> setAttribute ( "value" );
    
    $step4 = new MapFilter_Pattern_Tree_Node_All ();
    
    $check = new MapFilter_Pattern (
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
    
    $all0 = new MapFilter_Pattern_Tree_Node_All ();
    $all1 = new MapFilter_Pattern_Tree_Node_All ();
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $one = new MapFilter_Pattern_Tree_Node_One ();
    
    $location = new MapFilter_Pattern (
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

    $pattern = MapFilter_Pattern::fromFile (
        Test_Source::LOCATION
    );

    self::assertEquals (
        $location,
        $pattern
    );
  }
  
  /** Test login.xml parsing */
  public static function testLogin () {

    $all0 = new MapFilter_Pattern_Tree_Node_All ();
    $all1 = new MapFilter_Pattern_Tree_Node_All ();
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr5 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_Pattern_Tree_Node_Opt ();
    $one = new MapFilter_Pattern_Tree_Node_One ();
    $login = new MapFilter_Pattern (
        $all0 -> setFlag ( "login" ) -> setContent (
            Array (
                $attr0 -> setAttribute ( "name" ) -> setAssert ( "no_name" ),
                $attr1 -> setAttribute ( "pass" ) -> setAssert ( "no_password" ),
                $opt -> setContent (
                    Array (
                        $attr2 -> setAttribute ( "use-https" ),
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
    
    $pattern = MapFilter_Pattern::fromFile (
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

    $keyattr = new MapFilter_Pattern_Tree_Node_KeyAttr ();
    $all0 = new MapFilter_Pattern_Tree_Node_All ();
    $all1 = new MapFilter_Pattern_Tree_Node_All ();
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr2 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr3 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr4 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr5 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr6 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr7 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_Pattern_Tree_Node_Opt ();
    $one0 = new MapFilter_Pattern_Tree_Node_One ();
    $one1 = new MapFilter_Pattern_Tree_Node_One ();
    
    $action = new MapFilter_Pattern (
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

    $pattern = MapFilter_Pattern::fromFile (
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

      $filter = MapFilter_Pattern::fromFile ( "no_such_file.xml" );
      self::fail ( "No exception risen." );
      
    } catch ( MapFilter_Pattern_Exception $exception ) {

      self::assertEquals (
          MapFilter_Pattern_Exception::LIBXML_WARNING,
          $exception->getCode ()
      );
    } catch ( Exception $ex ) {
    
      self::fail ( "Wrong exception: " . $ex->getMessage () );
    }
  }
  
  public static function provideWrongAttribute () {
  
    return Array (
        Array (
            '<pattern><all attr="attrName" /></pattern>',
            "Node 'all' has no attribute like 'attr'."
        ),
        Array (
            '<pattern><one valuePattern="pattern" /></pattern>',
            "Node 'one' has no attribute like 'valuePattern'."
        ),
        Array (
            '<pattern><opt default="defaultValue" /></pattern>',
            "Node 'opt' has no attribute like 'default'."
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

      MapFilter_Pattern::load ( $pattern );
      self::fail ( "No exception risen." );

    } catch ( MapFilter_Pattern_Exception $ex ) {

      self::assertEquals ( $exception, $ex->getMessage () );
    } catch ( Exception $ex ) {
    
      self::fail ( "Wrong exception: " . $ex->getMessage () );
    }
  }
}
