<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern_Tree_Leaf.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.5.2
 */

/** @cond       INTERNAL */

/**
 * @file        3rdParty/PureException.php
 */
require_once ( dirname ( __FILE__ ) . '/../../../../3rdParty/PureException.php' );

/** @endcond */

/**
 * MapFilter_TreePattern_Tree_Leaf Exceptions
 *
 * @class       MapFilter_TreePattern_Tree_Leaf_Exception
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.5.2
 */
class MapFilter_TreePattern_Tree_Leaf_Exception extends PureException {

  /**
   * A value that was declared to be an array was filled with a scalar value.
   *
   * @since     0.5.2
   */
  const SCALAR_ATTR_VALUE = 1;
  
  /**
   * A value that was declared to be a scalar value was filled with an array.
   *
   * @since     0.5.2
   */
  const ARRAY_ATTR_VALUE = 2;
  
  /**
   * Exception messages
   */
  protected $messages = Array (
      self::SCALAR_ATTR_VALUE =>
          "A value of '%s' attribute is declared to be an array but '%s' given.",
      self::ARRAY_ATTR_VALUE =>
          "A value of '%s' attribute is declared to be a scalar value but array given.",
  );
}
