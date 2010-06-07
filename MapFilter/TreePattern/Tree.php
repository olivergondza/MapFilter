<?php
/**
 * Abstract Pattern node; Ancestor of all pattern nodes
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */

/**
 * @file        MapFilter/TreePattern/Tree/Exception.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Exception.php' );

/**
 * @file        MapFilter/TreePattern/Tree/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/Tree/Interface.php' );

/**
 * Internal pattern tree
 *
 * @class       MapFilter_TreePattern_Tree
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.3
 */
abstract class MapFilter_TreePattern_Tree implements
    MapFilter_TreePattern_Tree_Interface
{
  
  /**
   * Determine whether the node was already satisfied
   *
   * @since     0.3
   *
   * @var       Bool            $satisfied
   */
  protected $satisfied = FALSE;
  
  /**
   * Key-Attr value filter.
   *
   * @since     0.3
   *
   * @var       String          $valueFilter
   */
  private $valueFilter = NULL;
  
  /**
   * Node flag
   *
   * @since     0.3
   *
   * @var       String          $flag
   */
  protected $flag = NULL;
  
  /**
   * Node assert
   *
   * @since     0.3
   *
   * @var       String          $assert
   */
  protected $assert = NULL;
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setAttribute()}
   */
  public function setAttribute ( $attribute ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $attribute )
    );
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setDefault()}
   */
  public function setDefault ( $default ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $default )
    );
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setContent()}
   */
  public function setContent ( Array $content ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_CONTENT
    );
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setValuePattern()}
   */
  public function setValuePattern ( $valuePattern ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $valuePattern )
    );
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setIterator()}
   */
  public function setIterator ( $iterator ) {
  
    throw new MapFilter_TreePattern_Tree_Exception (
        MapFilter_TreePattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $iterator )
    );
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setValueFilter()}
   */
  public function setValueFilter ( $valueFilter ) {

    assert ( is_string ( $valueFilter ) );

    $this->valueFilter = $valueFilter;
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setFlag()}
   */
  public function setFlag ( $flag ) {
  
    assert ( is_string ( $flag ) );
  
    $this->flag = $flag;
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_TreePattern_Tree_Interface::setAssert()}
   */
  public function setAssert ( $assert ) {
  
    assert ( is_string ( $assert ) );
  
    $this->assert = $assert;
    return $this;
  }
  
  /**
   * Get valueFilter
   *
   * @since     0.3
   *
   * @return    String          Node value filter
   */
  protected function getValueFilter () {
  
    return (String) $this->valueFilter;
  }
  
  /**
   * Empty constructor.
   *
   * @since     0.3
   *
   * All setting is done by Fluent Methods.
   *
   * @see       setAssert(), setFlag(), setValueFilter(), setValuePattern(),
   *            setContent(), setDefault() or setAttribute()
   */
  public function __construct () {}
  
  /**
  * Set assertion value
  *
  * @since      0.5.2
  *
  * @param      Array           $asserts
  * @param      Mixed           $assertValue            An assert value to set
  *
  * @return     NULL
  */
  protected function setAssertValue ( Array &$asserts, $assertValue = NULL ) {
  
    if ( $this->assert === NULL ) {
    
      return;
    }
    
    $asserts[ $this->assert ] = ( $assertValue === NULL )
        ? $this->assert
        : $assertValue;
  }
  
  /**
   * Determine whether the node is satisfied.
   *
   * @since     0.4
   *
   * @return    Bool            Satisfied or not
   */
  protected function isSatisfied () {
  
    return $this->satisfied;
  }
  
  /**
   * Test whether an argument is present in the query.
   *
   * @since     0.4
   *
   * @param     String                  $attrName       Name of an attribute
   * @param     Array|ArrayAccess       $query          Input array
   *
   * @return    Bool                    Attribute present or not
   */
  protected static function attrPresent ( $attrName, $query ) {
    
    assert ( is_string ( $attrName ) );
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
    
    return array_key_exists (
        $attrName,
        $query
    );
  }
  
  /** @cond     INTERNAL */
  
  /**
   * Filter boundaries.
   *
   * @since     0.3
   *
   * A format string to enclose the pattern with begin and end mark to ensure
   * that the strings are completely (not partially) equal. 
   */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /**
   * PCRE filter delimiter
   *
   * @since     0.3
   * 
   * Special char to enclose PCRE filter.
   */
  const FILTER_DELIMITER = '/';
  
  /** @endcond */
  
  /**
   * Test whether a ForValue condition on tree node fits given pattern.
   *
   * @since     0.3
   *
   * @param     Mixed           $valueCandidate 	A value to fit
   * @param     String|NULL     $pattern                Value pattern
   *
   * @return    Bool            Does the value fit
   */
  protected function valueFits ( $valueCandidate, $pattern ) {

    if ( $pattern === NULL ) {

      return TRUE;
    }

    /** Sanitize inputted PCRE */
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
