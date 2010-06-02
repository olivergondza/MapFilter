<?php
/**
 * Class to load and hold Pattern tree
 *
 * @since	0.1
 *
 * @author	Oliver Gondža
 * @link	http://github.com/olivergondza/MapFilter
 * @license	GNU GPLv3
 * @copyright	2009-2010 Oliver Gondža
 *
 * @package	MapFilter
 * @subpackage	TreePattern
 */

/** @cond	INTERNAL */

/**
 * @file		3rdParty/NotSoSimpleXmlElement.php
 */
require_once ( dirname ( __FILE__ ) . '/../3rdParty/NotSoSimpleXmlElement.php' );

/** @endcond */

/** @cond	PROGRAMMER */

/**
 * @file		MapFilter/TreePattern/Tree/Node/All.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Node/All.php' );

/**
 * @file		MapFilter/TreePattern/Tree/Node/Opt.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Node/Opt.php' );

/**
 * @file		MapFilter/TreePattern/Tree/Node/One.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Node/One.php' );

/**
 * @file		MapFilter/TreePattern/Tree/Node/Some.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Node/Some.php' );

/**
 * @file		MapFilter/TreePattern/Tree/Node/KeyAttr.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Node/KeyAttr.php' );

/**
 * @file		MapFilter/TreePattern/Tree/Leaf/Attr.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Tree/Leaf/Attr.php' );

/**
 * @file		MapFilter/TreePattern/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/TreePattern/Exception.php' );

/**
 * @file		MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Pattern/Interface.php' );

/**
 * @file		MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Pattern/AssertInterface.php' );

/**
 * @file		MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Pattern/FlagInterface.php' );

/**
 * @file		MapFilter/Pattern/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Pattern/ResultInterface.php' );

/** @endcond */

/**
 * Class to load and hold Pattern tree
 *
 * @since	0.1
 *
 * @author	Oliver Gondža
 * @class	MapFilter_TreePattern
 *
 * @ingroup	gfilter
 * @package	MapFilter
 * @subpackage	TreePattern
 */
