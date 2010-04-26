<?php
/**
* Class for exceptions raised by the MapFilter_Pattern_Tree.
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include ecxception class
*/
require_once ( dirname ( __FILE__ ) . '/../../PureException.php' );

/**
* @package MapFilter
*/
class MapFilter_Pattern_Tree_Exception extends PureException {

  /** Exception code constants */

  /** Valid attributes passed to invalid tags */
  const INVALID_XML_ATTRIBUTE = 1;
  
  /** Content in tags that has none */
  const INVALID_XML_CONTENT = 2;
  
  protected $messages = Array (
      self::INVALID_XML_ATTRIBUTE => "Unknown attribute '%s'.",
      self::INVALID_XML_CONTENT => "Node has no content.",
  );
}
