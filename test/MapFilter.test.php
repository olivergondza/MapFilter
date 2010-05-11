<?php
/**
* Require tested class
*/
require_once ( dirname ( __FILE__ ) . '/../MapFilter.php' );

class TestMapFilter extends PHPUnit_Framework_TestCase {
  
  /** Test whether MapFilter class implements MapFilter_Interface */
  public static function testInterface () {
  
    self::assertTrue (
        is_a ( new MapFilter, 'MapFilter_Interface' )
    );
  }
  
  /** Test whether a fetch get deprecated error */
  public static function testDeprecated () {
  
    $filter = new MapFilter ();
    @$filter->fetch ();
    
    $error = error_get_last ();
    
    self::assertEquals (
        'MapFilter::fetch () is deprecated. Use MapFilter::getResults () instead.',
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
  
  /**@{*/
  /** Test invoke */
  public static function testInvocation () {
  
    $pattern = MapFilter_Pattern::load (
        '<pattern>
          <some>
            <attr>attr0</attr>
            <attr>attr1</attr>
          </some>
        </pattern>'
    );
    
    $query = Array ( 'attr0' => 'value', 'attr1' => 'value' );

    // Configure filter using constructor.
    $filter0 = new MapFilter ( $pattern, $query );
    
    // Create empty filter and configure it using fluent methods.
    $filter1 = new MapFilter ();
    $filter1 -> setPattern ( $pattern ) -> setQuery ( $query );

    // Optional combination of both can be used as well
    $filter2 = new MapFilter ( $pattern );
    $filter2 -> setQuery ( $query );

    self::assertEquals (
        $filter0,
        $filter1
    );
    
    self::assertEquals (
        $filter0,
        $filter2
    );
  }
  /**@}*/
  
  /** Test PatternAttr */
  public static function testAttr () {
    
    $query = Array ( 'attr0' => "value" );

    $attr = new MapFilter_Pattern_Tree_Leaf_Attr ();

    $pattern = new MapFilter_Pattern (
        $attr -> setAttribute ( "attr0" )
    );

    $filter = new MapFilter (
        $pattern,
        $query
    );

    self::assertEquals (
        $query,
        $filter->getResults ()
    );
  }
  
  /**
  * Test AllNode with simple values
  */
  public static function testSimpleAllNode () {

    $less = Array ( "Attr0" => 0 );
    $accurate = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $all = new MapFilter_Pattern_Tree_Node_All ();
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    
    $pattern = new MapFilter_Pattern (
        $all -> setContent (
            Array (
                $attr0 -> setAttribute ( "Attr0" ),
                $attr1 -> setAttribute ( "Attr1" )
            )
        )
    );
    

    $filter = new MapFilter ( $pattern );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test error */
    $filter->setQuery ( $less );

    self::assertEquals (
      Array (),
      $filter->getResults ()
    );
  }
  
  /**
  * Test OneNode with simple values
  */
  public static function testSimpleOneNode () {

    $accurate = Array ( "Attr0" => 0 );
    $all = Array ( "Attr0" => 0, "Attr1" => 1 );
    $more = Array ( "Attr0" => 0, "Attr1" => 1, "Attr2" => 2 );
    
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $one = new MapFilter_Pattern_Tree_Node_One ();

    $pattern = new MapFilter_Pattern (
        $one -> setContent (
            Array (
                $attr0 -> setAttribute  ( "Attr0" ),
                $attr1 -> setAttribute  ( "Attr1" )
            )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test accurate */
    $filter->setQuery ( $accurate );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );

    /** Test choose */
    $filter->setQuery ( $all );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
    );
    
    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $accurate,
      $filter->getResults ()
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
    
    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $opt = new MapFilter_Pattern_Tree_Node_Opt ();

    $pattern = new MapFilter_Pattern (
        $opt -> setContent (
            Array (
                $attr0 -> setAttribute  ( "Attr0" ),
                $attr1 -> setAttribute  ( "Attr1" )
            )
        )
    );

    $filter = new MapFilter ( $pattern );

    /** Test trim */
    $filter->setQuery ( $more );

    self::assertEquals (
      $all,
      $filter->getResults ()
    );

    /** Test accurate */
    $filter->setQuery ( $one );

    self::assertEquals (
      $one,
      $filter->getResults ()
    );

    /** Test All */
    $filter->setQuery ( $all );

    self::assertEquals (
      $all,
      $filter->getResults ()
    );
    
    /** Test nothing */
    $filter->setQuery ( $nothing );

    self::assertEquals (
      $nothing,
      $filter->getResults ()
    );
  }
  
  public static function provideKeyAttrCreate () {
  
    return Array (
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
  }
  
  /**
  * Test Create KeyAttr Node
  *
  * action => do ; task => ...
  * action => schedule; tasks => ...
  *
  * @dataProvider provideKeyAttrCreate
  */
  public static function testKeyAttrCreate ( $result, $query ) {

    $attr0 = new MapFilter_Pattern_Tree_Leaf_Attr ();
    $attr1 = new MapFilter_Pattern_Tree_Leaf_Attr ();

    $followers = Array (
        $attr0 -> setAttribute  ( "task" ) -> setValueFilter ( "do" ),
        $attr1 -> setAttribute  ( "tasks") -> setValueFilter ( "schedule" )
    );
  
    $keyattr = new MapFilter_Pattern_Tree_Node_KeyAttr ();
    $pattern = new MapFilter_Pattern (    
        $keyattr -> setContent ( $followers ) -> setAttribute ( "action" )
    );

    $filter = new MapFilter ( $pattern );

    $filter->setQuery ( $query );

    self::assertEquals (
        $result,
        $filter->getResults ()
    );
  }
}