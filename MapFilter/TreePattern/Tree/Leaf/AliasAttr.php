<?php
/**
 * Attr Pattern Alias attribute.
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
 * @since    $NEXT$
 */

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Attr.php
 */
require_once dirname ( __FILE__ ) . '/Attr.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf.php
 */
require_once dirname ( __FILE__ ) . '/../Leaf.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Interface.php
 */
require_once dirname ( __FILE__ ) . '/../Leaf/Interface.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/AliasAttr/DisallowedFollowerException.php
 */
require_once dirname ( __FILE__ ) . '/AliasAttr/DisallowedFollowerException.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf_AliasAttr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    $NEXT$
 */
final class MapFilter_TreePattern_Tree_Leaf_AliasAttr extends
    MapFilter_TreePattern_Tree_Leaf_Attr
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * Instantiate alias attribute
   *
   * @since     $NEXT$
   */
  public function __construct () {
  
    $this->setSetters ( Array (
        'content' => 'setContent',
    ) );

    parent::__construct ();
  }

  /**
   * Fluent Method; Set content.
   *
   * @since     $NEXT$
   *
   * @param     Array           $content                A content to set.
   *
   * @return    MapFilter_TreePattern_Tree_Node
   */
  public function setContent ( Array $content ) {
   
    foreach ( $content as $follower ) {
    
      $class = get_class ( $follower );
    
      if ( $class === 'MapFilter_TreePattern_Tree_Leaf_Attr' ) continue;
      
      $ex = new MapFilter_TreePattern_Tree_Leaf_AliasAttr_DisallowedFollowerException ();
      throw $ex->setFollower ( $class );
    }
   
    $this->content = $content;
    return $this;
  }

  /**
   * Satisfy certain node type and let its followers to get satisfied.
   *
   * @since     $NEXT$
   *
   * @param     Array|ArrayAccess       &$query         A query to filter.
   * @param     Array                   &$asserts       Asserts.
   *
   * @return    Bool                    Satisfied or not.
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    $satisfied = parent::satisfy ( $query, $asserts );
    
    if ( !$satisfied ) return FALSE;
    
    $value = $this->removeAttr ( $query );
    
    $this->satisfyFollowers ( $query, $asserts, $value );

    return $satisfied;
  }
  
  /**
   * Satisfy aliases using original attribute value
   *
   * @since     $NEXT$
   *
   * @param     Array|ArrayAccess       &$query         A query to filter.
   * @param     Array                   &$asserts       Asserts.
   * @param     Mixed                   $value          A value of alias attribute.
   */
  private function satisfyFollowers ( &$query, &$asserts, $value ) {
  
    foreach ( $this->content as $follower ) {
    
      $query[ $follower->getAttribute () ] = $value;
      $follower->satisfy ( $query, $asserts );
    }
  }
  
  /**
   * Remove attribute and fetch its value.
   *
   * @since     $NEXT$
   *
   * @param     Array|ArrayAccess       &$query         A query to filter.
   *
   * @return    Mixed                   Attribute that was removed.
   */
  private function removeAttr ( &$query ) {

    $attr = $this->attribute->getAttribute ();

    if ( array_key_exists ( $attr, $query ) ) {

      $value = $query[ $attr ];
      unset ( $query[ $attr ] );
    }

    return $value;
  }
  
  /**
   * Pick-up satisfaction results.
   *
   * @since     $NEXT$
   *
   * @param     Array           $result
   * @return    Array
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
}
