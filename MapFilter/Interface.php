<?php
/**
* A MapFilter Interface
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
* A MapFilter Interface
*
* @since	0.1
*
* @class	MapFilter_Interface
* @author	Oliver Gondža
* @package	MapFilter
*/
interface MapFilter_Interface {

  /**
  * Create new filter instance.
  *
  * @since	0.1
  *
  * @param	pattern		A pattern to set
  * @param	query		A query to filter
  *
  * If no pattern specified an untouched query will be returned:
  *
  * @clip{User.test.php,testEmptyPattern}
  *
  * All parsing is done just in time (however it can be triggered manually using
  * MapFilter::parse()) when some of parsing results is accessed (in this case
  * when MapFilter::getResults() is called for the first time):
  *
  * @clip{User.test.php,testDuration}
  *
  * @see	setPattern(), setQuery(), MapFilter_Pattern
  */
  public function __construct (
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  );

  /**
  * Set desired query pattern.
  *
  * Fluent Method
  *
  * @since	0.1
  *
  * @param	pattern		A pattern to set
  *
  * @return	MapFilter	Instance of MapFilter with new pattern
  *
  * MapFilter can be configured using both constructor and specialized fluent
  * methods setPattern() and setQuery():
  *
  * @clip{MapFilter.test.php,testInvocation}
  *
  * @see	__construct()
  */
  public function setPattern ( MapFilter_Pattern $pattern );
  
  /**
  * Set query to filter.
  *
  * @since	0.1
  *
  * @param	query	A query to set
  *
  * @return	MapFilter	Instance of MapFilter with new query
  *
  * MapFilter can be configured using both constructor and specialized fluent
  * methods setPattern() and setQuery():
  *
  * @clip{MapFilter.test.php,testInvocation}
  *
  * @see	__construct()
  * @todo	Arbitrary iterator query should be supported
  */
  public function setQuery ( Array $query );
  
  /**
  * Get results.
  *
  * Get parsed query from latest parsing process.
  *
  * @since	0.2
  *
  * @return	Array	Parsing results
  */
  public function getResults ();
  
  /**
  * Get validation assertions.
  *
  * Return validation asserts that was raised during latest parsing process.
  *
  * @since	0.1
  *
  * @return	Array	Parsing asserts
  */
  public function getAsserts ();
  
  /**
  * Get flags
  *
  * Return flags that was sat during latest parsing process.
  *
  * @since	0.1
  *
  * @return	Array	Parsing flags
  */
  public function getFlags ();
}
