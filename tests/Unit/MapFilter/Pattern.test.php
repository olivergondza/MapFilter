<?php
/**
 * Test Pattern
 */
require_once PHP_MAPFILTER_DIR . '/../MapFilter.php';

/**
 * @group	Unit
 * @group	Unit::MapFilter
 * @group	Unit::MapFilter::Pattern
 */
class MapFilter_Test_Unit_MapFilter_Pattern extends
    PHPUnit_Framework_TestCase
{
  
  /**
   * Test MapFilter_Pattern_Null implicit usage
   */
  public static function testImplicitMock () {
  
    $filter = new MapFilter (
        new MapFilter_NullPattern ()
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
        new MapFilter_NullPattern ()
    );
    
    $query = Array (
        'attr0' => 'val0',
        'attr1' => 'val1'
    );
    
    $filter->setQuery ( $query );
    
    self::assertEquals (
        $query,
        $filter->fetchResult ()->getResults ()
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
  /*@{*/
  public static function testWhitelistResultPatternFiltering (
      Array $query, Array $result
  ) {
  
    // 'attr1' and 'attr2' will be excluded from result
    $pattern = new MapFilter_WhitelistResultPattern(
        Array ( 'attr1', 'attr2' )
    );

    // Configure filter 
    $filter = new MapFilter (
        $pattern,
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
  /*@}*/
}

/**
 * @class       MapFilter_WhitelistResultPattern
 */
/*@{*/
/** __MapFilter_WhitelistResultPattern__ */
interface MapFilter_ResultInterface extends MapFilter_PatternInterface {

  public function getResults ();
}

class MapFilter_WhitelistResultPattern implements
    MapFilter_ResultInterface
{

  /* Filtering whitelist */
  private $_whitelist = Array ();
  
  /* Result cache */
  private $_results = Array ();

  /* Create user pattern */
  public function __construct ( Array $whitelist ) {
  
    $this->_whitelist = $whitelist;
  }
  
  /* Perform a filtering */
  public function parse ( $query ) {
  
    foreach ( $this->_whitelist as $validValue ) {
    
      if ( array_key_exists ( $validValue, $query ) ) {
  
        $this->_results[ $validValue ] = $query[ $validValue ];
      }
    }
  }
  
  /** Get filtering results */
  public function getResults () {
  
    return $this->_results;
  }
}
/*@}*/