class MapFilter_TreePattern implements
    MapFilter_Pattern_Interface,
    MapFilter_Pattern_AssertInterface,
    MapFilter_Pattern_FlagInterface,
    MapFilter_Pattern_ResultInterface
{

  /**
   * Pattern tree itself
   *
   * @since	0.1
   *
   * @var	MapFilter_TreePattern_Tree	$patternTree
   * @see	__construct()
   */
  private $patternTree = NULL;

  /**
   * Validated data
   *
   * @since	0.5
   *
   * @var	Array|ArrayAccess		$results
   * @see	getResults(), parse()
   */
  private $results = Array ();
  
  /**
   * Validation asserts
   *
   * @since	0.5
   *
   * @var	Array|ArrayAccess		$asserts
   * @see	getAsserts(), parse()
   */
  private $asserts = Array ();
  
  /**
   * Validation flags
   *
   * @since	0.5
   *
   * @var	Array|ArrayAccess		$flags
   * @see	getFlags(), parse()
   */
  private $flags = Array ();

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
   * Simple Factory Method to load data from string
   *
   * @since	0.1
   *
   * @param	String			$xmlSource	pattern string
   *
   * @return	MapFilter_TreePattern	Pattern created from $xmlSource string
   *
   * fromFile() and load() difference demonstration:
   * @clip{Pattern.test.php,testLoadFromFileComparison}
   *
   * @see	fromFile(), __construct()
   */
  public static function load ( $xmlSource ) {
    
    assert ( is_string ( $xmlSource ) );
    
    $xmlElement = self::loadXML (
        $xmlSource,
        self::DATA_IS_STRING
    );
    
    return new MapFilter_TreePattern (
        self::parseTree ( $xmlElement )
    );
  }

  /**
   * Simple factory method to instantiate with loading the data from file
   *
   * @since	0.1
   *
   * @param	String			$url	XML pattern file
   *
   * @return	MapFilter_TreePattern	Pattern created from $url file
   * 
   * fromFile() and load() difference demonstration:
   * @clip{Pattern.test.php,testLoadFromFileComparison}
   *
   * @see	load(), __construct()
   */
  public static function fromFile ( $url ) {
  
    assert ( is_string ( $url ) );
  
    $xmlElement = self::loadXML (
        $url,
        self::DATA_IS_URL
    );
    
    return new MapFilter_TreePattern (
        self::parseTree ( $xmlElement )
    );
  }
  
  /** @cond PROGRAMMER */
  
  /**
   * Create a Pattern from the Pattern_Tree object.
   *
   * @since	0.1
   * @note New object is created with @b copy of given patternTree
   *
   * @param	MapFilter_TreePattern_Tree	$patternTree	A tree to use
   *
   * @return	MapFilter_TreePattern		Created Pattern
   *
   * @see	load(), fromFile()
   */
  public function __construct ( MapFilter_TreePattern_Tree $patternTree ) {
  
    $this->patternTree = clone ( $patternTree );
  }

  /**
   * Clone pattern tree recursively.
   *
   * @since	0.1
   *
   * @note Deep cloning is used thus new copy of patternTree is going to be
   * created
   */
  public function __clone () {

    $this->patternTree = clone ( $this->patternTree );
  }
  
  /**
   * @copyfull{MapFilter_Pattern_ResultInterface::getResults()}
   */
  public function getResults () {

    return $this->tempTree->pickUp ( Array () );
  }
  
  /**
   * @copyfull{MapFilter_Pattern_AssertInterface::getAsserts()}
   */
  public function getAsserts () {
  
    return $this->asserts;
  }
  
  /**
   * @copyfull{MapFilter_Pattern_FlagInterface::getFlags()}
   */
  public function getFlags () {
  
    return $this->tempTree->pickUpFlags ( Array () );
  }
  
  /**
   * Clean up object storage.
   *
   * @since	0.5
   *
   * This enables to parse multiple queries with the same pattern with no need 
   * to re-instantiate the object.
   */
  private function cleanup () {
  
    $this->results = Array ();
    $this->asserts = Array ();
    $this->flags = Array ();
    $this->tempTree = NULL;
  }
  
  /**
   * @copyfull{MapFilter_Pattern_Interface::parse()}
   */
  public function parse ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
  
    $this->cleanup ();
  
    $this->tempTree = clone ( $this->patternTree );
  
    $this->tempTree->satisfy ( $query, $this->asserts );   
  }
  
  /** @endcond */
  
  /**
   * LibXml error to MapFilter_TreePattern_Exception mapping
   *
   * @since	0.1
   *
   * @var	Array	$errorToException
   */
  private static $errorToException = Array (
      LIBXML_ERR_WARNING => MapFilter_TreePattern_Exception::LIBXML_WARNING,
      LIBXML_ERR_ERROR => MapFilter_TreePattern_Exception::LIBXML_ERROR,
      LIBXML_ERR_FATAL => MapFilter_TreePattern_Exception::LIBXML_FATAL
  );
  
  /**
   * Load Xml source and create XmlElement
   *
   * @since	0.1
   *
   * @param	String		$xml	XML source
   * @param	Bool		$isUrl	URL or String
   *
   * @return	NotSoSimpleXmlElement	XmlElement of input
   * @throws	MapFilter_TreePattern_Exception
   */
  private static function loadXML ( $xml, $isUrl ) {
  
    assert ( is_string ( $xml ) );
    assert ( is_bool ( $isUrl ) );
  
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

      if ( $error ) {
        
        $exception = new MapFilter_TreePattern_Exception (
            self::$errorToException[ $error->level ],
            Array ( $error->message, $error->line, $error->file )
        );
      }
        
      throw $exception;
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
      self::NODE_ALL => 'MapFilter_TreePattern_Tree_Node_All',
      self::NODE_ONE => 'MapFilter_TreePattern_Tree_Node_One',
      self::NODE_OPT => 'MapFilter_TreePattern_Tree_Node_Opt',
      self::NODE_SOME => 'MapFilter_TreePattern_Tree_Node_Some',
      self::NODE_KEYATTR => 'MapFilter_TreePattern_Tree_Node_KeyAttr',
      self::NODE_ATTR => 'MapFilter_TreePattern_Tree_Leaf_Attr',
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
   * @param	String		$tag	A tag name to test
   *
   * @return	Bool		Valid or not
   */
  private static function isValidTag ( $tag ) {
  
    assert ( is_string ( $tag ) );
  
    return array_key_exists ( $tag, self::$tagToNode );
  }
  
  /**
   * Throw when there are some leftover attributes
   *
   * @since	0.4
   *
   * @param	String		$tagName		A tag with attributes
   * @param	Array		$attributes		Leftover attributes
   *
   * @throws	MapFilter_TreePattern_Exception::INVALID_PATTERN_ATTRIBUTE
   */
  private static function assertLeftoverAttrs ( $tagName, Array $attributes ) {
  
    assert ( is_string ( $tagName ) );
  
    if ( $attributes != Array () ) {
      
      $attrs = array_keys ( $attributes );
      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ATTRIBUTE,
          Array ( $tagName, $attrs[ 0 ] )
      );
    }
  }
  
  /**
   * Obtain and remove attribute from an array of attributes
   *
   * @since	0.4
   *
   * @param	Array	$attributes	Array of all provided attributes
   * @param	String	$attribute	Attribute to obtain
   *
   * @return	String|FALSE		Attribute name or false
   */
  private static function getAttribute ( Array &$attributes, $attribute ) {
  
    assert ( is_string ( $attribute ) );
  
    $value = FALSE;
    if ( array_key_exists ( $attribute, $attributes ) ) {

      /** Fetch and delete */
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
   * @param	NotSoSimpleXmlElement	$xml		A node to validate
   *
   * @return	String			New tag name
   *
   * @throws	MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT
   */
  private static function validateTagName ( NotSoSimpleXmlElement $xml ) {
  
    $tagName = $xml->getName ();

    /** Validate tag name */
    if ( ! self::isValidTag ( $tagName ) ) {

      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
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
   * @param	NotSoSimpleXmlEmement		$xml	A node to parse
   * @param	MapFilter_TreePattern_Tree	$node	A pattern node to fill
   * @param	String				$tagName	A name of tag
   *
   * @return	MapFilter_TreePattern_Tree		A pattern node with attributes
   * @throws	MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE
   */
  private static function parseTagAttributes (
      NotSoSimpleXmlElement $xml,
      MapFilter_TreePattern_Tree $node,
      $tagName
  ) {
  
    assert ( is_string ( $tagName ) );
  
    /** Obtain all attributes and set them using a bunch of soft setters */
    $attributes = $xml->getAttributes ();
    foreach ( self::$attrToSetter as $attr => $setter ) {

      /** Reset loop if attribute does not exist */
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
      } catch ( MapFilter_TreePattern_Tree_Exception $exception ) {
        
        throw new MapFilter_TreePattern_Exception (
            MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE,
            Array ( $tagName, $attr )
        );
      }
    }
    
    /** Unset attributes and make sure that none of them left over */
    self::assertLeftoverAttrs ( $tagName, $attributes );

    return $node;
  }
  
  /**
   * Create the node according to tag name
   *
   * @since	0.4
   *
   * @param	String	$tagName	A tag name
   * @param	Array	$followers	Set of followers to use as a content
   *
   * @return	MapFilter_TreePattern_Tree
   * @throws	MapFilter_TreePattern_Exception::INVALID_XML_CONTENT
   */
  private static function createTreeNode ( $tagName, Array $followers ) {
  
    assert ( is_string ( $tagName ) );
  
    /** Instantiate pattern node */
    $class = self::$tagToNode[ $tagName ];
    $node = new $class ();

    if ( $followers === Array () ) {
    
      return $node;
    }

    try {

      $node -> setContent ( $followers );
    } catch ( MapFilter_TreePattern_Tree_Exception $exception ) {
    
      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::INVALID_XML_CONTENT,
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
   * @param	NotSoSimpleXmlElement	$xml	An element to parse
   *
   * @return	MapFilter_TreePattern_Tree	Parsed pattern
   */
  private static function parseTree ( NotSoSimpleXmlElement $xml ) {

    /** Parse followers recursively */
    $followers = array_map (
        Array ( __CLASS__, 'parseTree' ),
        $xml->getChildren ()
    );

    $tagName = self::validateTagName ( $xml );
    
    $node = self::createTreeNode ( $tagName, $followers );

    $node = self::parseTagAttributes ( $xml, $node, $tagName );

    /**
     * Since Attr node can have its attribute in tag body this special check 
     * is needed
     */
    if ( is_a ( $node, 'MapFilter_TreePattern_Tree_Leaf_Attr' ) ) {

      $alreadySet = (Bool) ( $node->getAttribute() );

      $available = (Bool) ( (String) $xml[ 0 ] );

      if ( !$alreadySet && !$available ) {
      
        throw new MapFilter_TreePattern_Exception (
            MapFilter_TreePattern_Exception::MISSING_ATTRIBUTE_VALUE
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
   * @param	NotSoSimpleXmlElement	$xmlElement	An element to unwrap
   *
   * @return	NotSoSimpleXmlElement	Unwrapped element
   * @throws	MapFilter_TreePattern_Exception
   */
  private static function unwrap ( NotSoSimpleXmlElement $xmlElement ) {
   
    $tagName = $xmlElement->getName ();
   
    /** Tree is not wrapped */
    if ( self::isValidTag ( $tagName ) ) {

      return $xmlElement;
    }

    /** Unknown tag */
    if ( $tagName !== self::PATTERN ) {
  
      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }

    /** Too many followers for pattern tag */
    $children = $xmlElement->getChildren ();
    if ( count ( $children ) > 1 ) {
      
      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::TOO_MANY_PATTERNS
      );
    }
    
    /** Unwrap */
    return $xmlElement->children ();
  }
}
