<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern class.
 *
 * PHP Version 5.1.0
 *
 * This file is part of MapFilter package.
 *
 * MapFilter is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 *                
 * MapFilter is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 *                              
 * You should have received a copy of the GNU Lesser General Public License
 * along with MapFilter.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Pear
 * @package  MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * @file        MapFilter/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/../Exception.php' );

/**
 * MapFilter_TreePattern Exceptions.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Exception
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
class MapFilter_TreePattern_Exception extends MapFilter_Exception {

  /**
   * Invalid pattern element occurred.
   *
   * @since     0.4
   *
   * An invalid pattern tag used.
   */
  const INVALID_PATTERN_ELEMENT = 1;
  
  /**
   * Has not exactly one follower.
   *
   * @since     0.5.3
   *
   * A node that can have exactly one follower has more or less of them.
   */
  const HAS_NOT_ONE_FOLLOWER = 2;
  
  /**
   * No such attribute with this type of node.
   *
   * @since     0.4
   * 
   * Invalid pattern attribute used.
   */
  const INVALID_PATTERN_ATTRIBUTE = 3;
  
  /**
   * LibXML internal error.
   *
   * @since     0.5
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
   * @since     0.5
   *
   * Invalid attribute passed to the tag.
   */
  const INVALID_XML_ATTRIBUTE = 9;
  
  /**
   * Invalid tag content.
   *
   * @since     0.5
   *
   * A pattern tree leaf has some content specified.
   */
  const INVALID_XML_CONTENT = 10;
  
  /**
   * Missing attribute value.
   *
   * @since     0.5
   *
   * An attribute tag has no attr value specified.
   */
  const MISSING_ATTRIBUTE_VALUE = 11;

  /**
   * Invalid pattern name.
   *
   * @since     0.5.3
   *
   * Can attach pattern with given name.
   */
  const INVALID_PATTERN_NAME = 12;
  
  protected $messages = Array (
      self::INVALID_PATTERN_ELEMENT => "Invalid pattern element '%s'.",
      self::HAS_NOT_ONE_FOLLOWER => "A %s node must have exactly one follower but %d given.",
      self::INVALID_PATTERN_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::LIBXML_WARNING => "LibXML warning: %s on line %s (in file %s).",
      self::LIBXML_ERROR => "LibXML error: %s on line %s (in file %s).",
      self::LIBXML_FATAL => "LibXML fatal error: %s on line %s (in file %s).",
      self::INVALID_XML_ATTRIBUTE => "Node '%s' has no attribute like '%s'.",
      self::INVALID_XML_CONTENT => "Node '%s' has no content.",
      self::MISSING_ATTRIBUTE_VALUE => "There is an Attr node without attribute value specified.",
      self::INVALID_PATTERN_NAME => "Pattern '%s' can not be attached.",
  );
}
