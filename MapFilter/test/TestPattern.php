<?php
/**
* Require tested class
*/
require_once ( dirname ( __FILE__ ) . '/../Pattern.php' );

class TestPattern extends BaseTest {  
  
  /**
  * Test MapFilter_Pattern at first
  */
  public static function testBuild () {

    /**
    * Create complex pattern tree
    */
    $all = new MapFilter_Pattern_Node_All ();
    $one = new MapFilter_Pattern_Node_One ();
    $opt = new MapFilter_Pattern_Node_Opt ();
    $attr0 = new MapFilter_Pattern_Node_Attr ();
    $attr1 = new MapFilter_Pattern_Node_Attr ();
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
    
    /**
    * Create tree by chunks
    */
    $step0 = new MapFilter_Pattern_Node_Opt ();
    $step0 -> setContent ( Array () );
    
    $step1 = new MapFilter_Pattern_Node_Attr ();
    $step1 -> setAttribute ( "value" );
    
    $step2 = new MapFilter_Pattern_Node_One ();
    $step2 -> setContent ( Array ( $step0, $step1) );
    
    $step3 = new MapFilter_Pattern_Node_Attr ();
    $step3 -> setAttribute ( "value" );
    
    $step4 = new MapFilter_Pattern_Node_All ();
    
    $check = new MapFilter_Pattern (
        $step4 -> setContent ( Array ( $step3, $step2 ) )
    );
    
    self::assertEquals (
        $check,
        $pattern
    );
    return;
  }
  
  /**
  * Test location.xml parsing
  */
  public static function testLocation () {
    
    $all0 = new MapFilter_Pattern_Node_All ();
    $all1 = new MapFilter_Pattern_Node_All ();
    $attr0 = new MapFilter_Pattern_Node_Attr ();
    $attr1 = new MapFilter_Pattern_Node_Attr ();
    $attr2 = new MapFilter_Pattern_Node_Attr ();
    $attr3 = new MapFilter_Pattern_Node_Attr ();
    $attr4 = new MapFilter_Pattern_Node_Attr ();
    $one = new MapFilter_Pattern_Node_One ();
    
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
  
  /**
  * Test login.xml parsing
  */
  public static function testLogin () {

    $all0 = new MapFilter_Pattern_Node_All ();
    $all1 = new MapFilter_Pattern_Node_All ();
    $attr0 = new MapFilter_Pattern_Node_Attr ();
    $attr1 = new MapFilter_Pattern_Node_Attr ();
    $attr2 = new MapFilter_Pattern_Node_Attr ();
    $attr3 = new MapFilter_Pattern_Node_Attr ();
    $attr4 = new MapFilter_Pattern_Node_Attr ();
    $attr5 = new MapFilter_Pattern_Node_Attr ();
    $opt = new MapFilter_Pattern_Node_Opt ();
    $one = new MapFilter_Pattern_Node_One ();
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

  /**
  * Test action.xml parsing
  */  
  public static function testAction () {

    $keyattr = new MapFilter_Pattern_Node_KeyAttr ();
    $all0 = new MapFilter_Pattern_Node_All ();
    $all1 = new MapFilter_Pattern_Node_All ();
    $attr0 = new MapFilter_Pattern_Node_Attr ();
    $attr1 = new MapFilter_Pattern_Node_Attr ();
    $attr2 = new MapFilter_Pattern_Node_Attr ();
    $attr3 = new MapFilter_Pattern_Node_Attr ();
    $attr4 = new MapFilter_Pattern_Node_Attr ();
    $attr5 = new MapFilter_Pattern_Node_Attr ();
    $attr6 = new MapFilter_Pattern_Node_Attr ();
    $attr7 = new MapFilter_Pattern_Node_Attr ();
    $opt = new MapFilter_Pattern_Node_Opt ();
    $one0 = new MapFilter_Pattern_Node_One ();
    $one1 = new MapFilter_Pattern_Node_One ();
    
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
    } catch ( MapFilter_Exception $exception ) {

      self::assertEquals (
          MapFilter_Exception::LIBXML_WARNING,
          $exception->getCode ()
      );
    }
  }
}
?>
