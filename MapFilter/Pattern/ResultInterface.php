<?php
/**
* Interface that provides filtering results access.
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
* Interface that provides filtering results access.
*
* @since	0.5
*
* @class	MapFilter_Pattern_ResultInterface
* @ingroup	gfilter
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
  * @return	Array|ArrayAccess	Parsing results
  */
  public function getResults ();
}