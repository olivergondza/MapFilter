<?php
/**
 * Test Pattern
 */

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter.php' );

require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter/Pattern/Null.php' );

/**
 * @group	Unit
 * @group	Unit::MapFilter
 * @group	Unit::MapFilter::PAttern
 */
class TestMapFilterPattern extends PHPUnit_Framework_TestCase {  
  
  /**
   * Test MapFilter_Pattern_Null implicit usage
   */
  public static function testImplicitMock () {
  
    $filter = new MapFilter (
        new MapFilter_Pattern_Null ()
    );
    
    $implicitFilter = new MapFilter ();
    
    self::assertEquals (
        $filter,
        $implicitFilter
    );
  }
  
  /**
   * Test MapFilter_Pattern_Null usage
   */
  public static function testMock () {
  
    $filter = new MapFilter (
        new MapFilter_Pattern_Null ()
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
  
  public static function provideUserPatternFiltering () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val' ),
            Array ( 'attr1' => 'val' )
        ),
        Array (
            Array ( 'attr1' => 'val', 'attr2' => 'val' ),
            Array ( 'attr1' => 'val', 'attr2' => 'val' )
        ),
        Array (
            Array ( 'wrongAttr' => 'val' ),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val', 'attr2' => 'val', 'attr3' => 'val' ),
            Array ( 'attr1' => 'val', 'attr2' => 'val' )
        )
    );
  }
  
  /**
   * Test user defined pattern
   *
   * @dataProvider	provideUserPatternFiltering
   */
  public static function testUserPatternFiltering (
      Array $query, Array $result
  ) {
  
    $filter = new MapFilter (
        new WhitelistResultPattern (
            Array ( 'attr1', 'attr2' )
        )
    );
    
    $filter->setQuery ( $query, $result );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}

/**
 * User pattern
 */
class WhitelistResultPattern implements
    MapFilter_Pattern_Interface,
    MapFilter_Pattern_ResultInterface
{

  private $whitelist = Array ();
  
  private $results = Array ();

  /** Create user pattern */
  public function __construct ( Array $whitelist ) {
  
    $this->whitelist = $whitelist;
  }
  
  /** Perform a filtering */
  public function parse ( $query ) {
  
    foreach ( $this->whitelist as $validValue ) {
    
      if ( array_key_exists ( $validValue, $query ) ) {
  
        $this->results[ $validValue ] = $query[ $validValue ];
      }
    }
  }
  
  /** Get filtering results */
  public function getResults () {
  
    return $this->results;
  }
}
