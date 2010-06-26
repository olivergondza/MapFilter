<?php
/**
 * A MapFilter Interface
 *
 * @since       0.5
 * 
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  Filter
 */

/**
 * A MapFilter Interface
 *
 * @since       0.5
 *
 * @class       MapFilter_Interface
 * @author      Oliver Gondža
 * @ingroup     gfilter
 * @package     MapFilter
 * @subpackage  Filter
 */
interface MapFilter_Interface {

  /**
   * Create new filter instance.
   *
   * @since     0.1
   *
   * @param     MapFilter_Pattern_Interface     $pattern        A pattern to set
   * @param     Array|ArrayAccess               $query	A query to filter
   *
   * If no pattern specified an untouched query will be returned:
   *
   * @clip{User/MapFilter.test.php,testEmptyPattern}
   *
   * All parsing is done just in time (however it can be triggered manually using
   * MapFilter::parse()) when some of parsing results is accessed (in this case
   * when MapFilter::getResults() is called for the first time):
   *
   * @clip{User/TreePattern/Duration.test.php,testDuration}
   *
   * @see       setPattern(), setQuery(), MapFilter_Pattern
   */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL,
      $query = Array ()
  );

  /**
   * Set desired query pattern.
   *
   * Fluent Method
   *
   * @since     0.1
   *
   * @param     MapFilter_Pattern_Interface     $pattern        A pattern to set
   *
   * @return    MapFilter       Instance of MapFilter with new pattern
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setPattern ( MapFilter_Pattern_Interface $pattern );
  
  /**
   * Set a query to filter.
   *
   * @since     0.1
   *
   * @param     Array|ArrayAccess              $query           A query to set
   *
   * @return    MapFilter               Instance of MapFilter with new query
   *
   * MapFilter can be configured using both constructor and specialized fluent
   * methods setPattern() and setQuery():
   *
   * @clip{MapFilter.test.php,testInvocation}
   *
   * @see       __construct()
   */
  public function setQuery ( $query );
  
  /**
   * Get full filtering results.
   *
   * Return recently used pattern to obtain all kind of results to enable
   * user interface usage.
   *
   * @since     0.5
   *
   * @return    MapFilter_Pattern_Interface     Parsing results
   *
   * @see       __construct(), setPattern()
   */
  public function fetchResult ();
  
  /**
   * Get results.
   *
   * Get parsed query from latest parsing process.
   *
   * @since     0.2
   *
   * @return    Array|ArrayAccess               Parsing results
   *
   * @see       fetchResult()
   */
  public function getResults ();
  
  /**
   * Get validation assertions.
   *
   * Return validation asserts that was raised during latest parsing process.
   *
   * @since     0.4
   *
   * @return    Array|ArrayAccess               Parsing asserts
   *
   * @see       fetchResult()
   */
  public function getAsserts ();
  
  /**
   * Get flags
   *
   * Return flags that was sat during latest parsing process.
   *
   * @since     0.4
   *
   * @return    Array|ArrayAccess               Parsing flags
   *
   * @see       fetchResult()
   */
  public function getFlags ();
}
