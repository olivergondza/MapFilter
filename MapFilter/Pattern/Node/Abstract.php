<?php
/**
* Interface to Pattern node 
*/
abstract class MapFilter_Pattern_Node_Abstract {
  
  /**
  * Was node already satisfied
  * @var: Bool
  */
  public $satisfied = FALSE;
  
  /**
  * Key-Attr value filter
  * @var: String; REGEX
  */
  public $valueFilter = NULL;
  
  /**
  * Node flag
  * @var: String
  */
  public $flag = NULL;
  
  /**
  * All node types has to have empty constructors
  * All setting is done by Fluent Methods
  */
  final public function __construct () {}
  
  /**
  * Implicit Fluent Interface for all node type that do absolutely nothing;
  * That's implicit behaviour; Nodes that has certain value define own setter
  * with body;
  *
  * Values:
  *   attribute
  *   default
  *   valuePattern
  *   content
  *   valueFilter
  *   flag
  */
  public function setAttribute ( $attrbite ) { return $this; }
  public function setDefault ( $default ) { return $this; }
  public function setContent ( Array $content ) { return $this; }
  public function setValuePattern ( $pattern ) { return $this; }

  /**
  * Fluent Method; Set valueFilter
  * @valueFilter: String
  */
  public function setValueFilter ( $valueFilter ) {

    $this->valueFilter = $valueFilter;
    return $this;
  }
  
  /**
  * Fluent Msthod; Set Flag
  * @flag: String
  */
  public function setFlag ( $flag ) {
  
    $this->flag = $flag;
    return $this;
  }
  
  
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
  abstract public function hasAttr ();
  
  /**
  * Determine whether the node can have followers
  * @return: Bool
  */
  abstract public function hasFollowers ();
  
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
  * Test whether a ForValue condition on tree node fits given pattern
  * @valueFilter: PREG; Validation pattern
  * @valueCandidate: String
  * @return: Bool
  */
  protected function valueFits ( $valueCandidate, $pattern ) {

    if ( !$pattern ) {

      return TRUE;
    }

    /** Sanitize inputed PREG */
    $valueCandidate = preg_quote (
        $valueCandidate,
        self::FILTER_DELIMITER
    );
  
    $pattern = sprintf (
        self::FILTER_BOUNDARIES,
        $pattern
    );

    $matchCount = preg_match (
        $pattern,
        $valueCandidate
    );

    /** Assumed match count is 1 (Equals) or 0 (Differs) */
    assert ( $matchCount < 2 );
    
    return (Bool) $matchCount;
  }
}

