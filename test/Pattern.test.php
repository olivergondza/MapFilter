<?php
/**
* Test Pattern
*/

require_once ( dirname ( __FILE__ ) . '/../MapFilter.php' );

require_once ( dirname ( __FILE__ ) . '/../MapFilter/Pattern/Null.php' );

class TestPattern extends PHPUnit_Framework_TestCase {  
  
  /**
  * Test MapFilter_Pattern_Null usage
  */
  public static function testMock () {
  
    $filter = new MapFilter (
        new MapFilter_Pattern_Null ()
    );
    
    $implicitFilter = new MapFilter ();
    
    self::assertEquals (
        $filter,
        $implicitFilter
    );
    
    $query = Array (
        'attr0' => 'val0',
        'attr1' => 'val1'
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        Array (),
        $filter->getAsserts ()
    );
    
    self::assertEquals (
        Array (),
        $filter->getFlags ()
    );
    
    self::assertEquals (
        $query,
        $filter->getResults ()
    );
  }
}