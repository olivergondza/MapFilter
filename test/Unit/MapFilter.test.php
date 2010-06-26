<?php
/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter.php' );

/**
 * @group	Unit
 * @group	Unit::MapFilter
 */
class TestMapFilter extends PHPUnit_Framework_TestCase {
  
  /** Test whether MapFilter class implements MapFilter_Interface */
  public static function testInterface () {
  
    self::assertTrue (
        is_a ( new MapFilter, 'MapFilter_Interface' )
    );
  }
  
  /** Test whether a fetch get deprecated error */
  public static function testDeprecatedFetch () {
  
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
  
  /** Test whether a parse get deprecated error */
  public static function testDeprecatedParse () {
  
    $filter = new MapFilter ();
    @$filter->parse ();
    
    $error = error_get_last ();
    
    self::assertEquals (
        'MapFilter::parse () is deprecated.',
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
  
    $pattern = new MapFilter_Pattern_Null ();
    
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
}