<pre><?php
/**
* Require tested class
*/
require_once ( __DIR__ . '/../SerializedPattern.php' );

class TestMapFilter_SerializedPattern extends PHPUnit_Framework_TestCase {

  /**
  * Test location.xml parsing
  */
  public static function testLocation () {
    
    $location =  new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
        Array (
            MapFilter_Pattern::getValueNode ( "action" ),
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE,
                Array (
                    MapFilter_Pattern::getValueNode ( "nick" ),
                    new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
                        Array (
                            MapFilter_Pattern::getValueNode ( "x" ),
                            MapFilter_Pattern::getValueNode ( "y" ),
                            MapFilter_Pattern::getValueNode ( "z" ),
                        )
                    )
                )
            )
        )
    );
    
    $pattern = MapFilter_SerializedPattern::fromFile (
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

    $login = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
        Array (
            MapFilter_Pattern::getValueNode ( "name" ),
            MapFilter_Pattern::getValueNode ( "pass" ),
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_OPT,
                Array (
                    MapFilter_Pattern::getValueNode ( "use-https" ),
                    new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
                        Array (
                            MapFilter_Pattern::getValueNode ( "remember" ),
                            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE,
                                Array (
                                    MapFilter_Pattern::getValueNode ( "user" ),
                                    MapFilter_Pattern::getValueNode ( "server" ),
                                )
                            )
                        )
                    )
                )
            )
        )
    );
    
    $pattern = MapFilter_SerializedPattern::fromFile (
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

    $action = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_KEYATTR,
        Array (
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE,
                Array (
                    MapFilter_Pattern::getValueNode ( "id" ),
                    MapFilter_Pattern::getValueNode ( "file_name" )
                ),
                NULL,
                "delete"
            ),
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
                Array (
                    MapFilter_Pattern::getValueNode ( "new_file" ),
                    new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_OPT,
                        Array (
                            MapFilter_Pattern::getValueNode ( "new_name" )
                        )
                    )
                ),
                NULL,
                "create"
            ),
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,
                Array (
                    new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE,
                        Array (
                            MapFilter_Pattern::getValueNode ( "id" ),
                            MapFilter_Pattern::getValueNode ( "old_name" )
                        )
                    ),
                    MapFilter_Pattern::getValueNode ( "new_name" )
                ),
                NULL,
                "rename"
            ),
            MapFilter_Pattern::getValueNode (
                "id",
                "report"
            )
        ),
        "action"
    );

    $pattern = MapFilter_SerializedPattern::fromFile (
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
      $filter = MapFilter_SerializedPattern::fromFile ( "no_such_file.xml" );
    } catch ( MapFilter_Exception $exception ) {

      self::assertEquals (
          MapFilter_Exception::LIBXML_WARNING,
          $exception->getCode ()
      );
    }
  }
}

BaseTest::take ( "TestMapFilter_SerializedPattern" );

?>
</pre>