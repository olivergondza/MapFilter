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
        new MapFilter () instanceof MapFilter_Interface
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
    $filter1->setPattern ( $pattern )->setQuery ( $query );

    // Optional combination of both can be used as well.
    $filter2 = new MapFilter ( $pattern );
    $filter2->setQuery ( $query );

    /** __testInvocation */

    self::assertEquals ( $filter0, $filter1 );
    self::assertEquals ( $filter0, $filter2 );
  }
  
  /**
   * 
   */
  public function testParseResultCashing () {
  
    $query = 42;
  
    $pattern = $this->getMock ( 'MapFilter_NullPattern' );

    $pattern->expects ( $this->exactly ( 3 ) )
        ->method ( 'parse' )
        ->with ( $query )
    ;
    
    /** testParseResultCashing__ */
    
    // Initial pattern configuration
    $filter = new MapFilter ( $pattern, $query );

    // Parsing is done here
    $newResult = $filter->fetchResult ();

    // Here we parse again since we have reset the query
    $newQueryResult = $filter->setQuery ( $query )->fetchResult ();
    
    // Here we parse again since we have reset the pattern
    $newPatternResult = $filter->setPattern ( $pattern )->fetchResult ();
    
    // No needed to parse the same query using the same pattern
    // $newPatternResult === $sameResult
    $sameResult = $filter->fetchResult ();
    
    /** __testParseResultCashing */

    $this->assertEquals ( $pattern, $newResult );
    $this->assertEquals ( $pattern, $newQueryResult );
    $this->assertEquals ( $pattern, $newPatternResult );
    $this->assertEquals ( $pattern, $sameResult );
    
    $this->assertSame ( $sameResult, $newPatternResult );
    $this->assertNotSame ( $sameResult, $newQueryResult );
    $this->assertNotSame ( $sameResult, $newResult );
    $this->assertNotSame ( $newResult, $newQueryResult );
  }
}
