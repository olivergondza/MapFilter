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
   * Test MapFilter_NullPattern implicit usage
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
   * Test MapFilter_NullPattern usage
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
        $filter->fetchResult ()
    );
  }
  
  public static function provideUserPatternFiltering () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val' ),
            Array ( 'attr1' => 'val' ),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val1', 'attr2' => 'val2' ),
            Array ( 'attr1' => 'val1', 'attr2' => 'val2' ),
            Array ()
        ),
        Array (
            Array ( 'wrongAttr' => 'val' ),
            Array (),
            Array ( 'wrongAttr' )
        ),
        Array (
            Array ( 'attr1' => 'val1', 'attr2' => 'val2', 'attr3' => 'val' ),
            Array ( 'attr1' => 'val1', 'attr2' => 'val2' ),
            Array ( 'attr3' )
        )
    );
  }
  
  /**
   * Test user defined pattern
   *
   * @dataProvider	provideUserPatternFiltering
   */
  public static function testWhitelistResultPatternFiltering (
      Array $query, Array $result, Array $redundant
  ) {
  
    $pattern = new ArrayKeyWhitelistPattern (
        Array ( 'attr1', 'attr2' )
    );

    $filter = new MapFilter (
        $pattern,
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getValid ()
    );
    
    self::assertEquals (
        $redundant,
        $filter->fetchResult ()->getRedundant ()
    );
  }
  
  public function testCommand () {
  
    /** PatternUsage__ */
    // Allowed options
    $pattern = new ArrayKeyWhitelistPattern (
        Array ( '-v', '-h', '-o' )
    );
    
    // Actual options
    $query = Array ( '-o' => './a.out', '-f' => null, '-v' => null );
    
    $filter = new MapFilter ( $pattern, $query );
    
    $valid = $filter->fetchResult ()->getValid ();
    $redundant = $filter->fetchResult ()->getRedundant ();
    
    $this->assertEquals ( Array ( '-o' => './a.out', '-v' => null ), $valid );
    $this->assertEquals ( Array ( '-f' ), $redundant );
    
    /** __PatternUsage */
  }
}

/** ArrayKeyWhitelistResult__ */
class ArrayKeyWhitelistResult {

   private $_valid;
   private $_redundant;

    /*
     * Initialize whitelist
     *
     * @param     Array   $whitelist      Array of allowed keys.
     */
    public function __construct(Array $valid, Array $redundant)
    {
        $this->_valid = $valid;
        $this->_redundant = $redundant;
    }

    /*
     * Get filtering results
     *
     * @return  Array   Array containing keys and values from
     *                  query that DID match the whitelist pattern.
     */
    public function getValid()
    {
        return $this->_valid;
    }
    
    /*
     * Get filtered out
     *
     * @return  Array   Array containing keys from querty that DID NOT match
     *                  the whitelist pattern.
     */
    public function getRedundant()
    {
        return $this->_redundant;
    }
}
/** __ArrayKeyWhitelistResult */

/** ArrayKeyWhitelistPattern__ */
class ArrayKeyWhitelistPattern implements Mapfilter_PatternInterface {

    private $_whitelist;

    /*
     * Initialize whitelist
     *
     * @param     Array   $whitelist      Array of allowed keys.
     */
    public function __construct(Array $whitelist)
    {
        $this->_whitelist = array_unique($whitelist);
    }
    
    /*
     * Perform a filtering
     *
     * @param     Array   $query  A query to filter.
     * @return    ArrayKeyWhitelistResult
     */
    public function parse($query)
    {
        $valid = $redundant = Array();
        foreach ($query as $keyCandidate => $valueCandidate) {
        
            if(in_array($keyCandidate, $this->_whitelist)) {
      
                $valid[ $keyCandidate ] = $valueCandidate;
            } else {
          
                $redundant[] = $keyCandidate;
            }
        }
        
        return new ArrayKeyWhitelistResult($valid, $redundant);
    }
}
/** __ArrayKeyWhitelistPattern */
