<?php
/**
* User Tests
*/  

/**
 * Require tested class
 */
require_once ( dirname ( __FILE__ ) . '/../../MapFilter.php' );

/**
* @group        User
* @group	User::MapFilter
*/
class TestUserMapFilter extends PHPUnit_Framework_TestCase {

  /**@{*/
  /** Empty pattern filtering */
  public static function testEmptyPattern  () {
  
    $query = Array ( 'attr' => 'value' );
    $filter = new MapFilter ();
    $filter->setQuery (
        $query
    );
    
    self::assertEquals (
        $query,
        $filter->getResults ()
    );
  }
  /**@}*/
}
