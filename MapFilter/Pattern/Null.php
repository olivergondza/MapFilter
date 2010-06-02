<?php
/**
 * MapFilter Null pattern
 *
 * @since	0.5
 * 
 * @author	Oliver Gondža
 * @link	http://github.com/olivergondza/MapFilter
 * @license	GNU GPLv3
 * @copyright	2009-2010 Oliver Gondža
 * @package	MapFilter
 * @subpackage	NullPattern
 */

/**
 * MapFilter Pattern
 *
 * @file		MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Interface.php' );

/**
 * @file		MapFilter/Pattern/AssertInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/AssertInterface.php' );

/**
 * @file		MapFilter/Pattern/FlagInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/FlagInterface.php' );

/**
 * @file		MapFilter/Pattern/ResultInterface.php
 */
require_once ( dirname ( __FILE__ ) . '/ResultInterface.php' );

/**
 * A mock implementation basic MapFilter_Pattern interfaces
 *
 * @since	0.5
 *
 * @class	MapFilter_Pattern_Null
 * @author	Oliver Gondža
 *
 * @ingroup	gnullpattern
 * @package	MapFilter
 * @subpackage	NullPattern
 */
class MapFilter_Pattern_Null implements
    MapFilter_Pattern_Interface,
    MapFilter_Pattern_AssertInterface,
    MapFilter_Pattern_FlagInterface,
    MapFilter_Pattern_ResultInterface
{

  /**
   * A parsed query to return by getResults()
   *
   * @since	0.5
   *
   * @var	Array|ArrayAccess	$query
   */
  private $query = Array ();

  /**
   * An empty constructor
   *
   * @since	0.5
   *
   * @return	MapFilter_Pattern_Null
   */
  public function __construct () {}

  /**
   * @copyfull{MapFilter_Pattern_ResultInterface::getResults()}
   */
  public function getResults () {
  
    return $this->query;
  }
  
  /**
   * @copyfull{MapFilter_Pattern_AssertInterface::getAsserts()}
   */
  public function getAsserts () {
    
    return Array ();
  }
  
  /**
   * @copyfull{MapFilter_Pattern_FlagInterface::getFlags()}
   */
  public function getFlags () {
  
    return Array ();
  }
  
  /**
   * @copyfull{MapFilter_Pattern_Interface::parse()}
   */
  public function parse ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
  
    $this->query = $query;
  }
}