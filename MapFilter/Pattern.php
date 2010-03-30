<?php
/**
* Data type to hold Pattern tree
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/All.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/Opt.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/One.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/Some.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/KeyAttr.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Node/Attr.php' );

require_once ( dirname ( __FILE__ ) . '/NotSoSimpleXMLElement.php' );

class MapFilter_Pattern {

  const VALUE_FILTER = ".*";

  /**
  * Pattern tree itself
  * @var: MapFilter_Pattern_Node_Abstract
  */
  public $patternTree = NULL;

  /**
  * Create Pattern
  * $patternTree: MapFilter_Pattern_Node_Abstract
  */
  public function __construct (
      MapFilter_Pattern_Node_Abstract $patternTree
  ) {
  
    $this->patternTree = $patternTree;
    return;
  }

  /**
  * Simple Factory Method to load data from string
  * @XMLSpecification: String
  * @return: MapFilter_Pattern
  * @throw: MapFilter_Exception
  */
  public static function load ( $XMLSource ) {
    
    $XMLElement = self::loadXML (
        $XMLSource,
        self::DATA_IS_STRING
    );
    
    return new MapFilter_Pattern (
        self::parse ( $XMLElement )
    );
  }
  
  /**
  * Simple factory method to instantiate with loading the data from file
  * @url: String; URL
  * @return: MapFilter_Pattern
  * @throws: MapFilter_Exception
  */
  public static function fromFile ( $url ) {
  
    $XMLElement = self::loadXML (
        $url,
        self::DATA_IS_URL
    );
    
    return new MapFilter_Pattern (
        self::parse ( $XMLElement )
    );
  }

  /*** XML struct tags */
  const PATTERN = "pattern";
  
  const NODE_ALL = "all";
  const NODE_ONE = "one";
  const NODE_OPT = "opt";
  const NODE_KEYATTR = "key_attr";
  const NODE_ATTR = "attr";
  const NODE_SOME = "some";
  
  const ATTR_ATTR = "attr";
  const ATTR_VALUEFILTER = "forValue";
  const ATTR_DEFAULT = "default";
