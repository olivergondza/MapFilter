<?php
/**
* Data type to hold Pattern tree
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
class MapFilter_Pattern {

  /**
  * Determine whether the node or list was satisfied by satisfying its
  * followers
  * @var: Bool
  */
  public $satisfied = FALSE;
  
  /**
  * Attribute
  * @var: String
  */
  public $attribute = "";
  
  /**
  * Determine type of the node
  * @var: self::NODETYPE_*
  */
  public $nodeType = self::NODETYPE_NONE;
  
  /**
  * Value filter
  * @var: String
  */
  public $valueFilter = self::VALUE_FILTER;
  
  /**
  * Array of tree followers
  * @var: Array of Pattern
  */
  public $content = Array ();

  /**
  * Default Value Filter; Allows everything
  */
  const VALUE_FILTER = ".*";

  /**
  * Possible Node types.
  * NONE: Error state; Shouldn't be used
  * ALL, ONE, OPT, KEYATTR, VALUE: Represents given types of nodes
  */
  const NODETYPE_NONE = 0;
  const NODETYPE_ALL = 1;
  const NODETYPE_ONE = 2;
  const NODETYPE_OPT = 3;
  const NODETYPE_SOME = 4;
  const NODETYPE_KEYATTR = 5;
  const NODETYPE_ATTR = 6;

  const NODETYPE_END = 7;

  /**
  * Determine whether a nodetype is valid
  * @nodeType: self::NODETYPE
  * @return: Bool
  */
  public static function validNodeType ( $nodeType ) {
    
    if (
        is_int ( $nodeType ) &&
        $nodeType > self::NODETYPE_NONE &&
        $nodeType < self::NODETYPE_END
    ) {

      return TRUE;
    }
    
    return FALSE;
  }

  /**
  * Determines whether a node type requires an attribute
  * @nodeType: self::NODETYPE
  * @return: Bool
  */
  public static function hasAttribute ( $nodeType ) {
  
    static $needAttr = Array (
        self::NODETYPE_KEYATTR,
        self::NODETYPE_ATTR
    );
  
    if ( in_array ( $nodeType, $needAttr ) ) {

      return TRUE;
    }

    return FALSE;
  }
  
  /**
  * Create node or list in pattern tree.
  * @nodeType: self::NODETYPE_*; Determines type of used node
  * @followers: Array of Pattern; Following objects; Array () for Value node
  * @attribute: String; Name of attribute for Value and KeyAttr nodes
  * @valueFilter: String; Value filter for key nodes
  * @throws: MapFilter_Exception
  */
  public function __construct (
      $nodeType = self::NODETYPE_NONE,
      Array $followers = Array (),
      $attribute = NULL,
      $valueFilter = self::VALUE_FILTER
  ) {

    /** Set validated nodeType */
    if ( ! self::validNodeType ( $nodeType ) ) {
    
      throw new MapFilter_Exception (
          MapFilter_Exception::INVALID_NODE_TYPE,
          Array ( (String) $nodeType, $attribute, $valueFilter )
      );
    }

    $this->nodeType = $nodeType;
    
    /** Set Attributes for nodes that need so */
    if ( self::hasAttribute ( $nodeType ) ) {

      if ( ! is_string ( (String) $attribute ) ) {
        
        throw new MapFilter_Exception (
            MapFilter_Exception::INVALID_ATTR,
            Array ( var_dump ( $attribute ) )
        );
      }
    
      $this->attribute = (String) $attribute;
    }

    /** Set validated valueFilter */
    if ( ! is_string ( (String) $valueFilter ) ) {

      throw new MapFilter_Exception (
          MapFilter_Exception::INVALID_ATTR,
          Array ( var_dump ( $valueFilter ) )
      );
    }

    $this->valueFilter = (String) $valueFilter;

    $this->content = $followers;
  
    return;
  }

  /**
  * Simple Factory Method to create Value Node easier
  * Equivalent: new MapFilter_Pattern ( Pattern::NODETYPE_ATTR, Array (), $attribute )
  *
  * @attribute: String; Name of an attribute
  * @valueFilter: String; Optional valueFilter for new node
  * @return: Pattern; MapFilter_Pattern with Value node type
  */
  public static function getValueNode (
      $attribute,
      $valueFilter = self::VALUE_FILTER
  ) {
  
    return new MapFilter_Pattern (
        self::NODETYPE_ATTR,
        Array (),
        $attribute,
        $valueFilter
    );
  }
  
  /** Clone all tree recursively */
  public function __clone () {

    foreach ( $this->content as &$follower ) {
    
      $follower = clone ( $follower );
    }

    return;
  }
  
  /**
  * Enable cast to string to the Value and KeyAttr nodes
  * @return: String
  * @throws: MapFilter_Exception
  */
  public function __toString () {

    if ( self::hasAttribute ( $this->nodeType ) ) {

      return $this->attribute;
    }
    
    /** Different types shouldn't be converted to String */
    /** Cannot throw an exception from __toString */
    assert ( FALSE );

/**    throw new MapFilter_Exception (
        MapFilter_Exception::TYPE_CAST_FAULT,
        Array ( $this->nodeType )
    );
*/

  }
}