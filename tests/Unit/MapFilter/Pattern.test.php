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
            Array (),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val' ),
            Array ( 'attr1' => 'val' ),
            Array ()
        ),
        Array (
            Array ( 'attr1' => 'val', 'attr2' => 'val' ),
            Array ( 'attr1' => 'val', 'attr2' => 'val' ),
            Array ()
        ),
        Array (
            Array ( 'wrongAttr' => 'val' ),
            Array (),
            Array ( 'wrongAttr' => 'val' )
        ),
        Array (
            Array ( 'attr1' => 'val', 'attr2' => 'val', 'attr3' => 'val' ),
            Array ( 'attr1' => 'val', 'attr2' => 'val' ),
            Array ( 'attr3' => 'val' )
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
    $pattern = new ArrayKeyWhitelistPattern (
        Array ( '-v', '-h', '-o' )
    );
    
    $query = Array ( '-o' => './a.out', '-f' => NULL );
    
    $filter = new MapFilter (
        $pattern,
        $query
    );
    
    $valid = $filter->fetchResult ()->getValid ();
    $redundant = $filter->fetchResult ()->getRedundant ();
    
    // Assert redundant options
    
    // Perform an action based on valid options
    
    /** __PatternUsage */
    
    $this->assertEquals (
        Array ( '-o' => './a.out' ), $valid
    );
    
    $this->assertEquals (
        Array ( '-f' => NULL ), $redundant
    );
  }
}

/** ArrayKeyWhitelistPatternInterfaces__ */
interface ValidInterface extends MapFilter_PatternInterface {

    /*
     * Get filtering results
     *
     * @return  Array   Array containing keys and values from
     *                  query that DID match the whitelist pattern.
     */
    public function getValid();
}

interface RedundantInterface extends MapFilter_PatternInterface {

    /*
     * Get filtered out
     *
     * @return  Array   Array containing key and values from querty
     *                  that DID NOT match the whitelist pattern.
     */
    public function getRedundant();
}
/** __ArrayKeyWhitelistPatternInterfaces */

/** ArrayKeyWhitelistPattern__ */
class ArrayKeyWhitelistPattern implements ValidInterface, RedundantInterface {

    private $_whitelist = Array();

    private $_valid = Array();

    private $_redundant = Array();

    /*
     * Initialize whitelist
     *
     * @param     Array   $whitelist      Array of allowed keys.
     */
    public function __construct(Array $whitelist)
    {
        $this->_whitelist = $whitelist;
    }
    
    /*
     * Perform a filtering
     *
     * @param     Array   $query  A query to filter.
     */
    public function parse($query)
    {
        foreach ($query as $keyCandidate => $valueCandidate) {
        
            if(in_array($keyCandidate, $this->_whitelist)) {
      
                $this->_valid[ $keyCandidate ] = $valueCandidate;
            } else {
          
                $this->_redundant[ $keyCandidate ] = $valueCandidate;
            }
        }
    }
    
    public function getValid()
    {
        return $this->_valid;
    }
    
    public function getRedundant()
    {
        return $this->_redundant;
    }
}
/** __ArrayKeyWhitelistPattern */
