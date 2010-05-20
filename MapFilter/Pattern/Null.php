<?php
/**
* MapFilter Null pattern
*
* @since	0.5
* 
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
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
* A mock implementation MapFilter_Pattern_Interface
*
* @since	0.5
*
* @class	MapFilter_Pattern_Null
* @author	Oliver Gondža
* @package	MapFilter
* @subpackage	NullPattern
*/
class MapFilter_Pattern_Null implements
    MapFilter_Pattern_Interface
{

  /**
  * Parsed query to return by getResilts()
  *
  * @since	0.5
  *
  * @var	Array	$query
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
  * @copyfull{MapFilter_Pattern_Interface::getResults()}
  */
  public function getResults () {
  
    return $this->query;
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::getAsserts()}
  */
  public function getAsserts () {
    
    return Array ();
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::getFlags()}
  */
  public function getFlags () {
  
    return Array ();
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::parse()}
  */
  public function parse ( Array $query ) {
  
    $this->query = $query;
  }
}