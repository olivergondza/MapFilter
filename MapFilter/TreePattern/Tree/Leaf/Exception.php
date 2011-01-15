<?php
/**
 * Class for exceptions raised by the MapFilter_TreePattern_Tree_Leaf.
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
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 
 */

/**
 * @file        MapFilter/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/../Exception.php' );

/**
 * MapFilter_TreePattern_Tree_Leaf Exceptions.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf_Exception
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.2
 */
class MapFilter_TreePattern_Tree_Leaf_Exception extends
    MapFilter_TreePattern_Tree_Exception
{

  /**
   * A value that was declared to be a scalar value was filled with an array.
   *
   * @since     0.5.2
   */
  const ARRAY_ATTR_VALUE = 2;

  /**
   * Unsupported value used as an iterator depth indicator.
   * 
   * @since     0.5.4
   */
  const INVALID_DEPTH_INDICATOR = 3;
  
  protected $messages = Array (
      self::ARRAY_ATTR_VALUE =>
          "A value of '%s' attribute is declared to be a scalar value but array given.",
      self::INVALID_DEPTH_INDICATOR =>
          "Unsupported value '%s' for iterator attribute."
  );
}
