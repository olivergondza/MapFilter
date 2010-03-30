<?php
/**
* Require tested class
*/
require_once ( dirname ( __FILE__ ) . '/../Pattern.php' );

class TestMapFilter_Pattern extends BaseTest {  
  
  /**
  * Test MapFilter_Pattern at first
  */
  public static function testBuild () {

    /**
    * Create complex pattern tree
    */
    $pattern = new MapFilter_Pattern (
        new MapFilter_Pattern_Node_All (
            Array (
                new MapFilter_Pattern_Node_Attr ( "value" ),
                new MapFilter_Pattern_Node_One (
                    Array (
                        new MapFilter_Pattern_Node_Opt ( Array () ),
                        new MapFilter_Pattern_Node_Attr ( "value" )
                    )
                )
            )
        )
    );
    
    /**
    * Create tree by chunks
    */
    $step0 = new MapFilter_Pattern_Node_Opt ( Array () );
    $step1 = new MapFilter_Pattern_Node_Attr ( "value" );
    $step2 = new MapFilter_Pattern_Node_One ( Array ( $step0, $step1) );
    $step3 = new MapFilter_Pattern_Node_Attr ( "value" );
    $check = new MapFilter_Pattern (
        new MapFilter_Pattern_Node_All ( Array ( $step3, $step2 ) )
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
    
    $location = new MapFilter_Pattern (
        new MapFilter_Pattern_Node_All (
            Array (
                new MapFilter_Pattern_Node_Attr ( "action" ),
                new MapFilter_Pattern_Node_One (
                    Array (
                        new MapFilter_Pattern_Node_Attr ( "nick" ),
                        new MapFilter_Pattern_Node_All (
                            Array (
                                new MapFilter_Pattern_Node_Attr ( "x" ),
                                new MapFilter_Pattern_Node_Attr ( "y" ),
                                new MapFilter_Pattern_Node_Attr ( "z" ),
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

    $login = new MapFilter_Pattern (
        new MapFilter_Pattern_Node_All (
            Array (
                new MapFilter_Pattern_Node_Attr ( "name" ),
                new MapFilter_Pattern_Node_Attr ( "pass" ),
                new MapFilter_Pattern_Node_Opt (
                    Array (
                        new MapFilter_Pattern_Node_Attr ( "use-https" ),
                        new MapFilter_Pattern_Node_All (
                            Array (
                                new MapFilter_Pattern_Node_Attr ( "remember" ),
                                new MapFilter_Pattern_Node_One (
                                    Array (
                                        new MapFilter_Pattern_Node_Attr ( "user" ),
                                        new MapFilter_Pattern_Node_Attr ( "server" ),
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

    $action = new MapFilter_Pattern (
        new MapFilter_Pattern_Node_KeyAttr (
            Array (
                new MapFilter_Pattern_Node_One (
                    Array (
                        new MapFilter_Pattern_Node_Attr ( "id" ),
                        new MapFilter_Pattern_Node_Attr ( "file_name" )
                    ),
                    "delete"
                ),
                new MapFilter_Pattern_Node_All (
                    Array (
                        new MapFilter_Pattern_Node_Attr ( "new_file" ),
                        new MapFilter_Pattern_Node_Opt (
                            Array (
                                new MapFilter_Pattern_Node_Attr ( "new_name" )
                            )
                        )
                    ),
                    "create"
                ),
                new MapFilter_Pattern_Node_All (
                    Array (
                        new MapFilter_Pattern_Node_One (
                            Array (
                                new MapFilter_Pattern_Node_Attr ( "id" ),
                                new MapFilter_Pattern_Node_Attr ( "old_name" )
                            )
                        ),
                        new MapFilter_Pattern_Node_Attr ( "new_name" )
                    ),
                    "rename"
                ),
                new MapFilter_Pattern_Node_Attr (
                    "id",
                    "report"
                )
            ),
            "action"
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
