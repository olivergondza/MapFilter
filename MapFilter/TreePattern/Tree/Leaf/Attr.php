<?php
/**
 * Attr Pattern leaf.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */

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
 * MapFilter pattern tree attribute leaf.
 *
 * @class       MapFilter_TreePattern_Tree_Leaf_Attr
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
final class MapFilter_TreePattern_Tree_Leaf_Attr extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * @copybrief         MapFilter_TreePattern_Tree_Interface::satisfy()
   *
   * Attr leaf is satisfied when its attribute occurs in user query and its
   * value matches the optional pattern defined by valuePattern attribute. 
   * When this does not happen this node still can be satisfied if its
   * default value is sat: attribute will have that default value and leaf
   * will be satisfied.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy()
   */
  public function satisfy ( &$query, Array &$asserts ) {
  
    $present = self::attrPresent (
        $this->attribute,
        $query
    );

    /**
     * If an attribute is not present and there is a default value defined,
     * the default value is going to be used as a value and possibly wrapped
     * into the array if the attribute is flagged as the iterator attribute.
     */
    if ( !$present ) {
    
      if ( $this->default === NULL ) {
      
        $this->setAssertValue ( $asserts );
        return $this->satisfied = FALSE;
      }
      
      $this->value = ( self::ARRAY_VALUE_YES === $this->iterator )
          ? Array ( $this->default )
          : $this->default;
          
      return $this->satisfied = TRUE;
    }
    
    /**
     * Satisfy actual attribute value
     */
    $valueCandidate = $query[ $this->attribute ];
    $valueCandidate = ( $valueCandidate instanceof Iterator )
        ? iterator_to_array ( $valueCandidate, FALSE )
        : $valueCandidate
    ;

    $currentArrayValue = is_array ( $valueCandidate );

    $this->assertTypeMismatch (
        $currentArrayValue,
        gettype ( $valueCandidate )
    );

    /** Dispatch single value */
    if ( !$currentArrayValue ) {
    
      $this->satisfied = $this->validateValue ( $valueCandidate );
      $this->value = $valueCandidate;

      if ( !$this->satisfied ) {
      
        $this->setAssertValue ( $asserts, $this->value );
      }

      return $this->satisfied;
    }

    /** Dispatch array value */
    $assertValue = NULL;
    foreach ( $valueCandidate as &$singleCandidate ) {
    
      $valid = $this->validateValue ( $singleCandidate );
      
      if ( $valid ) {

        $this->value[] = $singleCandidate;
      } else {
      
        $assertValue[] = $singleCandidate;
      }
    }

    $this->satisfied = (Bool) count ( $this->value );

    if ( (Bool) count ( $assertValue ) || !$this->satisfied ) {
    
      $this->setAssertValue ( $asserts, $assertValue );
    }

    return $this->satisfied;
  }
}
