<?php
/**
 * Class to filter key-value data structures.
 *
 * @package     MapFilter
 * @subpackage  Filter
 * @author      Oliver Gondža
 * @copyright   2009-2010 Oliver Gondža
 * @license     LGPL
 * @link        http://github.com/olivergondza/MapFilter
 * @since       0.1
 */

/**
 * MapFilter Interface.
 *
 * @file                MapFilter/Interface.php
 */
require_once ( dirname ( __FILE__ ) . '/MapFilter/Interface.php' );

/**
 * MapFilter Null Pattern.
 *
 * @file                MapFilter/Pattern/Null.php
 */
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern/Null.php' );

/**
 * Class to filter key-value data structures.
 *
 * @class       MapFilter
 * @ingroup     gfilter
 * @package     MapFilter
 * @subpackage  Filter
 * @author      Oliver Gondža
 * @since       0.1
 */
class MapFilter implements MapFilter_Interface {

  /**
   * Pattern.
   *
   * @since     0.4
   *
   * @var       MapFilter_Pattern_Interface     $_pattern
   * @see       setPattern(), __construct()
   */
  private $_pattern = NULL;
  
  /**
   * A Used Pattern.
   *
   * @since     0.5
   *
   * @var       MapFilter_Pattern_Interface     $_usedPattern
   * @see       setPattern(), __construct()
   */
  private $_usedPattern = NULL;

  /**
   * Read data / Query candidate.
   *
   * @since     0.4
   *
   * @var       Array                           $_query
   * @see       setQuery(), __construct()
   */
  private $_query = Array ();
  
  /**
   * Determine whether the filter configuration has been filtered.
   *
   * @since     0.4
   *
   * @var       Bool    $_filtered
   * @see       _filter(), setQuery(), setPattern()
   */
  private $_filtered = FALSE;
  
  /**
   * @copyfull{MapFilter_Interface::__construct()}
   */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL,
      $query = Array ()
  ) {

    assert ( is_array ( $query ) ||  ( $query instanceof ArrayAccess ) );
    
    $this->setPattern ( $pattern );
    
    $this->setQuery ( $query );
  }

  /**
   * @copyfull{MapFilter_Interface::setPattern()}
   */
  public function setPattern ( MapFilter_Pattern_Interface $pattern = NULL) {

    $this->_filtered = FALSE;

    $this->_pattern = ( $pattern === NULL )
        ? new MapFilter_Pattern_Null ()
        : clone ( $pattern );
    
    return $this;
  }
  
  /**
   * @copyfull{MapFilter_Interface::setQuery()}
   */
  public function setQuery ( $query ) {
  
    assert ( is_array ( $query ) || ( $query instanceof ArrayAccess ) );
  
    $this->_filtered = FALSE;
  
    $this->_query = $query;
    return $this;
  }
  
  /**
   * Parse filter configuration.
   *
   * @since     0.5
   *
   * @see       fetchResult(), getResults(), getAsserts(), getFlags()
   */
  private function _filter () {
  
    if ( $this->_filtered ) {
      
      return;
    }
  
    $this->_filtered = TRUE;
  
    $this->_usedPattern = clone ( $this->_pattern );
    
    $this->_usedPattern->parse ( $this->_query );
  }
  
  /**
   * @copyfull{MapFilter_Interface::fetchResult()}
   */
  public function fetchResult () {
  
    $this->_filter ();
  
    return $this->_usedPattern;
  }
  
  /**
   * @copyfull{MapFilter_Interface::getResults()}
   */
  public function getResults () {

    return $this->fetchResult ()->getResults ();
  }
  
  /**
   * @copyfull{MapFilter_Interface::getAsserts()}
   */
  public function getAsserts () {
  
    return $this->fetchResult ()->getAsserts ();
  }
  
  /**
   * @copyfull{MapFilter_Interface::getFlags()}
   */
  public function getFlags () {
  
    return $this->fetchResult ()->getFlags();
  }
  
  /**
   * Alias for getResults().
   *
   * Used for maintain backward compatibility.
   *
   * @since             0.1
   *
   * @deprecated        since 0.2
   * @see               getResults()
   */
  public function fetch () {
  
    $level = ( defined ( 'E_USER_DEPRECATED' ) )
        ? E_USER_DEPRECATED
        : E_USER_NOTICE
    ;

    $message = sprintf (
        '%s::%s () is deprecated. Use %s::getResults () instead.',
        __CLASS__, __FUNCTION__, __CLASS__
    );
  
    trigger_error ( $message, $level );
    
    return $this->getResults ();
  }
  
  /**
   * Just here for maintain backward compatibility.
   *
   * @note There is no replacement for this method since it has become
   * unnecessary
   *
   * @since             0.1
   *
   * @deprecated        since 0.5
   */
  public function parse () {
  
    $level = ( defined ( 'E_USER_DEPRECATED' ) )
        ? E_USER_DEPRECATED
        : E_USER_NOTICE
    ;

    $message = sprintf (
        '%s::%s () is deprecated.',
        __CLASS__, __FUNCTION__
    );
  
    trigger_error ( $message, $level );
    
    $this->_filter ();
  }
}
