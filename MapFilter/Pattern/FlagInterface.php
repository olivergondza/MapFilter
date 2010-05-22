<?php
/**
* MapFilter_Pattern_FlagInterface
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
* MapFilter_Pattern_FlagInterface
*
* @since	0.5
*
* @class	MapFilter_Pattern_FlagInterface
* @package	MapFilter
* @subpackage	Filter
* @author	Oliver Gondža
*/
interface MapFilter_Pattern_FlagInterface {

  /**
  * Get flags
  *
  * Return flags that was sat during latest parsing process.
  *
  * @since	0.5
  *
  * @return	Array|Iterator	Parsing flags
  */
  public function getFlags ();
}