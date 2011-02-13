<?php
/**
 * Opt Pattern node.
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
 * @since    0.3
 */

/**
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once dirname ( __FILE__ ) . '/../Node.php';

/**
 * MapFilter pattern tree opt node.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Node_Opt
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.3
 */
final class MapFilter_TreePattern_Tree_Node_Opt extends
    MapFilter_TreePattern_Tree_Node
{

  /**
   * Satisfy certain node type and let its followers to get satisfied.
   *
   * @since     0.4
   *
   * @param     Array|ArrayAccess       &$query         A query to filter.
   * @param     Array                   &$asserts       Asserts.
   *
   * @return    Bool                    Satisfied or not.
   *
   * That node is always satisfied.  Thus satisfy MUST be mapped on ALL
   * followers.
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    foreach ( $this->getContent () as $follower ) {
    
      $follower->satisfy ( $query, $asserts );
    }

    return $this->satisfied = TRUE;
  }
}
