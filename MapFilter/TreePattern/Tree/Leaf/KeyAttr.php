<?php
/**
 * KeyAttr Pattern node.
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
 * @since    0.4
 */

/**
 * @file        MapFilter/TreePattern/Tree/Attribute.php
 */
require_once ( dirname ( __FILE__ ) . '/../Attribute.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf.php
 */
require_once ( dirname ( __FILE__ ) . '/../Leaf.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/../Leaf/Interface.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/../Leaf/Exception.php' );

/**
 * MapFilter pattern tree KeyAttribute node.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf_KeyAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
final class
    MapFilter_TreePattern_Tree_Leaf_KeyAttr
extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * Fluent Method; Set content.
   *
   * @since     0.5.2
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
   * Find a fitting follower, let it satisfy and set value or assertion.
   *
   * @since     0.5.2
   *
   * @param     Mixed           &$query                 A query.
   * @param     Array           &$asserts               Assertions.
   * @param     Mixed           $valueCandidate         
   *
   * @return    Bool
   */
  private function _satisfyFittingFollower (
      &$query,
      Array &$asserts,
      $valueCandidate
  ) {
  
    $satisfied = FALSE;
    foreach ( $this->getContent () as $follower ) {
    
      $fits = MapFilter_TreePattern_Tree_Attribute::valueFits (
          $valueCandidate,
          $follower->getValueFilter ()
      );
      
      if ( !$fits ) continue;
      
      $satisfied |= (Bool) $followerSatisfied = $follower->satisfy (
          $query, $asserts
      );
    }
    
    if ( $satisfied ) {
        
      $this->value = $valueCandidate;
    } else {
    
      $this->setAssertValue ( $asserts );
    }
      
    return $this->satisfied = $satisfied;
  }

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
   * Find a follower with a valueFilter that fits and try to satisfy it.
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    $this->attribute->setQuery ( $query );
    
    if ( !$this->attribute->isPresent () ) {
    
      // Set existence assertion
      $this->setAssertValue ( $asserts );

      return $this->satisfied = FALSE;
    }
    
    if ( !$this->attribute->isValid () ) {

      // Set validation assertion
      $this->setAssertValue ( $asserts );

      return $this->satisfied = FALSE;
    }

    $value = $this->attribute->getValue ();

    $assertValue = (String) $this->attribute;
    if ( is_array ( $value ) ) {
      $satisfied = FALSE;
      foreach ( $value as $singleCandidate ) {

        $satisfied |= (Bool) $this->_satisfyFittingFollower (
            $query, $asserts, $singleCandidate
        );
      }
    } else {
     
      $satisfied = $this->_satisfyFittingFollower (
          $query, $asserts, $value
      );
    }
      
    if ( !$satisfied ) {

      $this->setAssertValue ( $asserts, $assertValue );
    }

    $this->value = $this->attribute->getValue ();
    return $this->satisfied = $satisfied;
  }
}