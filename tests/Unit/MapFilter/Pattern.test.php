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
        $filter->fetchResult ()->getFiltered ()
    );

    self::assertEquals (
        $redundant,
        $filter->fetchResult ()->getRedundantKeys ()
    );
  }

  public function testCommand () {

    // Actual options
    $query = Array ( '-o' => './a.out', '-f' => null, '-v' => null );

    /** [PatternUsage] */
    // Allowed options
    $pattern = new ArrayKeyWhitelistPattern (
        Array ( '-v', '-h', '-o' )
    );

    $filter = new MapFilter ( $pattern, $query );

    $valid = $filter->fetchResult ()->getFiltered ();
    $redundant = $filter->fetchResult ()->getRedundantKeys ();

    /** [PatternUsage] */

    $this->assertEquals ( Array ( '-o' => './a.out', '-v' => null ), $valid );
    $this->assertEquals ( Array ( '-f' ), $redundant );
  }

  /**
   * @expectedException MapFilter_InvalidStructureException
   */
  public function testInvalidMap () {

    $pattern = new ArrayKeyWhitelistPattern ( Array () );
    $filter = new MapFilter ( $pattern, 42 );

    $filter->fetchResult ();
  }
}

/** [ArrayKeyWhitelistResult] */
class ArrayKeyWhitelistResult {

    private $_filtered;
    private $_redundantKeys;

    /*
     * Initialize whitelist
     *
     * @param     Array   $whitelist      Array of allowed keys.
     */
    public function __construct(Array $filtered, Array $redundantKeys)
    {
        $this->_filtered = $filtered;
        $this->_redundantKeys = $redundantKeys;
    }

    /*
     * Get filtering results
     *
     * @return  Array   Array containing keys and values from
     *                  query that DID match the whitelist pattern.
     */
    public function getFiltered()
    {
        return $this->_filtered;
    }

    /*
     * Get filtered out
     *
     * @return  Array   Array containing keys from querty that DID NOT match
     *                  the whitelist pattern.
     */
    public function getRedundantKeys()
    {
        return $this->_redundantKeys;
    }
}
/** [ArrayKeyWhitelistResult] */

/** [ArrayKeyWhitelistPattern] */
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

        if ( !is_array($query) && !( $query instanceof ArrayAccess ) ) {

          throw new MapFilter_InvalidStructureException;
        }

        $filtered = $redundantKeys = Array();
        foreach ($query as $keyCandidate => $valueCandidate) {

            if(in_array($keyCandidate, $this->_whitelist)) {

                $filtered[ $keyCandidate ] = $valueCandidate;
            } else {

                $redundantKeys[] = $keyCandidate;
            }
        }

        return new ArrayKeyWhitelistResult($filtered, $redundantKeys);
    }
}
/** [ArrayKeyWhitelistPattern] */
