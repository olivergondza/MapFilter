<?php
/**
* MapFilter_Pattern Interface
*
* @since	0.5
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
*
* @package	MapFilter
*/

/**
* MapFilter_Pattern Interface
*
* @since	0.5
*
* @class	MapFilter_Pattern_Interface
* @package	MapFilter
* @author	Oliver Gondža
*/
interface MapFilter_Pattern_Interface {

  /**
  * Simple Factory Method to load data from string
  *
  * @since	0.1
  *
  * @param	xmlSource	pattern string
  *
  * @return	MapFilter_Pattern	Pattern created from $XmlSource string
  *
  * fromFile() and load() difference demonstration:
  * @clip{Pattern.test.php,testLoadFromFileComparison}
  *
  * @see	fromFile(), __construct()
  */
  public static function load ( $xmlSource );

  /**
  * Simple factory method to instantiate with loading the data from file
  *
  * @since	0.1
  *
  * @param	url	XML pattern file
  *
  * @return	MapFilter_Pattern	Pattern created from $url file
  * 
  * fromFile() and load() difference demonstration:
  * @clip{Pattern.test.php,testLoadFromFileComparison}
  *
  * @see	load(), __construct()
  */
  public static function fromFile ( $url );
  
  /**
  * Create Pattern from Pattern_Tree object.
  *
  * @since	0.1
  * @note New object is created with @b copy of given patternTree
  *
  * @param	patternTree	A pattern tree to use
  *
  * @return	MapFilter_Pattern	Created Pattern
  *
  * @see	load(), fromFile()
  */
  public function __construct ( MapFilter_Pattern_Tree $patternTree );

  /**
  * Get Pattern tree
  *
  * @since	0.1
  *
  * @return	MapFilter_Pattern_Tree	Internal pattern tree
  *
  * @see	__construct()
  */
  public function getTree ();
  
  /**
  * Satisfy pattern
  *
  * @since	0.1
  *
  * @param	param	MapFilter_Pattern_SatisfyParam
  *
  * @return	Bool	Satisfaction results
  */
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param );
  
  /**
  * Pick up results
  *
  * @since	0.1
  *
  * @param	param	MapFilter_Pattern_PickUpParam
  */
  public function pickUp ( MapFilter_Pattern_PickUpParam $param );

  /**
  * Clone pattern tree recursively.
  *
  * @since	0.1
  *
  * @note Deep cloning is used thus new copy of patternTree is going to be
  * created
  */
  public function __clone ();
}