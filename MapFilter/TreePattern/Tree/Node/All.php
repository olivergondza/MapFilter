<?php
/**
 * All Pattern node.
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
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
 * MapFilter pattern tree all node.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Node_All
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.4
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
final class MapFilter_TreePattern_Tree_Node_All extends
    MapFilter_TreePattern_Tree_Node
{

  /**
   * @copybrief 	MapFilter_TreePattern_Tree_Interface::satisfy
   *
   * Satisfy the node just if there are no unsatisfied follower.  Finding
   * unsatisfied follower may stop mapping since there is no way to satisfy
   * parent by any further potentially satisfied follower.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    foreach ( $this->getContent () as $follower ) {
      
      if ( !$follower->satisfy ( $query, $asserts ) ) {

        $this->setAssertValue ( $asserts );
        return $this->satisfied = FALSE;
      }
    }
    
    return $this->satisfied = TRUE;
  }
}
