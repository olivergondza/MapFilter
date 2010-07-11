<?php
/**
 * Ancestor of pattern tree nodes.
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
 * @since    0.3
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * @file        MapFilter/TreePattern/Tree.php
 */
require_once ( dirname ( __FILE__ ) . '/../Tree.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Node/Interface.php' );

/**
 * Abstract class for pattern tree node.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Node
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.3
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
abstract class MapFilter_TreePattern_Tree_Node extends
    MapFilter_TreePattern_Tree
implements
    MapFilter_TreePattern_Tree_Node_Interface
{

  /**
   * Fluent Method; Set content.
   *
   * @since     0.3
   *
   * @param     Array           $content                A content to set.
   *
   * @return    self
   */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }
  
  /**
   * PickUp Nodes.
   *
   * @since     0.3
   *
   * @param     Array           $result
   */
  public function pickUp ( Array $result ) {

    if ( !$this->isSatisfied () ) return Array ();
  
    foreach ( $this->getContent () as $follower ) {

      $result = array_merge (
          $result,
          $follower->pickUp ( $result )
      );
    }
    
    return $result;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUpFlags}
   */
  public function pickUpFlags ( Array $flags ) {
  
    if ( !$this->isSatisfied () ) return $flags;
    
    if ( $this->flag !== NULL ) {
    
      if ( !in_array ( $this->flag, $flags ) ) {
       
        $flags[] = $this->flag;
      }
    }
    
    foreach ( $this->getContent () as $follower ) {

      $flags = $follower->pickUpFlags ( $flags );
    }
    
    return $flags;
  }
  
  /**
   * Clone node followers.
   *
   * @note This method uses deep cloning.
   *
   * @since     0.3
   */
  public function __clone () {
  
    foreach ( $this->content as &$follower ) {

      $follower = clone ( $follower );
    }
  }
}