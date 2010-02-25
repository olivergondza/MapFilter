<pre><?php
/**
* Require tested class
*/
require_once ( __DIR__ . '/../Pattern.php' );

class TestMapFilter_Pattern extends PHPUnit_Framework_TestCase {  
  
  /**
  * Test MapFilter_Pattern at first
  */
  public static function testBuild () {
    
    /**
    * Create complex pattern tree
    */
    $pattern = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL, 
        Array (
            MapFilter_Pattern::getValueNode ( "value" ),
            new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE, 
                Array (
                    new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_OPT, Array () ),
                    MapFilter_Pattern::getValueNode ( "value" )
                )
            )
        )
    );
    
    /**
    * Create tree by chunks
    */
    $step0 = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_OPT,  Array () );
    $step1 = MapFilter_Pattern::getValueNode ( "value" );
    $step2 = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE,  Array ( $step0, $step1) );
    $step3 = MapFilter_Pattern::getValueNode ( "value" );
    $check = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL,  Array ( $step3, $step2 ) );
    
    self::assertEquals (
        $check,
        $pattern
    );
    return;
  }
}

BaseTest::take ( "TestMapFilter_Pattern" );
?>
</pre>