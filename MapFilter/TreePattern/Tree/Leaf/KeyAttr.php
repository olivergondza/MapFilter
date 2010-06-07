<?php
/**
 * KeyAttr Pattern node
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
 * MapFilter pattern tree KeyAttribute node
 *
 * @class       MapFilter_TreePattern_Tree_Leaf_KeyAttr
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 *
 * @todo        This class should be declared as final but it must be
 *              extended by MapFilter_TreePattern_Tree_Node_KeyAttr due to
 *              maintain backward compatibility.  After KeyAttr *node*
 *              removal class should remain final.
 */
class
    MapFilter_TreePattern_Tree_Leaf_KeyAttr
extends
    MapFilter_TreePattern_Tree_Leaf
implements
    MapFilter_TreePattern_Tree_Leaf_Interface
{

  /**
   * Fluent Method; Set content
   *
   * @since     0.5.2
   *
   * @param     Array           $content                A content to set
   *
   * @return    self
   */
  public function setContent ( Array $content ) {
   
    $this->content = $content;
    return $this;
  }

  /**
   * @copybrief         MapFilter_TreePattern_Tree_Interface::satisfy()
   *
   * Find a follower with a valueFilter that fits and try to satisfy it.
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
      
      $valueCandidate = $this->value =
          ( self::ARRAY_VALUE_YES === $this->iterator )
          ? Array ( $this->default )
          : $this->default
      ;

    } else {
    
      /**
       * Satisfy actual attribute value
       */
      $valueCandidate = $query[ $this->attribute ];
    }
    $valueCandidate = ( $valueCandidate instanceof Iterator )
        ? iterator_to_array ( $valueCandidate, FALSE )
        : $valueCandidate
    ;
    $currentArrayValue = is_array ( $valueCandidate );

    $this->assertTypeMismatch (
        $currentArrayValue,
        gettype ( $valueCandidate )
    );

    if ( $currentArrayValue ) {
     
      foreach ( $valueCandidate as &$singleCandidate ) {
          $this->validateValue ( $singleCandidate );
      }
    } else {
      
      $this->validateValue ( $valueCandidate );
    }

    /**
     * Find a follower that fits the only or all the value filters and let
     * it to be satisfied.
     */
    foreach ( $this->getContent () as $follower ) {
      
      if ( $currentArrayValue ) {
      
        $candidateCount = count ( $valueCandidate );
        $filters = array_pad (
            Array (),
            $candidateCount,
            $follower->getValueFilter ()
        );
      
        $candidatesFit = array_map (
            'self::valueFits',
            $valueCandidate,
            $filters
        );
        
        $fits = ( $candidateCount > 0 )
            ? !in_array ( FALSE, $candidatesFit )
            : FALSE
        ;
        
      } else {
      
        $fits = self::valueFits (
            $valueCandidate,
            $follower->getValueFilter ()
        );
      }
      
      if ( !$fits ) {
      
        continue;
      }

      $satisfied = $follower->satisfy ( $query, $asserts );
        
      if ( $satisfied ) {
          
        $this->value = $valueCandidate;
      } else {
      
        $this->setAssertValue ( $asserts );
      }
        
      return $this->satisfied = $satisfied;
    }

    $this->setAssertValue ( $asserts );
    return $this->satisfied = FALSE;
  }
}
