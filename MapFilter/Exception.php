<?php
/**
* Class for exceptions raised by the MapFilter package.
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

require_once ( dirname ( __FILE__ ) . "/PureException.php" );

class MapFilter_Exception extends PureException {

  /** Exception code constants */

  /** Unknown type of pattern */
  const UNKNOWN_PATTERN_SOURCE = 1;

  /** Invalid pattern element occurred */
  const INVALID_PATTERN_ELEMENT = 2;
  
  /** More than one pattern specification in the XML */
  const TOO_MANY_PATTERNS = 3;
  
  /** File cannot be read */
//  const NO_SUCH_FILE = 4;
  
  /** No such attribute with this type of node */
  const INVALID_PATTERN_ATTRIBUTE = 5;
  
  /** libXML cannot parse source */
  const LIBXML_WARNING = 6;
  const LIBXML_ERROR = 7;
  const LIBXML_FATAL = 8;
  
  /** Pattern has got an invalid node type */
  const INVALID_NODE_TYPE = 9;
  
  /** Attr cannot be cast to string */
  const INVALID_ATTR = 10;
  
  /** Cannot cast type */
  const TYPE_CAST_FAULT = 11;
  
  /**
  * Messages
  * @var: Array ( MapFilter_Exception::const => String )
  */
  protected $messages = Array (
      self::UNKNOWN_PATTERN_SOURCE =>
          "Unknown pattern source '%s'. Supported types are Pattern, XML String or String Filename.",
      self::INVALID_PATTERN_ELEMENT => "Invalid pattern element '%s'.",
      self::TOO_MANY_PATTERNS => "More than one pattern specified.",
//      self::NO_SUCH_FILE => "File '%s' does not exists or cannot be read.",
      self::INVALID_PATTERN_ATTRIBUTE => "Node '%s' has no attribute as '%s'.",
      self::LIBXML_WARNING => "LibXML warning: %s on line %s (in file %s).",
      self::LIBXML_ERROR => "LibXML error: %s on line %s (in file %s).",
      self::LIBXML_FATAL => "LibXML fatal error: %s on line %s (in file %s).",
      self::INVALID_NODE_TYPE =>
          "Invalid node type '%s' having attribute '%s' and valueFilter '%s'.",
      self::INVALID_ATTR =>
          "Invalid attribute value '%s' cannot be cast to string.",
      self::TYPE_CAST_FAULT =>
          "This node type cannot be cast to string '%d'."
  );
}
