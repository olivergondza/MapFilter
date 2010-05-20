<?php
/**
* MapFilter_Pattern Interface
*
* @since	0.5
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	Filter
*/

/**
* MapFilter_Pattern Interface
*
* @since	0.5
*
* @class	MapFilter_Pattern_Interface
* @package	MapFilter
* @subpackage	Filter
* @author	Oliver Gondža
*/
interface MapFilter_Pattern_Interface {

  /**
  * Get results.
  *
  * Get parsed query from latest parsing process.
  *
  * @since	0.5
  *
  * @return	Array	Parsing results
  */
  public function getResults ();
  
  /**
  * Get validation assertions.
  *
  * Return validation asserts that was raised during latest parsing process.
  *
  * @since	0.5
  *
  * @return	Array	Parsing asserts
  */
  public function getAsserts ();
  
  /**
  * Get flags
  *
  * Return flags that was sat during latest parsing process.
  *
  * @since	0.5
  *
  * @return	Array	Parsing flags
  */
  public function getFlags ();
  
  /**
  * Parse pattern aginst given query.
  *
  * @since	0.5
  *
  * @param	$query		A query to parse
  *
  * @see	getResults(), getAsserts(), getFlags()
  */
  public function parse ( Array $query );
}