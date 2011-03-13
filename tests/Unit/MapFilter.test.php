<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_CLASS;

/**
 * @group	Unit
 * @group	Unit::MapFilter
 */
class MapFilter_Test_Unit_MapFilter extends PHPUnit_Framework_TestCase {
  
  /** Test whether MapFilter class implements MapFilter_Interface */
  public static function testInterface () {
  
    self::assertTrue (
        is_a ( new MapFilter (), 'MapFilter_Interface' )
    );
  }
  
  public static function testInvocation () {
  
    /** testInvocation__ */
  
    $pattern = new MapFilter_NullPattern ();
    
    $query = Array ( 'attr0' => 'value', 'attr1' => 'value' );

    // Configure filter using constructor.
    $filter0 = new MapFilter ( $pattern, $query );
    
    // Create empty filter and configure it using fluent interface.
    $filter1 = new MapFilter ();
    $filter1 -> setPattern ( $pattern ) -> setQuery ( $query );

    // Optional combination of both can be used as well.
    $filter2 = new MapFilter ( $pattern );
    $filter2 -> setQuery ( $query );

    /** __testInvocation */

    self::assertEquals (
        $filter0,
        $filter1
    );
    
    self::assertEquals (
        $filter0,
        $filter2
    );
  }
}