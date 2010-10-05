<?php
/**
 * Class to load Pattern tree.
 *
 * PHP Version 5.1.0
 *
 * This file is part of MapFilter package.
 *
 * MapFilter is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 *                
 * MapFilter is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 *                              
 * You should have received a copy of the GNU Lesser General Public License
 * along with MapFilter.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Pear
 * @package  MapFilter
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.3
 */

/** @cond       PROGRAMMER */

/**
 * @file        MapFilter/TreePattern/Tree/Node/All.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Node/All.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/Opt.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Node/Opt.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/One.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Node/One.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/Some.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Node/Some.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Node/NodeAttr.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Node/NodeAttr.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/KeyAttr.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Leaf/KeyAttr.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/Attr.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Leaf/Attr.php' );

/** @endcond */

/**
 * Class to load  Pattern tree.
 *
 * @category Pear
 * @package  MapFilter
 * @class    MapFilter_TreePattern_Deserialize
 * @author   Oliver Gondža <324706@mail.muni.cz>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License
 * @link     http://github.com/olivergondza/MapFilter
 * @since    0.5.3
 */
class MapFilter_TreePattern_Deserialize {

  /**
   * Data is url.
   *
   * @since     0.1
   *
   * Load data from file
   */
  const DATA_IS_URL = TRUE;
  
  /**
   * Data is string.
   *
   * @since     0.1
   *
   * Load data from string
   */
  const DATA_IS_STRING = FALSE;
  
  /**
   * LibXml error to MapFilter_TreePattern_Exception mapping.
   *
   * @since     0.1
   *
   * @var       Array           $_errorToException
   */
  private static $_errorToException = Array (
      LIBXML_ERR_WARNING => MapFilter_TreePattern_Exception::LIBXML_WARNING,
      LIBXML_ERR_ERROR => MapFilter_TreePattern_Exception::LIBXML_ERROR,
      LIBXML_ERR_FATAL => MapFilter_TreePattern_Exception::LIBXML_FATAL
  );
  
  /**
   * Load Xml source and create XmlElement.
   *
   * @since     0.1
   *
   * @param     String          $xml            XML source.
   * @param     Bool            $isUrl          URL or String.
   *
   * @return    SimpleXmlElement                XmlElement of input.
   * @throws    MapFilter_TreePattern_Exception
   */
  public static function loadXml ( $xml, $isUrl ) {
  
    assert ( is_string ( $xml ) );
    assert ( is_bool ( $isUrl ) );
  
    /** Suppress Error | Warning vomiting into the output stream */
    libxml_use_internal_errors ( TRUE );
    
    /**
     * Options used for XML deserialization by SimpleXmlElement
     * Use compact data allocation | remove blank nodes | translate HTML entities
     */
    $options = LIBXML_COMPACT & LIBXML_NOBLANKS & LIBXML_NOENT;
    
    /** Try to load and raise proper exception accordingly */
    try {
    
      $xmlElement = new SimpleXmlElement ( $xml, $options, $isUrl );
    } catch ( Exception $exception ) {
    
      /** Throw first error */
      $error = libxml_get_last_error ();
      libxml_clear_errors ();

      if ( $error ) {
        
        $exception = new MapFilter_TreePattern_Exception (
            self::$_errorToException[ $error->level ],
            Array ( $error->message, $error->line, $error->file )
        );
      }
        
      throw $exception;
    }

    return $xmlElement;
  }

  /** @cond     INTERNAL */

  /**
   * Valid XML structure tag.
   * @{
   */
  const PATTERN = 'pattern';
  const PATTERNS = 'patterns';
  
  const NODE_ALL = 'all';
  const NODE_ONE = 'one';
  const NODE_OPT = 'opt';
  const NODE_KEYATTR = 'key_attr';
  const NODE_ATTR = 'attr';
  const NODE_SOME = 'some';
  const NODE_NODEATTR = 'node_attr';
  /**@}*/
  
  /**
   * Valid XML attribute.
   * @{
   */
  const ATTR_NAME = 'name';
  
  const ATTR_ATTR = 'attr';
  const ATTR_VALUEFILTER = 'forValue';
  const ATTR_DEFAULT = 'default';
  const ATTR_EXISTENCEDEFAULT = 'existenceDefault';
  const ATTR_VALICATIONDEFAULT = 'validationDefault';
  const ATTR_VALUEPATTERN = 'valuePattern';
  const ATTR_FLAG = 'flag';
  const ATTR_ASSERT = 'assert';
  const ATTR_ITERATOR = 'iterator';
  const ATTR_ATTACHPATTERN = 'attachPattern';
  /**@}*/
  
  /** @endcond */
  
