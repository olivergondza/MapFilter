<?php
/**
 * NodeAttr Pattern node.
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
 * @since    0.5.3
 *
 * @link     http://github.com/olivergondza/MapFilter
 */

/**
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
 * MapFilter pattern tree NodeAttr node.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Node_NodeAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * 
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @since    0.5.3
 *
 * @link     http://github.com/olivergondza/MapFilter
 */
final class MapFilter_TreePattern_Tree_Node_NodeAttr extends
    MapFilter_TreePattern_Tree_Node
{

  /**
   * Attribute name
   *
   * @since     0.5.3
   *
   * @var       String          $_attribute
   */
  private $_attribute = NULL;

  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * Possible values are 'no', 'yes' and 'auto'.
   *
   * @since     0.5.3
   *
   * @var       String          $_iterator
   */
  private $_iterator = 'no';
  
  /**
   * Copy of an original follower.
   *
   * Kept to be cloned for iterator satisfaction purposes.
   *
   * @since     0.5.3
   *
   * @var       MapFilter_TreePattern_Tree_Interface    $_follower
   */
  private $_follower = NULL;

  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setAttribute()}
   */
  public function setAttribute ( $attribute ) {
  
    assert ( is_string ( $attribute ) );
    
    $this->_attribute = $attribute;
    
    return $this;
  }

  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setIterator()}
   */
  public function setIterator ( $iterator ) {
  
    assert ( is_string ( $iterator ) );
    
    $this->_iterator = $iterator;
    
    return $this;
  }

  /**
   * Fluent Method; Set content.
   *
   * @since     0.5.3
   *
   * @param     Array           $content                A content to set.
   *
   * @return    MapFilter_TreePattern_Tree_Node_NodeAttr
   */
  public function setContent ( Array $content ) {

    self::_assertNonSingleFollower ( $content );
    $this->content = $content;
    $this->_follower = array_shift ( $content );

    return $this;
  }

  /**
   * Examine content and throw an exception if needed.
   *
   * @since     0.5.3
   *
   * @param     Array           $content                A content to examine.
   *
   * @return    Array
   *
   * @throws    MapFilter_TreePattern_Exception::HAS_NOT_ONE_EXCEPTION
   */
  private function _assertNonSingleFollower ( Array $content ) {

    $contentCount = count ( $content );

    if ( $contentCount != 1 ) {

      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
          Array ( 'NodeAttr', $contentCount )
      );
    }

    return array_shift ( $content );
  }

  /**
   * @copybrief 	MapFilter_TreePattern_Tree_Interface::satisfy
   *
   * @since             0.5.3
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy
   */
  public function satisfy ( &$query, Array &$asserts ) {

    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    $follower = self::_assertNonSingleFollower ( $this->getContent () );
    
    if ( !array_key_exists ( $this->_attribute, $query ) ) {
        $this->setAssertValue ( $asserts );
        return $this->satisfied = FALSE;
    }

    $valueCandidate = self::convertIterator ( $query[ $this->_attribute ] );

    if ( !is_array ( $valueCandidate ) || $valueCandidate === Array () ) {
    
      $this->setAssertValue ( $asserts );
      return $this->satisfied = FALSE;
    }

    $isIterator = $this->_iterator === self::ITERATOR_VALUE_YES;
    if ( $isIterator ) {
    
      $this->content = Array ();
    
      foreach ( $valueCandidate as $singleCandidate ) {

        /** Scalar can not by satisfied. array expected */
        if (
            !is_array ( $singleCandidate )
            && !( $singleCandidate instanceof Iterator )
        ) {
        
          continue;
        }
      
        $follower = clone ( $this->_follower );

        $satisfied = $follower->satisfy (
            $singleCandidate,
            $asserts
        );

        if ( $satisfied ) {

          $this->content[] = $follower;
          $this->satisfied = TRUE;
        }
      }
    } else {

      $this->satisfied = $follower->satisfy (
          $valueCandidate,
          $asserts
      );
    } 

    if ( !$this->satisfied ) {

      $this->setAssertValue ( $asserts );
    }

    return $this->satisfied;
  }
  
  /**
   * @copybrief         MapFilter_TreePattern_Tree_Interface::pickUp
   *
   * @since             0.5.3
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::pickUp
   */
  public function pickUp ( Array $result ) {

    if ( !$this->isSatisfied () ) return Array ();
  
    $isIterator = ($this->_iterator === self::ITERATOR_VALUE_YES);
    $c = $this->getContent ();

    foreach ( $this->getContent () as $follower ) {

      $followerResult = $follower->pickUp ( Array () );
      
      if ( $isIterator ) {

        if ( $followerResult === Array () ) continue;
        
        $result[ $this->_attribute ][] = $followerResult;
      } else {

        $result[ $this->_attribute ] = $followerResult;
      }
    }
    
    return $result;
  }
}