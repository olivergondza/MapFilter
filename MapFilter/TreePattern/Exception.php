<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern class.
 *
 * @author	Oliver Gondža
 * @link	http://github.com/olivergondza/MapFilter
 * @license	GNU GPLv3
 * @copyright	2009-2010 Oliver Gondža
 * @package	MapFilter
 * @subpackage	TreePattern
 * @since	0.4
 */

/** @cond	INTERNAL */

/**
 * @file		3rdParty/PureException.php
 */
require_once ( dirname ( __FILE__ ) . '/../../3rdParty/PureException.php' );

/** @endcond */

/**
 * MapFilter_TreePattern Exceptions
 *
 * @class	MapFilter_TreePattern_Exception
 * @ingroup	gtreepattern
 * @package	MapFilter
 * @subpackage	TreePattern
 * @since	0.4
 */
class MapFilter_TreePattern_Exception extends PureException {

  /**
   * Invalid pattern element occurred.
   *
   * @since	0.4
   *
   * An invalid pattern tag used.
   */
  const INVALID_PATTERN_ELEMENT = 1;
  
  /**
   * More than one pattern specified in the XML.
   *
   * @since	0.4
   *
   * A pattern tree must have exactly one follower whether an optional
   * \<pattern\> tag is used or not.
   */
  const TOO_MANY_PATTERNS = 2;
  
  /**
   * No such attribute with this type of node.
   *
   * @since	0.4
   * 
   * Invalid pattern attribute used.
   */
  const INVALID_PATTERN_ATTRIBUTE = 3;
  
  /**
   * LibXML internal error
   *
   * @since	0.5
   *
   * libXML cannot parse source
   * @{
   */
  const LIBXML_WARNING = 6;
  const LIBXML_ERROR = 7;
  const LIBXML_FATAL = 8;
  /**@}*/
  
  /**
   * Invalid xml attribute used.
   *
   * @since	0.5
   *
   * Invalid attribute passed to the tag.
   */
  const INVALID_XML_ATTRIBUTE = 9;
  
  /**
   * Invalid tag content.
   *
   * @since	0.5
   *
   * A pattern tree leaf has some content specified.
   */
  const INVALID_XML_CONTENT = 10;
  
  /**
   * Missing attribute value.
   *
   * @since	0.5
   *
   * An attribute tag has no attr value specified.
   */
  const MISSING_ATTRIBUTE_VALUE = 11;
  
  /**
   * Exception messages
   */
  protected $messages = Array (
      self::INVALID_PATTERN_ELEMENT => "Invalid pattern element '%s'.",
      self::TOO_MANY_PATTERNS => "More than one pattern specified.",
      self::INVALID_PATTERN_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::LIBXML_WARNING => "LibXML warning: %s on line %s (in file %s).",
      self::LIBXML_ERROR => "LibXML error: %s on line %s (in file %s).",
      self::LIBXML_FATAL => "LibXML fatal error: %s on line %s (in file %s).",
      self::INVALID_XML_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::INVALID_XML_CONTENT => "Node '%s' has no content.",
      self::MISSING_ATTRIBUTE_VALUE => "There is an Attr node without attribute value specified.",
  );
}
