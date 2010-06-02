<?php
/**
 * MapFilter_Pattern Interface
 *
 * @since	0.5
 *
 * @author	Oliver Gondža
 * @link	http://github.com/olivergondza/MapFilter
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
 * @ingroup	gfilter
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
   * Parse the given query against the pattern.
   *
   * @since	0.5
   *
   * @param 	Array 		$query		A user query
   */
  public function parse ( $query );
}