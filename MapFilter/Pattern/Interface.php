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
*
* @see		MapFilter_Pattern_AssertInterface
* @see		MapFilter_Pattern_FlagInterface
* @see		MapFilter_Pattern_ResultInterface
*/
interface MapFilter_Pattern_Interface {

  /**
  * Parse pattern against given query.
  *
  * @since	0.5
  *
  * @param	Array|Iterator	$query		A query to parse
  */
  public function parse ( $query );
}