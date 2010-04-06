<?php
/**
* Abstract Pattern node; Ancestor of all pattern nodes
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
abstract class MapFilter_Pattern_Node_Abstract {
  
  /**
  * Was node already satisfied
  * @var: Bool
  */
//  public $satisfied = FALSE;
  protected $satisfied = FALSE;
  
  /**
  * Key-Attr value filter
  * @var: String; REGEXP
  */
  public $valueFilter = NULL;
  
  /**
  * Node flag
  * @var: String
  */
  public $flag = NULL;
  
  /**
  * Node assert
  * @var: String
  */
  public $assert = NULL;
  
  /**
  * Implicit Fluent Interface for all node types that rises exception;
  * That's implicit behavior; Nodes that has certain value define own setter
  * with body;
  *
  * Values:
  *   attribute
  *   default
  *   valuePattern
  *   content
  *   valueFilter
  *   flag
  *   assert
  */
  public function setAttribute ( $attribute ) {
  
    throw new MapFilter_Exception (
        MapFilter_Exception::INVALID_XML_ATTRIBUTE,
        Array ( "", $attribute )
    );
  }
  
  public function setDefault ( $default ) {
  
    throw new MapFilter_Exception (
        MapFilter_Exception::INVALID_XML_ATTRIBUTE,
        Array ( "", $default )
    );
  }
  
  public function setContent ( Array $content ) {
  
    throw new MapFilter_Exception (
        MapFilter_Exception::INVALID_XML_CONTENT,
        Array ( "" )
    );
  }
  
  public function setValuePattern ( $pattern ) {
  
    throw new MapFilter_Exception (
        MapFilter_Exception::INVALID_XML_ATTRIBUTE,
        Array ( "", $pattern )
    );
  }

  /**
  * Fluent Method; Set valueFilter
  * @valueFilter: String
  */
  public function setValueFilter ( $valueFilter ) {

    $this->valueFilter = $valueFilter;
    return $this;
  }
  
  /**
  * Fluent Method; Set Flag
  * @flag: String
  */
  public function setFlag ( $flag ) {
  
    $this->flag = $flag;
    return $this;
  }
  
  /**
  * Fluent Method; Set Assert
  * @assert: String
  */
  public function setAssert ( $assert ) {
  
    $this->assert = $assert;
    return $this;
  }
  
  /**
  * All node types has to have empty constructors
  * All setting is done by Fluent Methods
  */
  final public function __construct () {}
  
  /**
  * Satisfy certain node type and let it's followers to get satisfied
  * Some node types needs to query access so it has to be distributed all over
  * the tree.
  * @&query: Array
  * @&assert: Array ( String )
  * @return: Bool
  */
  abstract public function satisfy ( Array &$query, Array &$asserts );
  
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
  
  /**
  * Satisfy certain node and do all necessary work to get (un)satisfied
  * @cond: Bool
  * @&asserts: Array
  * @return: Bool
  */
  protected function setSatisfied ( $cond, Array &$asserts ) {
  
    $this->satisfied = (Bool) $cond;
  
    /** Unsatisfied */
    if ( !$this->isSatisfied () ) {
      
      if ( $this->assert !== NULL ) {
      
        $asserts[] = $this->assert;
      }

    /** Satisfied */
    } else {
    
    }
  
    return $this->satisfied;
  }
  
  /**
  * Determine whether the node is satisfied
  * @return: Bool
  */
  public function isSatisfied () {
  
    return $this->satisfied;
  }
  
  /** All nodes must clone */
  public function __clone () {
  
    $content = $this->getContent ();
    foreach ( $content as &$follower ) {
    
      $follower = clone ( $follower );
    }
    
    return;
  }
  
  /**
  * Export object
  * @return: String
  */
  public function __toString () {
  
    return var_export ( $this , TRUE );
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
  * Enclose string with beginning and end mark to ensure that the string are
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

    /** Sanitize inputted PREG */
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
