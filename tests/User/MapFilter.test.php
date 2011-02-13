<?php
/**
 * User Tests
 */  

/**
 * Require tested class
 */
require_once PHP_MAPFILTER_CLASS;

/**
 * @group        User
 * @group	User::MapFilter
 */
class MapFilter_Test_User_MapFilter extends PHPUnit_Framework_TestCase {

  /**@{*/
  /** Empty pattern filtering */
  public static function testEmptyPattern  () {
  
    $query = Array ( 'attr' => 'value' );
    $filter = new MapFilter ();
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->getResults ()
    );
  }
  /**@}*/
}
