<?php
/**
* Class to handle serialized Patterns
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/

require_once ( dirname ( __FILE__ ) . "/Pattern.php" );
require_once ( dirname ( __FILE__ ) . "/MapFilter_Exception.php" );
require_once ( dirname ( __FILE__ ) . "/NotSoSimpleXMLElement.php" );

/** Define MapFilter_SerializedPattern as a Patern that load itself from file */
class MapFilter_SerializedPattern extends MapFilter_Pattern {
  
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
// NOT IMPLEMENTED
//  const ATTR_FLAG = "flag";
  
  /**
  * Node name Object type mapping
  * @var: Array ( TagName => ObjType )
  */
  private static $tagToNode = Array (
      self::NODE_ALL => parent::NODETYPE_ALL,
      self::NODE_ONE => parent::NODETYPE_ONE,
      self::NODE_OPT => parent::NODETYPE_OPT,
      self::NODE_SOME => parent::NODETYPE_SOME,
      self::NODE_ATTR => parent::NODETYPE_ATTR,
      self::NODE_KEYATTR => parent::NODETYPE_KEYATTR,
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
  
  /** Load Xml source and create XMLElement
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
  * Simple Factory MEthod to load data from string
  * @XMLSpecification: String
  * @return: MapFilter_SerializedPattern
  * @throw: MapFilter_Exception
  */
  public static function load ( $XMLSource ) {
    
    $XMLElement = self::loadXML (
        $XMLSource,
        self::DATA_IS_STRING
    );
    
    return self::parse ( $XMLElement );
  }
  
  /**
  * Simple factory method to instantiate with loading the data from file
  * @url: String; URL
  * @return: MapFilter_SerializedPattern
  * @throws: MapFilter_Exception
  */
  public static function fromFile ( $url ) {
  
    $XMLElement = self::loadXML (
        $url,
        self::DATA_IS_URL
    );
    
    return self::parse ( $XMLElement );
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
      
      $valueFilter = parent::VALUE_FILTER;
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
  
    if ( parent::hasAttribute ( $nodeType ) ) {
    
      /** Obtain attribute from keyAttr node*/
      if (
          $nodeType === self::NODETYPE_KEYATTR &&
          array_key_exists ( self::ATTR_ATTR, $attributes ) 
      ) {
        $attrAttr = (String) $attributes[ self::ATTR_ATTR ];
        unset ( $attributes[ self::ATTR_ATTR ] );
      }
      
      /** Obtain attribute from attr node */
      if ( $nodeType === self::NODETYPE_ATTR ) {
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
  * @return: MapFilter_Pattern
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
    
    $nodeType = self::$tagToNode[
        $tagName
    ];

    $attributes = $XML->getAttributes ();

    /** Get ValueFilter if present */
    $valueFilter = self::getValueFilter ( $attributes );

    /** Get Attribute from certain types of nodes */
    $attrAttr = self::getNodeAttribute ( $XML, $attributes );

    /** Parse strictly (throw exception when some attributes left over) */
    self::assertLeftoverAttrs ( $tagName, $attributes );

    return new parent (
        $nodeType,
        $followers,
        $attrAttr,
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
}