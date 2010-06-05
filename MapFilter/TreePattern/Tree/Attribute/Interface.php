<?php
/**
 * Every tree node that has an attribute must implement this interface.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

/**
 * Interface for MapFilter pattern tree nodes that has an attribute.
 *
 * @class       MapFilter_TreePattern_Tree_Attribute_Interface
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
interface MapFilter_TreePattern_Tree_Attribute_Interface {

  /**
   * Convert node to string,
   *
   * @since     0.4
   *
   * @return    String          String representation of node
   */
  public function __toString ();
  
  /**
   * Get node attribute.
   *
   * @since     0.4
   *
   * @return    String          A node attribute
   * @see       setAttribute()
   */
  public function getAttribute ();
  
  /**
  * Possible iterator Values
  *
  * @since      0.5.2
  *
  * @var        String
  * @{
  */
  const ARRAY_VALUE_NO = 'no';
  const ARRAY_VALUE_YES = 'yes';
  const ARRAY_VALUE_AUTO = 'auto';
  /**@}*/
}