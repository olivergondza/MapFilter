<?php
/**
 * Interface that provides filtering flags access.
 *
 * @since       0.5
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     LGPL
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  Filter
 */

/**
 * Interface that provides filtering flags access.
 *
 * @since       0.5
 *
 * @class       MapFilter_Pattern_FlagInterface
 * @ingroup     gfilter
 * @package     MapFilter
 * @subpackage  Filter
 * @author      Oliver Gondža
 */
interface MapFilter_Pattern_FlagInterface {

  /**
   * Get flags
   *
   * Return flags that was sat during latest parsing process.
   *
   * @since     0.5
   *
   * @return    Array|ArrayAccess	Parsing flags
   */
  public function getFlags ();
}