  /**
   * Node name Object type mapping.
   *
   * @since     0.4
   *
   * @var       Array           $_tagToNode
   */
  private static $_tagToNode = Array (
      self::NODE_ALL => 'MapFilter_TreePattern_Tree_Node_All',
      self::NODE_ONE => 'MapFilter_TreePattern_Tree_Node_One',
      self::NODE_OPT => 'MapFilter_TreePattern_Tree_Node_Opt',
      self::NODE_SOME => 'MapFilter_TreePattern_Tree_Node_Some',
      self::NODE_NODEATTR => 'MapFilter_TreePattern_Tree_Node_NodeAttr',
      self::NODE_KEYATTR => 'MapFilter_TreePattern_Tree_Leaf_KeyAttr',
      self::NODE_ATTR => 'MapFilter_TreePattern_Tree_Leaf_Attr',
  );
  
  /**
   * Attribute name Object setter mapping.
   *
   * @since     0.4
   *
   * @var       Array           $_attrToSetter
   */
  private static $_attrToSetter = Array (
      self::ATTR_ATTR => 'setAttribute',
      self::ATTR_VALUEFILTER => 'setValueFilter',
      self::ATTR_DEFAULT => 'setDefault',
      self::ATTR_EXISTENCEDEFAULT => 'setExistenceDefault',
      self::ATTR_VALICATIONDEFAULT => 'setValidationDefault',
      self::ATTR_VALUEPATTERN => 'setValuePattern',
      self::ATTR_FLAG => 'setFlag',
      self::ATTR_ASSERT => 'setAssert',
      self::ATTR_ITERATOR => 'setIterator',
      self::ATTR_ATTACHPATTERN => 'setAttachPattern'
  );
  
  /**
   * Determines whether a tag is valid.
   *
   * @since     0.4
   *
   * @param     String          $tag            A tag name to test.
   *
   * @return    Bool            Valid or not
   */
  private static function _isValidTag ( $tag ) {
  
    assert ( is_string ( $tag ) );
  
    return array_key_exists ( $tag, self::$_tagToNode );
  }
  
