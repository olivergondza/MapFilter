<?php
/**
* Data type to load and hold Pattern tree
*
* Author: Oliver Gondža
* E-mail: 324706(at)mail.muni.cz
* License: GNU GPLv3
* Copyright: 2009-2010 Oliver Gondža
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Exception.php' );

require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/All.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/Opt.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/One.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/Some.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/KeyAttr.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Leaf/Attr.php' );

require_once ( dirname ( __FILE__ ) . '/Pattern/SatisfyParam.php' );
require_once ( dirname ( __FILE__ ) . '/Pattern/PickUpParam.php' );

require_once ( dirname ( __FILE__ ) . '/NotSoSimpleXMLElement.php' );

require_once ( dirname ( __FILE__ ) . '/Pattern_Interface.php' );

class MapFilter_Pattern implements MapFilter_Pattern_Interface {

  /**
  * Pattern tree itself
  * @var MapFilter_Pattern_Tree
  */
  private $patternTree = NULL;

  /**
  * Create Pattern from Pattern_Tree onbject creating a new copy of it.
  * $param MapFilter_Pattern_Tree
  */
  public function __construct ( MapFilter_Pattern_Tree $patternTree ) {
  
    $this->patternTree = clone ( $patternTree );
    return;
  }

  /**
  * Get Pattern tree
  * @return MapFilter_Pattern_Tree
  */
  public function getTree () {
  
    return $this->patternTree;
  }

  /**
  * Disallow load from file
  * @var Bool
  */
  const DATA_IS_URL = TRUE;
  const DATA_IS_STRING = FALSE;

  /**
  * Simple Factory Method to load data from string
  * @param String
  * @return MapFilter_Pattern
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
  * @param String; URL
  * @return MapFilter_Pattern
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
  
  /**
  * LibXml error to MapFilter_Pattern_Exception mapping
  * @var Array ( LIBXML_ERR_* => MapFilter_Pattern_Exception::LIBXML_* )
  */
  private static $errorToException = Array (
      LIBXML_ERR_WARNING => MapFilter_Pattern_Exception::LIBXML_WARNING,
      LIBXML_ERR_ERROR => MapFilter_Pattern_Exception::LIBXML_ERROR,
      LIBXML_ERR_FATAL => MapFilter_Pattern_Exception::LIBXML_FATAL
  );
  
  /**
  * Load Xml source and create XMLElement
  * @param String;
  * @param Bool;
  * @return NotSoSimpleXMLElement
  * @throws MapFilter_Pattern_Exception
  */
  private static function loadXML ( $xml, $isUrl ) {
  
    /** Suppress Error | Warning vomiting into the output stream */
    libxml_use_internal_errors ( TRUE );
    
    /**
    * Options used for XML deserialization by NotSoSimpleXMLElement
    * Use compact data allocation | remove blank nodes | translate HTML entities
    */
    $options = LIBXML_COMPACT & LIBXML_NOBLANKS & LIBXML_NOENT;
    
    /** Try to load and raise proper exception accordingly */
    try {
    
      $XMLElement = new NotSoSimpleXMLElement ( $xml, $options, $isUrl );

    } catch ( Exception $exception ) {
    
      /** Throw first error */
      $error = libxml_get_last_error ();
      libxml_clear_errors ();

      throw new MapFilter_Pattern_Exception (
          self::$errorToException[ $error->level ],
          Array ( $error->message, $error->line, $error->file )
      );
    }

    /** Sanitize pattern tag */
    $XMLElement = self::unwrap ( $XMLElement );
    
    return $XMLElement;
  }

  /**
  * Allowed XML struct tags
  * @var String
  */
  const PATTERN = 'pattern';
  
  const NODE_ALL = 'all';
  const NODE_ONE = 'one';
  const NODE_OPT = 'opt';
  const NODE_KEYATTR = 'key_attr';
  const NODE_ATTR = 'attr';
  const NODE_SOME = 'some';
  
  /**
  * Allowed XML attributes
  * @var String
  */
  const ATTR_ATTR = 'attr';
  const ATTR_VALUEFILTER = 'forValue';
  const ATTR_DEFAULT = 'default';
  const ATTR_VALUEPATTERN = 'valuePattern';
  const ATTR_FLAG = 'flag';
  const ATTR_ASSERT = 'assert';
  
  /**
  * Node name Object type mapping
  * @var Array ( TagName => ObjType )
  */
  private static $tagToNode = Array (
      self::NODE_ALL => 'MapFilter_Pattern_Tree_Node_All',
      self::NODE_ONE => 'MapFilter_Pattern_Tree_Node_One',
      self::NODE_OPT => 'MapFilter_Pattern_Tree_Node_Opt',
      self::NODE_SOME => 'MapFilter_Pattern_Tree_Node_Some',
      self::NODE_KEYATTR => 'MapFilter_Pattern_Tree_Node_KeyAttr',
      self::NODE_ATTR => 'MapFilter_Pattern_Tree_Leaf_Attr',
  );
  
  /**
  * Attribute name Object setter mapping
  * @var Array ( AttrName => SetterMethod )
  */
  private static $attrToSetter = Array (
      self::ATTR_ATTR => 'setAttribute',
      self::ATTR_VALUEFILTER => 'setValueFilter',
      self::ATTR_DEFAULT => 'setDefault',
      self::ATTR_VALUEPATTERN => 'setValuePattern',
      self::ATTR_FLAG => 'setFlag',
      self::ATTR_ASSERT => 'setAssert'
  );
  
  /**
  * Determines whether a tag is valid
  * @param String
  * @return Bool
  */
  private static function isValidTag ( $tag ) {
  
    return array_key_exists ( $tag, self::$tagToNode );
  }
  
  /**
  * Throw when there are some leftover attributes
  * @param Array
  * @param Array
  * @throws MapFilter_Pattern_Exception::INVALID_PATTERN_ATTRIBUTE
  */
  private static function assertLeftoverAttrs ( $tagName, $attributes ) {
  
    if ( $attributes != Array () ) {
      
      $attrs = array_keys ( $attributes );
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ATTRIBUTE,
          Array ( $tagName, $attrs[ 0 ] )
      );
    }
    
    return;
  }
  
  /**
  * Obtain and remove attribute from an array of attributes
  * @param &Array ()
  * @param String
  * @return String | FALSE
  */
  private static function getAttribute ( &$attributes, $attribute ) {
  
    $value = FALSE;
    
    /** Fetch and delete */
    if ( array_key_exists ( $attribute, $attributes ) ) {

      $value = (String) $attributes[ $attribute ];
      unset ( $attributes[ $attribute] );
    }
  
    return $value;
  }
  
  /**
  * Parse serialized pattern tree to its object implementation
  * @param NotSoSimpleXMLElement
  * @return MapFilter_Pattern_Tree_Abstract
  */
  private static function parse ( NotSoSimpleXMLElement $XML ) {

    /** Parse followers recursively */
    $followers = array_map (
        Array ( __CLASS__, 'parse' ),
        $XML->getChildren ()
    );

    $tagName = $XML->getName ();

    /** Validate tag name */
    if ( ! self::isValidTag ( $tagName ) ) {

      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }
    
    /** Instantiate pattern node */
    $class = self::$tagToNode[ $tagName ];
    $node = new $class ();

    try {
      if ( $followers !== Array () ) {
        $node -> setContent ( $followers );
      }
    } catch ( MapFilter_Pattern_Tree_Exception $exception ) {
    
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_XML_CONTENT, Array ( $tagName )
      );
    }
    
    /** Obtain all attributes and set them by bunch of soft setters */
    $attributes = $XML->getAttributes ();
    foreach ( self::$attrToSetter as $attr => $setter ) {

      /** Reset loop if attribute does not exists */
      $attrValue = self::getAttribute ( $attributes, $attr );
      if ( $attrValue === FALSE ) {
        continue;
      }

      /**
      * Try to set an attribute or rise an exception when attribute is not
      * supported
      */
      try {
        $node = call_user_func (
            Array ( $node, $setter ),
            $attrValue
        );
      } catch ( MapFilter_Pattern_Tree_Exception $exception ) {
        
        throw new MapFilter_Pattern_Exception (
            MapFilter_Pattern_Exception::INVALID_XML_ATTRIBUTE,
            Array ( $tagName, $attr )
        );
      }
    }

    /** Unset attributes and make sure that none of them left over */
    self::assertLeftoverAttrs ( $tagName, $attributes );
    unset ( $attributes );

    /**
    * Since Attr node can have it's attribute in tag body this special check 
    * is needed
    */
    if ( is_a ( $node, 'MapFilter_Pattern_Tree_Leaf_Attr' ) ) {

      if ( $XML[ 0 ] ) {
      
        $node -> setAttribute ( (String) $XML[ 0 ] );
      }
    }
    
    return $node;
  }
  
  /**
  * Unwrap not necessary <pattern> tags from very beginning and end of tree
  * @param NotSoSimpleXMLElement; Tree root
  * @return NotSoSimpleXMLElement; New tree root
  * @throws MapFilter_Pattern_Exception
  */
  private static function unwrap ( NotSoSimpleXMLElement $XMLElement ) {
   
    $tagName = $XMLElement->getName ();
   
    /** Tree is not wrapped */
    if ( self::isValidTag ( $tagName ) ) {

      return $XMLElement;
    }

    /** Unknown tag */
    if ( $tagName !== self::PATTERN ) {
  
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }

    /** Too many followers for pattern tag */
    $children = $XMLElement->getChildren ();
    if ( count ( $children ) > 1 ) {
      
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::TOO_MANY_PATTERNS
      );
    }
    
    /** Unwrap */
    return $XMLElement->children ();
  }
  
  /**
  * Satisfy pattern
  * @param MapFilter_Pattern_SatisfyParam
  * @return Bool
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    return $this->patternTree->satisfy ( $param );
  }
  
  /**
  * Pick up results
  * @param MapFilter_Pattern_PickUpParam
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {
  
    return $this->patternTree->pickUp ( $param );
  }

  /**
  * Clone pattern tree
  */
  public function __clone () {

    $this->patternTree = clone ( $this->patternTree );

    return;
  }
  
}