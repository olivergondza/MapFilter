<?php
/**
 * Attr Pattern leaf.
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
require_once dirname ( __FILE__ ) . '/../Attribute.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf.php
 */
require_once dirname ( __FILE__ ) . '/../Leaf.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Interface.php
 */
require_once dirname ( __FILE__ ) . '/../Leaf/Interface.php';

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Exception.php
 */
require_once dirname ( __FILE__ ) . '/../Leaf/Exception.php';

/**
 * MapFilter pattern tree attribute leaf.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Tree_Leaf_Attr
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.4
 */
class MapFilter_TreePattern_Tree_Leaf_Attr extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
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
   * Attr leaf is satisfied when its attribute occurs in user query and its
   * value matches the optional pattern defined by valuePattern attribute. 
   * When this does not happen this node still can be satisfied if its
   * default value is sat: attribute will have that default value and leaf
   * will be satisfied.
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );

    $this->attribute->setQuery ( $query );

    if ( !$this->attribute->isPresent () ) {
    
      $this->setAssertValue ( $asserts );

      return $this->satisfied = FALSE;
    }
    
    if ( !$this->attribute->isValid () ) {
    
      $this->setAssertValue (
          $asserts, $query[ (String) $this->attribute ]
      );
    
      return $this->satisfied = FALSE;
    }

    $value = $this->attribute->getValue ();

    $attrName = (String) $this->attribute;

    if ( 
        array_key_exists ( $attrName, $query )
        && is_array ( $value )
    ) {

      $oldValue = self::convertIterator ( $query[ $attrName ] );

      $oldValue = ( is_array ( $oldValue ) )
          ? $oldValue
          : Array ()
      ;

      $setAsserts = (Bool) $assertValue = array_values (
          array_diff ( $oldValue, $value )
      );

      if ( $setAsserts ) {

        $this->setAssertValue ( $asserts, $assertValue );
      }
    }

    return $this->satisfied = TRUE;
  }
}