  /**
   * Throw when there are some leftover attributes.
   *
   * @since     0.4
   *
   * @param     String          $tagName                A tag with attributes.
   * @param     Array           $attributes             Leftover attributes.
   *
   * @throws    MapFilter_TreePattern_Exception::INVALID_PATTERN_ATTRIBUTE
   */
  private static function _assertLeftoverAttrs ( $tagName, Array $attributes ) {
  
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
   * Obtain and remove attribute from an array of attributes.
   *
   * @since     0.4
   *
   * @param     Array   &$attributes    Array of all provided attributes.
   * @param     String  $attribute      Attribute to obtain.
   *
   * @return    String|FALSE            Attribute name or false.
   */
  private static function _getAttribute ( Array &$attributes, $attribute ) {
  
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
   * Get tag name.
   *
   * @since     0.4
   *
   * @param     SimpleXmlElement        $xml            A node to validate.
   *
   * @return    String                  New tag name.
   *
   * @throws    MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT
   */
  private static function _validateTagName ( SimpleXmlElement $xml ) {
  
    $tagName = $xml->getName ();

    /** Validate tag name */
    if ( !self::_isValidTag ( $tagName ) ) {

      throw new MapFilter_TreePattern_Exception (
          MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
          Array ( $tagName )
      );
    }
    
    return $tagName;
  }

  /**
   * Array key for attributes
   *
   * @since     0.5.3
   */
  const ATTR_ARRAY_KEY = '@attributes';
  
  /**
   * Parse attributes of existing tag.
   *
   * @since     0.4
   *
   * @param     SimpleXmlEmement                $xml    A node to parse.
   * @param     MapFilter_TreePattern_Tree      $node   A pattern node to fill.
   * @param     String                          $tagName        A name of tag.
   *
   * @return    MapFilter_TreePattern_Tree      A pattern node with attributes.
   * @throws    MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE
   */
  private static function _parseTagAttributes (
      SimpleXmlElement $xml,
      MapFilter_TreePattern_Tree $node,
      $tagName
  ) {
  
    assert ( is_string ( $tagName ) );
  
    /** Obtain all attributes and set them using a bunch of soft setters */
    $attrs = (Array) $xml->attributes ();
    $attributes = array_key_exists ( self::ATTR_ARRAY_KEY , $attrs )
        ? $attrs[self::ATTR_ARRAY_KEY]
        : Array ()
    ;
    
    foreach ( self::$_attrToSetter as $attr => $setter ) {

      /** Reset loop if attribute does not exist */
      $attrValue = self::_getAttribute ( $attributes, $attr );

      if ( $attrValue === FALSE ) continue;

      /** Try to set an attribute */
      try {

        $node = call_user_func ( Array ( $node, $setter ), $attrValue );

      } catch ( MapFilter_TreePattern_Tree_Exception $exception ) {
        
        $translate = $exception->getCode ()
             === MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE
        ;
        
        throw ( $translate )
            ? new MapFilter_TreePattern_Exception (
                MapFilter_TreePattern_Exception::INVALID_XML_ATTRIBUTE,
                Array ( $tagName, $attr )
            )
            : $exception
        ;
      }
    }

    /** Unset attributes and make sure that none of them left over */
    self::_assertLeftoverAttrs ( $tagName, $attributes );

    return $node;
  }
  
  /**
   * Create the node according to tag name.
   *
   * @since     0.4
   *
   * @param     String  $tagName        A tag name.
   * @param     Array   $followers      Set of followers to use as a content.
   *
   * @return    MapFilter_TreePattern_Tree
   * @throws    MapFilter_TreePattern_Exception::INVALID_XML_CONTENT
   */
  private static function _createTreeNode ( $tagName, Array $followers ) {
  
    assert ( is_string ( $tagName ) );
  
    /** Instantiate pattern node */
    $class = self::$_tagToNode[ $tagName ];
    $node = new $class ();

    if ( $followers === Array () ) return $node;

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
   * Parse serialized pattern tree to its object implementation.
   *
   * @since     0.4
   *
   * @param     SimpleXmlElement                $xml    An element to parse.
   *
   * @return    MapFilter_TreePattern_Tree      Parsed pattern.
   */
  public static function parseTree ( SimpleXmlElement $xml ) {

    $tagName = self::_validateTagName ( $xml );

    /** Parse followers recursively */
    $followers = array_map (
        'self::parseTree',
        iterator_to_array ( $xml->children (), FALSE )
    );

    $node = self::_createTreeNode ( $tagName, $followers );

    $node = self::_parseTagAttributes ( $xml, $node, $tagName );

    /** Attr node can have attribute in tag body so special check is needed. */
    if ( is_a ( $node, 'MapFilter_TreePattern_Tree_Leaf_Attr' ) ) {

      $alreadySet = (Bool) ( $node->getAttribute () );

      $available = (Bool) strlen ( (String) $xml[ 0 ] );

      if ( !$alreadySet && !$available ) {

        throw new MapFilter_TreePattern_Exception (
            MapFilter_TreePattern_Exception::MISSING_ATTRIBUTE_VALUE
        );
      }

      if ( $available ) {
        $node -> setAttribute ( trim ( (String) $xml[ 0 ] ) );
      }
    }

    return $node;
  }
  
  /**
   * Main pattern name.
   *
   * @since     0.5.3
   */
  const MAIN_PATTERN = 'main';
  
  /**
   * Unwrap not necessary \<pattern\> tags from very beginning and end of tree.
   *
   * @since     0.4
   *
   * @param     SimpleXmlElement   &$xmlElement    An element to unwrap.
   *
   * @return    SimpleXmlElement   Unwrapped element.
   * @throws    MapFilter_TreePattern_Exception
   */
  public static function unwrap ( SimpleXmlElement &$xmlElement ) {
   
    $tagName = $xmlElement->getName ();
   
    /** Tree is not wrapped */
    if ( self::_isValidTag ( $tagName ) ) {

      return Array ();
    }

    /** Unwrap pattern tag */
    if ( $tagName === self::PATTERN ) {
    
      if ( $xmlElement->count () !== 1 ) {
      
        throw new MapFilter_TreePattern_Exception (
            MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
            Array ( self::PATTERN, $xmlElement->count )
        );
      }

      $xmlElement = $xmlElement->children ();

      return Array ();
    }

    /** Unwrap patterns tag */
    if ( $tagName === self::PATTERNS ) {
    
      if ( $xmlElement->count () < 1 ) {
      
        throw new MapFilter_TreePattern_Exception (
            MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
            Array ( self::PATTERN, $xmlElement->count )
        );
      }
    
      $sidePatterns = Array ();
      foreach ( iterator_to_array ( $xmlElement, FALSE ) as $child ) {
        
        if ( $child->count () !== 1 ) {
        
          throw new MapFilter_TreePattern_Exception (
              MapFilter_TreePattern_Exception::HAS_NOT_ONE_FOLLOWER,
              Array ( self::PATTERN, $child->count )
          );
        }

        $attrs = (Array) $child->attributes ();

        $name = (
            array_key_exists ( self::ATTR_ARRAY_KEY , $attrs )
            && array_key_exists ( self::ATTR_NAME, $attrs[ self::ATTR_ARRAY_KEY ] )
        )
            ? $attrs[self::ATTR_ARRAY_KEY][ self::ATTR_NAME ]
            : FALSE
        ;

        if ( $name === FALSE || $name === self::MAIN_PATTERN ) {
        
          $xmlElement = $child->children ();
        } else {
        
          $sidePatterns[ $name ] = $child->children ();
        }
      }
      
      return $sidePatterns;
    }

    /** Unknown tag */
    throw new MapFilter_TreePattern_Exception (
        MapFilter_TreePattern_Exception::INVALID_PATTERN_ELEMENT,
        Array ( $tagName )
    );
  }
}
