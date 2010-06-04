<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern_Tree.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

/** @cond       INTERNAL */

/**
 * @file        3rdParty/PureException.php
 */
require_once ( dirname ( __FILE__ ) . '/../../../3rdParty/PureException.php' );

/** @endcond */

/**
 * MapFilter_TreePattern_Tree Exceptions
 *
 * @class       MapFilter_TreePattern_Tree_Exception
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
class MapFilter_TreePattern_Tree_Exception extends PureException {

  /**
   * @copyfull{MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE}
   */
  const INVALID_XML_ATTRIBUTE = 1;
  
  /**
   * @copyfull{MapFilter_TreePattern_Exception::INVALID_XML_CONTENT}
   */
  const INVALID_XML_CONTENT = 2;
  
  /**
   * Exception messages
   */
  protected $messages = Array (
      self::INVALID_XML_ATTRIBUTE => "Unknown attribute '%s'.",
      self::INVALID_XML_CONTENT => "Node has no content.",
  );
}
