<?php
/**
* MapFilter_Pattern_AssertInterface
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
* MapFilter_Pattern_AssertInterface
*
* @since	0.5
*
* @class	MapFilter_Pattern_AssertInterface
* @package	MapFilter
* @subpackage	Filter
* @author	Oliver Gondža
*/
interface MapFilter_Pattern_AssertInterface {

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
}