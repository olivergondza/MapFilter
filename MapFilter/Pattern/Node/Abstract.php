<?php
/**
* Interface to Pattern node 
*/
abstract class MapFilter_Pattern_Node_Abstract {
  
  /**
  * 
  * @var: String
  */
  public $valueFilter = NULL;
  
  /**
  * Node attribute
  * @var: String
  */
  public $attribute = "";
  
  /**
  * Was node already satisfied
  * @var: Bool
  */
  public $satisfied = FALSE;
  
  /**
  * Attr default value
  * @var: String
  */
  public $default = NULL;
  
  /**
  * Node Followers
  * @var: Array ( MapFilter_Pattern_Node_Interface )
  */
  public $content = NULL;

  /** All classes has to have it's own constructors with different synopsis */

  /**
  * Satisfy certain node type and let it's followers to get satisfied
  * Some nodetypes needs to query access so it has to be distributed all over
  * the tree.
  * @query: Array
  * @return: Bool
  */
  abstract public function satisfy ( Array &$query );
  
  /**
  * Determine whether a node has an attribute
  * @return: Bool
  */
  abstract public static function hasAttr ();
  
  /**
  * Determine whether the node can have followers
  * @return: Bool
  */
  abstract public static function hasFollowers ();
  
  /** All nodes must clone */
  public function __clone () {
  
    foreach ( $this->content as &$follower ) {
    
      $follower = clone ( $follower );
    }
    
    return;
  }
  
  /**
  * Test whether an argument is present in query.
  * @attrName: String; Name of an attribute
  * @returns: Bool
  */
  public static function attrPresent ( $attrName, &$query ) {
    
    return array_key_exists (
        $attrName,
        $query
    );
  }
  
  /**
  * Enclose string with begining and end mark to ensure that the string are
  * completely equal.
  */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /** PREG string delimiter */
  const FILTER_DELIMITER = '/';
  
  /**
  * Test whether a onValue condition on tree node fits given pattern
  * @valueFilter: PREG; Validation pattern
  * @valueCandidate: String
  * @return: Bool
  */
  protected function valueFits ( $valueCandidate ) {

    $valueFilter = $this->valueFilter;

    if ( !$valueFilter ) {

      return TRUE;
    }

    /** Sanitize inputed PREG */
    $valueCandidate = preg_quote (
        $valueCandidate,
        self::FILTER_DELIMITER
    );
  
    $valueFilter = sprintf (
        self::FILTER_BOUNDARIES,
        $valueFilter
    );

    $matchCount = preg_match (
        $valueFilter,
        $valueCandidate
    );

    /** Assumed match count is 1 (Equals) or 0 (Differs) */
    assert ( $matchCount < 2 );
    
    return (Bool) $matchCount;
  }
}

