<pre><?php
/**
* Require tested class
*/
require_once ( __DIR__ . '/../../MapFilter.php' );

class TestMapFilter extends PHPUnit_Framework_TestCase {
  
  /**
  * Test Create KeyAttr Node
  */
  public static function testKeyAttrCreate () {
  
    $followers = Array (
        MapFilter_Pattern::getValueNode ( "task", "do" ),
        MapFilter_Pattern::getValueNode ( "tasks", "schedule" )
    );
  
    $oldPattern = $pattern = new MapFilter_Pattern (
        MapFilter_Pattern::NODETYPE_KEYATTR,
        $followers,
        "action"
    );

    $filter = new MapFilter ( $pattern );

    $queries = Array (
        Array (
            Array (),
            Array ( 'no' => "action" )
        ), Array (
            Array (),
            Array ( 'action' => "sickAction", 'task' => "sickTask")
        ), Array (
            Array ( 'action' => "do", 'task' => "myTask" ),
            Array ( 'action' => "do", 'task' => "myTask" )
        ), Array (
            Array ( 'action' => "schedule", 'tasks' => "All My Tasks" ),
            Array ( 'action' => "schedule", 'tasks' => "All My Tasks" )
        ), Array (
            Array ( 'action' => "do", 'task' => "myTask" ),
            Array ( 'action' => "do", 'task' => "myTask", 'tasks' => "My Tasks" )
        ), Array (
            Array (),
            Array ( 'action' => "do", 'nothing' => "All Day" )
        )
    );

    foreach ( $queries as $query ) {

      $filter->setQuery ( $query[ 1 ] );

      self::assertEquals (
          $query[ 0 ],
          $filter->parse ()
      );
    }
  }
  
  /** Test parsing by empty pattern */
  public static function testEmptyPattern () {
  
    $query = Array ( 'attr0' => "val0", 'attr1' => "val1" );
    
    $filter = new MapFilter ();
    $filter->setQuery ( $query );

    self::assertEquals (
        $query,
        $filter->parse ()
    );

    return;
  }
  
  /** Test PatternValue */
  public static function testValue () {
    
    $query = Array ( 'attr0' => "value" );
    $pattern = MapFilter_Pattern::getValueNode ( "attr0" );

    $filter = new MapFilter (
        $pattern,
        $query
    );

    self::assertEquals (
        $query,
        $filter->parse ()
    );
  }
  
  /**
  * Test AllNode with simple values
  */
  public static function testSimpleAllNode () {

    $less = Array ( "Attr0" => 0 );
    $accurate = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $pattern = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ALL, 
        Array (
            MapFilter_Pattern::getValueNode ( "Attr0" ),
            MapFilter_Pattern::getValueNode ( "Attr1" )
        )
    );
    

    $filter = new MapFilter (
        $pattern
    );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->parse ()
    );
    
    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->parse ()
    );

    /** Test error */
    $filter->setQuery ( $less );

    self::assertEquals (
      Array (),
      $filter->parse ()
    );
  }
  
  /**
  * Test OneNode with simple values
  */
  public static function testSimpleOneNode () {

    $accurate = Array ( "Attr0" => 0 );
    $all = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $pattern = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_ONE, 
        Array (
            MapFilter_Pattern::getValueNode ( "Attr0" ),
            MapFilter_Pattern::getValueNode ( "Attr1" )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->parse ()
    );

    /** Test choose */
    $filter->setQuery ( $all );

    self::assertEquals (
      $accurate,
      $filter->parse ()
    );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->parse ()
    );
  }
  
  /**
  * Test OptNode with simple values
  */
  public static function testSimpleOptNode () {

    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    $one = Array ( "Attr0" => 0 );
    $all = Array ( "Attr0" => 0, "Attr1" => 1 );
    $nothing = Array ();
    
    $pattern = new MapFilter_Pattern ( MapFilter_Pattern::NODETYPE_OPT, 
        Array (
            MapFilter_Pattern::getValueNode ( "Attr0" ),
            MapFilter_Pattern::getValueNode ( "Attr1" )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $all,
      $filter->parse ()
    );

    /** Test accurate */
    $filter->setQuery ( $one );

    self::assertEquals (
      $one,
      $filter->parse ()
    );

    /** Test All */
    $filter->setQuery ( $all );

    self::assertEquals (
      $all,
      $filter->parse ()
    );
    
    /** Test nothing */
    $filter->setQuery ( $nothing );

    self::assertEquals (
      $nothing,
      $filter->parse ()
    );
  }
}

BaseTest::take ( "TestMapFilter" );
?>
</pre>