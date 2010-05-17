<?php
/**
* Class to load and hold Pattern tree
*
* @since	0.1
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
*
* @package	MapFilter
*/

/**
* @file		MapFilter/Pattern/Tree/Node/All.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/All.php' );

/**
* @file		MapFilter/Pattern/Tree/Node/Opt.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/Opt.php' );

/**
* @file		MapFilter/Pattern/Tree/Node/One.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/One.php' );

/**
* @file		MapFilter/Pattern/Tree/Node/Some.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/Some.php' );

/**
* @file		MapFilter/Pattern/Tree/Node/KeyAttr.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Node/KeyAttr.php' );

/**
* @file		MapFilter/Pattern/Tree/Leaf/Attr.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Tree/Leaf/Attr.php' );

/**
* @file		MapFilter/Pattern/SatisfyParam.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/SatisfyParam.php' );

/**
* @file		MapFilter/Pattern/PickUpParam.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/PickUpParam.php' );

/**
* @file		3rdParty/NotSoSimpleXmlElement.php
*/
require_once ( dirname ( __FILE__ ) . '/../3rdParty/NotSoSimpleXmlElement.php' );

/**
* @file		MapFilter/Pattern/Exception.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Exception.php' );

/**
* @file		MapFilter/Pattern/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/Pattern/Interface.php' );

/**
* Class to load and hold Pattern tree
*
* @since	0.1
*
* @class	MapFilter_Pattern
* @package	MapFilter
* @author	Oliver Gondža
*/
class MapFilter_Pattern implements MapFilter_Pattern_Interface {

  /**
  * Pattern tree itself
  *
  * @since	0.1
  *
  * @var	MapFilter_Pattern_Tree	$patternTree
  * @see	__construct()
  */
  private $patternTree = NULL;

  /** @cond	INTERNAL */

  /**
  * Data is url
  *
  * @since	0.1
  *
  * Load data from file
  */
  const DATA_IS_URL = TRUE;
  
  /**
  * Data is string
  *
  * @since	0.1
  *
  * Load data from string
  */
  const DATA_IS_STRING = FALSE;
  
  /** @endcond */

  /**
  * @copyfull{MapFilter_Pattern_Interface::load()}
  */
  public static function load ( $xmlSource ) {
    
    assert ( is_string ( $xmlSource ) );
    
    $xmlElement = self::loadXML (
        $xmlSource,
        self::DATA_IS_STRING
    );
    
    return new MapFilter_Pattern (
        self::parse ( $xmlElement )
    );
  }

  /**
  * @copyfull{MapFilter_Pattern_Interface::fromFile()}
  */
  public static function fromFile ( $url ) {
  
    assert ( is_string ( $url ) );
  
    $xmlElement = self::loadXML (
        $url,
        self::DATA_IS_URL
    );
    
    return new MapFilter_Pattern (
        self::parse ( $xmlElement )
    );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::__construct()}
  */
  public function __construct ( MapFilter_Pattern_Tree $patternTree ) {
  
    $this->patternTree = clone ( $patternTree );
    return;
  }

  /** @cond PROGRAMMER */