// NOT IMPLEMENTED
//  const ATTR_FLAG = "flag";

  
  /**
  * Node name Object type mapping
  * @var: Array ( TagName => ObjType )
  */
  private static $tagToNode = Array (
      self::NODE_ALL => 'MapFilter_Pattern_Node_All',
      self::NODE_ONE => 'MapFilter_Pattern_Node_One',
      self::NODE_OPT => 'MapFilter_Pattern_Node_Opt',
      self::NODE_SOME => 'MapFilter_Pattern_Node_Some',
      self::NODE_ATTR => 'MapFilter_Pattern_Node_Attr',
      self::NODE_KEYATTR => 'MapFilter_Pattern_Node_KeyAttr',
  );
  
  /**
  * Determines whether a tag is valid
  * @tag: String
  * @return: Bool
  */
  private static function isValidTag ( $tag ) {
  
    return array_key_exists ( $tag, self::$tagToNode );
  }
  
  /** Disallow load from file */
  const DATA_IS_URL = TRUE;
  const DATA_IS_STRING = FALSE;
  
  /**
  * Load Xml source and create XMLElement
  * @xml: String;
  * @isUrl: Bool;
  * @return: NotSoSimpleXMLElement
  * @throw: MapFilter_Exception
  */
  private static function loadXML ( $xml, $isUrl ) {
  
    /** Suppress Error | Warning vomiting into the output stream */
    libxml_use_internal_errors ( TRUE );
    
    /**
    * Options used for XML deserialization by NotSoSimpleXMLElement
    * Use compact data allocation | remove blank nodes | translate HTML entities
    */
    $options = LIBXML_COMPACT & LIBXML_NOBLANKS & LIBXML_NOENT;
    
    /** Try to load and raise propper exception accordingly */
    try {
    
      $XMLElement = new NotSoSimpleXMLElement (
          $xml,
          $options,
          $isUrl
      );
    } catch ( Exception $exception ) {
    
      /** Assign special exception for all kinds of libxml errors */
      $errorToException = Array (
          LIBXML_ERR_WARNING => MapFilter_Exception::LIBXML_WARNING,
          LIBXML_ERR_ERROR => MapFilter_Exception::LIBXML_ERROR,
          LIBXML_ERR_FATAL => MapFilter_Exception::LIBXML_FATAL
      );

      /** Throw first error */
      $error = libxml_get_last_error ();
      libxml_clear_errors ();

      throw new MapFilter_Exception (
          $errorToException[ $error->level ],
          Array ( $error->message, $error->line, $error->file )
      );
    }

    /** Sanitize pattern tag */
    $XMLElement = self::unwrap ( $XMLElement );
    
    return $XMLElement;
  }
  
  /**
  * Obtain and remove valueFilter from an array of attributes or set default
  * @&Attributes: Array ()
  * @return: String; valueFilter
  */
  private static function getValueFilter ( &$attributes ) {
  
    /** Fetch and delete */
    if ( array_key_exists ( self::ATTR_VALUEFILTER, $attributes ) ) {

      $valueFilter = (String) $attributes[ self::ATTR_VALUEFILTER ];

      unset ( $attributes[ self::ATTR_VALUEFILTER ] );

    /** Set default */
    } else {
      
      $valueFilter = NULL;
    }
  
    return $valueFilter;
  }
  
  /**
  * Obtain and remove attribute from an array of attributes or set default
  * @XML: NotSoSimpleXMLElement
  * @&Attributes: Array ()
  * @return: String; attribute
  */
  private static function getNodeAttribute ( $XML, &$attributes ) {
  
    $nodeType = self::$tagToNode[
        $XML->getName ()
    ];
    
    $hasAttrCallback = Array ( $nodeType, 'hasAttr' );
    $hasAttribute = call_user_func ( $hasAttrCallback );
    
    if ( $hasAttribute ) {

      /** Obtain attribute from keyAttr node*/
      if (
          $nodeType === 'MapFilter_Pattern_Node_KeyAttr' &&
          array_key_exists ( self::ATTR_ATTR, $attributes ) 
      ) {

        $attrAttr = (String) $attributes[ self::ATTR_ATTR ];
        /** Delete attribute due to asserive behaviour in case of leftover arguments */
        unset ( $attributes[ self::ATTR_ATTR ] );
      }
      
      /** Obtain attribute from attr node */
      if ( $nodeType === 'MapFilter_Pattern_Node_Attr' ) {
        $attrAttr = (String) $XML[ 0 ];
      }

    } else {

      $attrAttr = NULL;
    }

    return $attrAttr;
  }
  
  /**
  * Throw when there are some leftover attributes
  * @attributes: Array
  * @return: Bool
  * @throw: MapFilter_Exception
  */
  private static function assertLeftoverAttrs ( $tagName, $attributes ) {
  
    if ( $attributes != Array () ) {
      
      $attrs = array_keys ( $attributes );
      throw new MapFilter_Exception (
          MapFilter_Exception::INVALID_PATTERN_ATTRIBUTE,
          Array ( $tagName, $attrs[ 0 ] )
      );
    }
    
    return;
  }
  
  /**
  * Parse serialized pattern tree to its object implementation
  * @xml: NotSoSimpleXMLElement
  * @return: MapFilter_Pattern_Node_Abstract
  */
  private static function parse ( NotSoSimpleXMLElement $XML ) {

    /** Parse recursively */
    $followers = array_map (
        Array ( __CLASS__, 'parse' ),
        $XML->getChildren ()
    );

    $tagName = $XML->getName ();

    /** Validate tag name */
    if ( ! self::isValidTag ( $tagName ) ) {

      throw new MapFilter_Exception (
          MapFilter_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }
    
    $attributes = $XML->getAttributes ();

    /** Get ValueFilter if present */
    $valueFilter = self::getValueFilter ( $attributes );

    /** Get Attribute from certain types of nodes */
    $attrAttr = self::getNodeAttribute ( $XML, $attributes );

    /** Parse strictly (throw exception when some attributes left over) */
    $nodeType = self::$tagToNode[ $tagName ];
    self::assertLeftoverAttrs ( $tagName, $attributes );

    $class = self::$tagToNode[ $tagName ];

    /** Deal with the difference in constructor sinpsis */
    if ( $class === 'MapFilter_Pattern_Node_Attr' ) {
      
      return new $class (
          $attrAttr,
          $valueFilter
// NOT IMPLEMENTED
//          $default
      );
    }

    if ( $class === 'MapFilter_Pattern_Node_KeyAttr' ) {
    
      return new $class (
          $followers,
          $attrAttr,
          $valueFilter
      );
    }
    
    return new $class (
        $followers,
        $valueFilter
    );
  }
  
  /**
  * Unwrap not necessary <pattern> tags from very beginning and end of tree
  * @XMLElement: NotSoSimpleXMLElement; Tree root
  * @return: NotSoSimpleXmlElement; New tree root
  * @throws: MapFilter_Exception
  */
  private static function unwrap ( NotSoSimpleXMLElement $XMLElement ) {
   
    $tagName = $XMLElement->getName ();
   
    /** Tree is not wrapped */
    if ( self::isValidTag ( $tagName ) ) {

      return $XMLElement;
    }

    /** Unknown tag */
    if ( $tagName !== self::PATTERN ) {
  
      throw new MapFilter_Exception (
          MapFilter_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }

    /** Too many followers for pattern tag */
    $children = $XMLElement->getChildren ();
    if ( count ( $children ) > 1 ) {
      
      throw new MapFilter_Exception (
          MapFilter_Exception::TOO_MANY_PATTERNS
      );
    }
    
    /** Unwrap */
    return $XMLElement->children ();
  }
  
  /**
  * Fetch created pattern
  * @return: MapFilter_Pattern
  */
  public function fetch () {

    return $this->content;
  }

  /**
  * Satisfy pattern
  * @query: Array
  * @return: Bool
  */
  public function satisfy ( Array $query = Array () ) {
  
    return $this->patternTree->satisfy ( $query );
  }

  /** Clone all tree recursively */
  public function __clone () {

    $this->patternTree = clone ( $this->patternTree );

    return;
  }
  
  /** Get String representation of pattern */
  public function __toString () {
  
    return (String) var_export ( $this->patternTree, TRUE );
  }
}