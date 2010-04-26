<?php
/**
* Class for exceptions raised by the MapFilter package.
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include Exception class
*/
require_once ( dirname ( __FILE__ ) . '/../PureException.php' );

/**
* @package MapFilter
*/
class MapFilter_Pattern_Exception extends PureException {

  /** Exception code constants */

  /** Invalid pattern element occurred */
  const INVALID_PATTERN_ELEMENT = 1;
  
  /** More than one pattern specification in the XML */
  const TOO_MANY_PATTERNS = 2;
  
  /** No such attribute with this type of node */
  const INVALID_PATTERN_ATTRIBUTE = 3;
  
  /** libXML cannot parse source */
  const LIBXML_WARNING = 6;
  const LIBXML_ERROR = 7;
  const LIBXML_FATAL = 8;
  
  /** Valid attributes passed to invalid tags */
  const INVALID_XML_ATTRIBUTE = 9;
  
  /** Content in tags that has none */
  const INVALID_XML_CONTENT = 10;
  
  protected $messages = Array (
      self::INVALID_PATTERN_ELEMENT => "Invalid pattern element '%s'.",
      self::TOO_MANY_PATTERNS => "More than one pattern specified.",
      self::INVALID_PATTERN_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::LIBXML_WARNING => "LibXML warning: %s on line %s (in file %s).",
      self::LIBXML_ERROR => "LibXML error: %s on line %s (in file %s).",
      self::LIBXML_FATAL => "LibXML fatal error: %s on line %s (in file %s).",
      self::INVALID_XML_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::INVALID_XML_CONTENT => "Node '%s' has no content.",
  );
}