  /**
  * @copyfull{MapFilter_Pattern_Interface::getTree()}
  */
  public function getTree () {
  
    return $this->patternTree;
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::satisfy()}
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param ) {
  
    return $this->patternTree->satisfy ( $param );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Interface::pickUp()}
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param ) {
  
    return $this->patternTree->pickUp ( $param );
  }

  /**
  * @copyfull{MapFilter_Pattern_Interface::__clone()}
  */
  public function __clone () {

    $this->patternTree = clone ( $this->patternTree );

    return;
  }
  
  /** @endcond */
  
  /**
  * LibXml error to MapFilter_Pattern_Exception mapping
  *
  * @since	0.1
  *
  * @var	Array	$errorToException
  */
  private static $errorToException = Array (
      LIBXML_ERR_WARNING => MapFilter_Pattern_Exception::LIBXML_WARNING,
      LIBXML_ERR_ERROR => MapFilter_Pattern_Exception::LIBXML_ERROR,
      LIBXML_ERR_FATAL => MapFilter_Pattern_Exception::LIBXML_FATAL
  );
  
  /**
  * Load Xml source and create XmlElement
  *
  * @since	0.1
  *
  * @param	xml	XML source
  * @param	isUrl	URL or String
  *
  * @return	NotSoSimpleXmlElement	XmlElement of input
  * @throws	MapFilter_Pattern_Exception
  */
  private static function loadXML ( $xml, $isUrl ) {
  
    /** Suppress Error | Warning vomiting into the output stream */
    libxml_use_internal_errors ( TRUE );
    
    /**
    * Options used for XML deserialization by NotSoSimpleXmlElement
    * Use compact data allocation | remove blank nodes | translate HTML entities
    */
    $options = LIBXML_COMPACT & LIBXML_NOBLANKS & LIBXML_NOENT;
    
    /** Try to load and raise proper exception accordingly */
    try {
    
      $XmlElement = new NotSoSimpleXmlElement ( $xml, $options, $isUrl );

    } catch ( Exception $exception ) {
    
      /** Throw first error */
      $error = libxml_get_last_error ();
      libxml_clear_errors ();

      if ( !$error ) {
        
        throw $exception;
      }

      throw new MapFilter_Pattern_Exception (
          self::$errorToException[ $error->level ],
          Array ( $error->message, $error->line, $error->file )
      );
    }

    /** Sanitize pattern tag */
    $XmlElement = self::unwrap ( $XmlElement );
    
    return $XmlElement;
  }

  /** @cond INTERNAL */

  /**
  * Valid XML structure tag
  * @{
  */
  const PATTERN = 'pattern';
  
  const NODE_ALL = 'all';
  const NODE_ONE = 'one';
  const NODE_OPT = 'opt';
  const NODE_KEYATTR = 'key_attr';
  const NODE_ATTR = 'attr';
  const NODE_SOME = 'some';
  /**@}*/
  
  /**
  * Valid XML attribute
  * @{
  */
  const ATTR_ATTR = 'attr';
  const ATTR_VALUEFILTER = 'forValue';
  const ATTR_DEFAULT = 'default';
  const ATTR_VALUEPATTERN = 'valuePattern';
  const ATTR_FLAG = 'flag';
  const ATTR_ASSERT = 'assert';
  /**@}*/
  
  /** @endcond */
  
  /**
  * Node name Object type mapping
  *
  * @since	0.4
  *
  * @var	Array	$tagToNode
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
  *
  * @since	0.4
  *
  * @var	Array	$attrToSetter
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
  *
  * @since	0.4
  *
  * @param	tag	A tag name to test
  *
  * @return	Bool	Valid or not
  */
  private static function isValidTag ( $tag ) {
  
    return array_key_exists ( $tag, self::$tagToNode );
  }
  
  /**
  * Throw when there are some leftover attributes
  *
  * @since	0.4
  *
  * @param	tagName		A tag with attributes
  * @param	attributes	Leftover attributes
  *
  * @throws	MapFilter_Pattern_Exception::INVALID_PATTERN_ATTRIBUTE
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
  *
  * @since	0.4
  *
  * @param	attributes	Array of all provided attributes
  * @param	attribute	Attribute to obtain
  *
  * @return	String | FALSE		Attribute name or false
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
  * Get tag name
  *
  * @since	0.4
  *
  * @param	xml		A node to validate
  * @return	String		New tag name
  * @throws	MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT
  */
  private static function validateTagName ( NotSoSimpleXmlElement $xml ) {
  
    $tagName = $xml->getName ();

    /** Validate tag name */
    if ( ! self::isValidTag ( $tagName ) ) {

      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }
    
    return $tagName;
  }
  
  /**
  * Parse attributes of existing tag
  *
  * @since	0.4
  *
  * @param	xml		A node to parse
  * @param	node		A pattern node to fill
  * @param	tagName		A name of tag
  *
  * @return	MapFilter_Pattern_Tree		A pattern node with attributes
  * @throws	MapFilter_Pattern_Exception::INVALID_XML_ATTRIBUTE
  */
  private static function parseTagAttributes (
      NotSoSimpleXmlElement $xml,
      MapFilter_Pattern_Tree $node,
      $tagName
  ) {
  
    /** Obtain all attributes and set them by bunch of soft setters */
    $attributes = $xml->getAttributes ();
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

    return $node;
  }
  
  /**
  * Create node according to tag name
  *
  * @since	0.4
  *
  * @param	tagName		A tag name
  * @param	followers	Set of followers to use as a content
  *
  * @return	MapFilter_Pattern_Tree
  * @throws	MapFilter_Pattern_Exception::INVALID_XML_CONTENT
  */
  private static function createTreeNode ( $tagName, Array $followers ) {
  
    /** Instantiate pattern node */
    $class = self::$tagToNode[ $tagName ];
    $node = new $class ();

    if ( $followers === Array () ) {
    
      return $node;
    }

    try {

      $node -> setContent ( $followers );
    } catch ( MapFilter_Pattern_Tree_Exception $exception ) {
    
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_XML_CONTENT,
          Array ( $tagName )
      );
    }
    
    return $node;
  }
  
  /**
  * Parse serialized pattern tree to its object implementation
  *
  * @since	0.4
  *
  * @param	xml	A NotSoSimpleXmlElement element to parse
  *
  * @return	MapFilter_Pattern_Tree	Parsed pattern
  */
  private static function parse ( NotSoSimpleXmlElement $xml ) {

    /** Parse followers recursively */
    $followers = array_map (
        Array ( __CLASS__, 'parse' ),
        $xml->getChildren ()
    );

    $tagName = self::validateTagName ( $xml );
    
    $node = self::createTreeNode ( $tagName, $followers );

    $node = self::parseTagAttributes ( $xml, $node, $tagName );

    /**
    * Since Attr node can have it's attribute in tag body this special check 
    * is needed
    */
    if ( is_a ( $node, 'MapFilter_Pattern_Tree_Leaf_Attr' ) ) {

      $alreadySet = (Bool) ( $node->getAttribute() );

      $available = (Bool) ( (String) $xml[ 0 ] );

      if ( !$alreadySet && !$available ) {
      
        throw new MapFilter_Pattern_Exception (
            MapFilter_Pattern_Exception::MISSING_ATTRIBUTE_VALUE
        );
      }

      if ( $available ) {
        $node -> setAttribute ( (String) $xml[ 0 ] );
      }
    }
    
    return $node;
  }
  
  /**
  * Unwrap not necessary \<pattern\> tags from very beginning and end of tree
  *
  * @since	0.4
  *
  * @param	xmlElement	A NotSoSimpleXmlElement element to unwrap
  *
  * @return	NotSoSimpleXmlElement	Unwrapped element
  * @throws	MapFilter_Pattern_Exception
  */
  private static function unwrap ( NotSoSimpleXmlElement $xmlElement ) {
   
    $tagName = $xmlElement->getName ();
   
    /** Tree is not wrapped */
    if ( self::isValidTag ( $tagName ) ) {

      return $xmlElement;
    }

    /** Unknown tag */
    if ( $tagName !== self::PATTERN ) {
  
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }

    /** Too many followers for pattern tag */
    $children = $xmlElement->getChildren ();
    if ( count ( $children ) > 1 ) {
      
      throw new MapFilter_Pattern_Exception (
          MapFilter_Pattern_Exception::TOO_MANY_PATTERNS
      );
    }
    
    /** Unwrap */
    return $xmlElement->children ();
  }
}
