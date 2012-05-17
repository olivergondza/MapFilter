<?php
/**
 * User Tests
 */

require_once PHP_MAPFILTER_CLASS;

/**
 * @group       User
 * @group	User::MapFilter
 */
class MapFilter_Test_User_MapFilter extends PHPUnit_Framework_TestCase {

  /** Empty pattern filtering */
  /** [testEmptyPattern] */
  public function testEmptyPattern () {

    $query = Array ( 'attr' => 'value' );
    $filter = new MapFilter ();
    $filter->setQuery ( $query );

    $this->assertEquals (
        $query,
        $filter->fetchResult ()
    );
  }
  /** [testEmptyPattern] */
}
