<?php
/**
* Abstract Pattern node; Ancestor of all pattern nodes
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Tree/Exception.php' );
require_once ( dirname ( __FILE__ ) . '/Tree_Interface.php' );

abstract class MapFilter_Pattern_Tree
    implements MapFilter_Pattern_Tree_Interface
{
  
  /**
  * Determine whether the node was already satisfied
  * @var Bool
  */
  protected $satisfied = FALSE;
  
  /**
  * Key-Attr value filter. REGEXP
  * @var String
  */
  public $valueFilter = NULL;
  
  /**
  * Node flag
  * @var String
  */
  public $flag = NULL;
  
  /**
  * Node assert
  * @var String
  */
  public $assert = NULL;
  
  /**
  * Implicit Fluent Interface for all node types that rises exception;
  * That's implicit behavior; Nodes that has certain value define own setter
  * with meaningful body;
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
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $attribute )
    );
  }
  
  public function setDefault ( $default ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $default )
    );
  }
  
  public function setContent ( Array $content ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_CONTENT
    );
  }
  
  public function setValuePattern ( $pattern ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $pattern )
    );
  }

  /**
  * Fluent Method; Set valueFilter
  * @param String
  */
  public function setValueFilter ( $valueFilter ) {

    $this->valueFilter = $valueFilter;
    return $this;
  }
  
  /**
  * Fluent Method; Set Flag
  * @param String
  */
  public function setFlag ( $flag ) {
  
    $this->flag = $flag;
    return $this;
  }
  
  /**
  * Fluent Method; Set Assert
  * @param String
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
  * Satisfy certain node and do all necessary work to get (un)satisfied
  * @param Bool
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  protected function setSatisfied (
      $cond,
      MapFilter_Pattern_SatisfyParam $param
  ) {
  
    $this->satisfied = (Bool) $cond;
  
    /** Unsatisfied */
    if ( !$this->isSatisfied () ) {
      
      if ( $this->assert !== NULL ) {
      
        $param->asserts[] = $this->assert;
      }

    /** Satisfied */
    } else {
    
      if ( $this->flag !== NULL ) {
      
        $param->flags[] = $this->flag;
      }
      
    }
  
    return $this->satisfied;
  }
  
  /**
  * Determine whether the node is satisfied
  * @return Bool
  */
  public function isSatisfied () {
  
    return $this->satisfied;
  }
  
  /**
  * Test whether an argument is present in query.
  * @param String
  * @param Array
  * @return Bool
  */
  public static function attrPresent ( $attrName, $query ) {
    
    return array_key_exists (
        $attrName,
        $query
    );
  }
  
  /**
  * Enclose string with beginning and end mark to ensure that the string are
  * completely equal. FORMAT
  * @var String
  */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /**
  * PREG string delimiter
  * @var String
  */
  const FILTER_DELIMITER = '/';
  
  /**
  * Test whether a ForValue condition on tree node fits given pattern
  * @param String
  * @param String
  * @return Bool
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
