<?php
/**
 * Interface that provides filtering assertions access.
 *
 * @since       0.5
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  Filter
 */

/**
 * Interface that provides filtering assertions access.
 *
 * @since       0.5
 *
 * @class       MapFilter_Pattern_AssertInterface
 *
 * @ingroup     gfilter
 * @package     MapFilter
 * @subpackage  Filter
 * @author      Oliver Gondža
 */
interface MapFilter_Pattern_AssertInterface {

  /**
   * Get validation assertions.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @since     0.5
   *
   * @return    Array|ArrayAccess       Parsing asserts
   */
  public function getAsserts ();
}