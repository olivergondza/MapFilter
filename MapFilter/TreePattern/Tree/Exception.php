<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern_Tree.
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
 * MapFilter_TreePattern_Tree Exceptions.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Exception
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
class MapFilter_TreePattern_Tree_Exception extends
    MapFilter_TreePattern_Exception
{

  /**
   * @copyfull{MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE}
   */
  const INVALID_XML_ATTRIBUTE = 1;
  
  /**
   * @copyfull{MapFilter_TreePattern_Exception::INVALID_XML_CONTENT}
   */
  const INVALID_XML_CONTENT = 2;
  
  protected $messages = Array (
      self::INVALID_XML_ATTRIBUTE => "Unknown attribute '%s'.",
      self::INVALID_XML_CONTENT => "Node has no content.",
  );
}
