<?php
/**
* Class for exceptions raised by the MapFilter_Pattern_Tree.
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @since	0.4
*/

/**
* @file		3rdParty/PureException.php
*/
require_once ( dirname ( __FILE__ ) . '/../../../3rdParty/PureException.php' );

/**
* MapFilter_Pattern_Tree Exceptions
*
* @class	MapFilter_Pattern_Tree_Exception
* @package	MapFilter
* @since	0.4
*/
class MapFilter_Pattern_Tree_Exception extends PureException {

  /**
  * @copyfull{MapFilter_Pattern_Exception::INVALID_XML_ATTRIBUTE}
  */
  const INVALID_XML_ATTRIBUTE = 1;
  
  /**
  * @copyfull{MapFilter_Pattern_Exception::INVALID_XML_CONTENT}
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
