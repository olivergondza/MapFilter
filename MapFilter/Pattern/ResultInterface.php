<?php
/**
* MapFilter_Pattern_ResultInterface
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
* MapFilter_Pattern_ResultInterface
*
* @since	0.5
*
* @class	MapFilter_Pattern_ResultInterface
* @package	MapFilter
* @subpackage	Filter
* @author	Oliver Gondža
*/
interface MapFilter_Pattern_ResultInterface {

  /**
  * Get results.
  *
  * Get parsed query from latest parsing process.
  *
  * @since	0.5
  *
  * @return	Array|Iterator	Parsing results
  */
  public function getResults ();
}