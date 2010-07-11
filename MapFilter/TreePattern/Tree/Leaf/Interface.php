<?php
/**
 * Interface for MapFilter pattern tree leaves.
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
 * Interface for MapFilter pattern tree leaves.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf_Interface
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
interface MapFilter_TreePattern_Tree_Leaf_Interface {

  /**
   * Convert node to string.
   *
   * @since     0.4
   *
   * @return    String          String representation of node.
   */
  public function __toString ();
  
  /**
   * Get node attribute.
   *
   * @since     0.4
   *
   * @return    String          A node attribute.
   * @see       setAttribute()
   */
  public function getAttribute ();
}