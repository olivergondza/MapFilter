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
 * @file        MapFilter/TreePattern/Tree/Node.php
 */
require_once ( dirname ( __FILE__ ) . '/../Node.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Attribute/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/../Attribute/Interface.php' );

/**
 * MapFilter pattern tree SetAttribute node
 *
 * @class       MapFilter_TreePattern_Tree_Node_KeyAttr
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 */
final class MapFilter_TreePattern_Tree_Node_KeyAttr extends
    MapFilter_TreePattern_Tree_Node
implements
    MapFilter_TreePattern_Tree_Attribute_Interface
{

  /**
   * Node attribute
   *
   * @since     0.4
   *
   * @var       String          $attribute
   */
  public $attribute = "";
  
  /**
   * Attribute value
   *
   * @since     0.4
   *
   * @var       String          $value
   */
  public $value = NULL;
  
  /**
   * Determine whether a value is scalar or an array/iterator.
   *
   * Possible values are 'no', 'yes' and 'auto'.
   *
   * @since     0.5.2
   *
   * @var       String
   */
  private $iterator = 'no';

  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setIterator()}
   */
  public function setIterator ( $iterator ) {

    $this->iterator = $iterator;
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setAttribute()}
   */
  public function setAttribute ( $attribute ) {
  
    $this->attribute = $attribute;
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::getAttribute()}
   */
  public function getAttribute () {
  
    return $this->attribute;
  }

  /**
   * @copybrief	        MapFilter_TreePattern_Tree_Interface::satisfy()
   *
   * Find a follower with a valueFilter that fits and try to satisfy it.
   *
   * @copydetails       MapFilter_TreePattern_Tree_Interface::satisfy()
   */
  public function satisfy ( &$query, Array &$asserts ) {

    $present = self::attrPresent ( $this->attribute, $query );

    if ( !$present ) {

      $this->setAssertValue ( $asserts );
      return $this->satisfied = FALSE;
    }
    
    $valueCandidate = $query[ $this->attribute ];
    $valueCandidate = ( $valueCandidate instanceof Iterator )
        ? iterator_to_array ( $valueCandidate, FALSE )
        : $valueCandidate
    ;
    $currentArrayValue = is_array ( $valueCandidate );

    /**
     * If there is no match between a declared value type and the current
     * value type an exception is going to be risen.
     */
    if ( ( self::ARRAY_VALUE_NO === $this->iterator ) && $currentArrayValue ) {
    
      throw new MapFilter_TreePattern_Tree_Attribute_Exception (
          MapFilter_TreePattern_Tree_Attribute_Exception::ARRAY_ATTR_VALUE,
          Array ( $this->attribute )
      );
    }

    if ( 
        ( self::ARRAY_VALUE_YES === $this->iterator ) && !$currentArrayValue
    ) {

      throw new MapFilter_TreePattern_Tree_Attribute_Exception (
          MapFilter_TreePattern_Tree_Attribute_Exception::SCALAR_ATTR_VALUE,
          Array ( $this->attribute, gettype ( $valueCandidate ) )
      );
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
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::pickUp()}
   */
  public function pickUp ( Array $result ) {

    /**
     * Set assertion for nodes that hasn't been satisfied and stop the
     * recursion
     */
    if ( !$this->isSatisfied () ) {

      return Array ();
    }
  
    $result[ (String) $this ] = $this->value;

    foreach ( $this->getContent () as $follower ) {

      $result = array_merge (
          $result,
          $follower->pickUp ( $result )
      );
    }

    return $result;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Attribute_Interface::__toString()}
   */
  public function __toString () {
  
    return (String) $this->attribute;
  }
}
