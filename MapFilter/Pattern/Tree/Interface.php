<?php
/**
* MapFilter_Pattern_Tree Interface.
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @since 0.4
*/

/**
* MapFilter_Pattern_Tree Interface.
*
* @class	MapFilter_Pattern_Tree_Interface
* @package	MapFilter
* @since 0.4
*/
interface MapFilter_Pattern_Tree_Interface {
  
  /**
  * Set attribute.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	attribute			An attribute to set
  * @return	MapFilter_Pattern_Tree	A pattern with new attribute
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setAttribute ( $attribute );
  
  /**
  * Set default value.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	default			A default value to set
  * @return	MapFilter_Pattern_Tree	A pattern with new default value
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setDefault ( $default );
  
  /**
  * Set content.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	content			A content to set
  * @return	MapFilter_Pattern_Tree	A pattern with new content
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setContent ( Array $content );
  
  /**
  * Set valueFilter.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	valuePattern			A valueFilter to set
  * @return	MapFilter_Pattern_Tree	A pattern with new valueFilter
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setValuePattern ( $valuePattern );

  /**
  * Set valueFilter.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	valueFilter		A valueFilter to set
  * @return	MapFilter_Pattern_Tree	New pattern with valueFilter
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setValueFilter ( $valueFilter );
  
  /**
  * Set Flag.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	flag			A flag to set
  * @return	MapFilter_Pattern_Tree	New pattern with flag
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setFlag ( $flag );
  
  /**
  * Set Assert.
  *
  * A Fluent Method.
  *
  * @since 0.4
  *
  * @param	assert			An assert to set
  * @return	MapFilter_Pattern_Tree	New pattern with flag
  * @throws	MapFilter_Pattern_Tree_Exception::INVALID_XML_ATTRIBUTE
  */
  public function setAssert ( $assert );
  
  /**
  * Create new tree instance.
  *
  * @since 0.4
  *
  * @return	MapFilter_Pattern_Tree_Interface	New instance
  */
  public function __construct ();
  
  /**
  * Make copy of the node
  *
  * @since 0.4
  */
  public function __clone ();
  
  /**
  * Satisfy certain node type and let it's followers to get satisfied.
  *
  * @since 0.4
  *
  * @param	param	Satisfy ParameterObject
  * @return	Bool	Satisfaction result
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param );
  
  /**
  * Pick-up satisfaction results.
  *
  * @since 0.4
  *
  * @param	param	PickUp ParameterObject
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param );
}
