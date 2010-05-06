<?php
/**
* Abstract Pattern node; Ancestor of all pattern nodes
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @since	0.3
*/

/**
* @file		MapFilter/Pattern/Tree/Exception.php
*/
require_once ( dirname ( __FILE__ ) . '/Tree/Exception.php' );

/**
* @file		MapFilter/Pattern/Tree/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/Tree/Interface.php' );

/**
* Internal pattern tree
*
* @class	MapFilter_Pattern_Tree
* @package	MapFilter
* @since	0.3
*/
abstract class MapFilter_Pattern_Tree
    implements MapFilter_Pattern_Tree_Interface
{
  
  /**
  * Determine whether the node was already satisfied
  *
  * @since	0.3
  *
  * @var	Bool	$satisfied
  */
  private $satisfied = FALSE;
  
  /**
  * Key-Attr value filter.
  *
  * @since	0.3
  *
  * @var	String	$valueFilter
  */
  private $valueFilter = NULL;
  
  /**
  * Node flag
  *
  * @since	0.3
  *
  * @var	String	$flag
  */
  private $flag = NULL;
  
  /**
  * Node assert
  *
  * @since	0.3
  *
  * @var	String	$assert
  */
  private $assert = NULL;
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setAttribute()}
  */
  public function setAttribute ( $attribute ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $attribute )
    );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setDefault()}
  */
  public function setDefault ( $default ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $default )
    );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setContent()}
  */
  public function setContent ( Array $content ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_CONTENT
    );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setValuePattern()}
  */
  public function setValuePattern ( $valuePattern ) {
  
    throw new MapFilter_Pattern_Tree_Exception (
        MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE,
        Array ( $valuePattern )
    );
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setValueFilter()}
  */
  public function setValueFilter ( $valueFilter ) {

    $this->valueFilter = $valueFilter;
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setFlag()}
  */
  public function setFlag ( $flag ) {
  
    $this->flag = $flag;
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setAssert()}
  */
  public function setAssert ( $assert ) {
  
    $this->assert = $assert;
    return $this;
  }
  
  /**
  * Get valueFilter
  *
  * @since	0.3
  *
  * @return	String	Node value filter
  */
  public function getValueFilter () {
  
    return $this->valueFilter;
  }
  
  /**
  * Empty constructor.
  *
  * @since	0.3
  *
  * All setting is done by Fluent Methods.
  *
  * @see	setAssert(), setFlag(), setValueFilter(), setValuePattern(),
  * 		setContent(), setDefault() or setAttribute()
  */
  final public function __construct () {}
  
  /**
  * Satisfy certain node and do all necessary work to get (un)satisfied
  *
  * @since	0.3
  *
  * @param	cond	Bool Satisfy condition
  * @param	param	Parameter object
  *
  * @return	Bool	Was or was not satisfied
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
  * @copyfull{MapFilter_Pattern_Tree_Interface::isSatisfied()}
  */
  public function isSatisfied () {
  
    return $this->satisfied;
  }
  
  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::attrPresent()}
  */
  public static function attrPresent ( $attrName, $query ) {
    
    return array_key_exists (
        $attrName,
        $query
    );
  }
  
  /** @cond INTERNAL */
  
  /**
  * Filter boundaries.
  *
  * @since	0.3
  *
  * A format string to enclose pattern with begin and end mark to ensure
  * that the string are completely (not partially) equal. 
  */
  const FILTER_BOUNDARIES = '/^%s$/';
  
  /**
  * PCRE filter delimiter
  *
  * @since	0.3
  * 
  * Special char to enclose PCRE filter.
  */
  const FILTER_DELIMITER = '/';
  
  /** @endcond */
  
  /**
  * Test whether a ForValue condition on tree node fits given pattern.
  *
  * @since	0.3
  *
  * @param	valueCandidate	A value to fit
  * @param	pattern		Value pattern
  *
  * @return	Bool	Does the value fit
  */
  protected function valueFits ( $valueCandidate, $pattern ) {

    if ( !$pattern ) {

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
