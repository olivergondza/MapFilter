<?php
/**
* Class to filter associative arrays
*
* @since	0.1
* 
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	Filter
*/

/**
* MapFilter Interface
*
* @file		MapFilter/Interface.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Interface.php' );

/**
* MapFilter Null Pattern
*
* @file		MapFilter/Pattern/Null.php
*/
require_once ( dirname ( __FILE__ ) . '/MapFilter/Pattern/Null.php' );

/**
* Class to filter associative arrays.
*
* @since	0.1
*
* @class	MapFilter
* @author	Oliver Gondža
* @package	MapFilter
* @subpackage	Filter
*/
class MapFilter implements MapFilter_Interface {

  /**
  * Pattern.
  *
  * @since	0.4
  *
  * @var	MapFilter_Pattern_Interface	$pattern
  * @see	setPattern(), __construct()
  */
  private $pattern = NULL;
  
  /**
  * Used Pattern.
  *
  * @since	0.5
  *
  * @var	MapFilter_Pattern_Interface	$usedPattern
  * @see	setPattern(), __construct()
  */
  private $usedPattern = NULL;

  /**
  * Read data / Query candidate
  *
  * @since	0.4
  *
  * @var	Array	$query
  * @see	setQuery(), __construct()
  */
  private $query = Array ();
  
  /**
  * Determine whether the filter configuration has been filtered
  *
  * @since	0.4
  *
  * @var	Bool	$filtered
  * @see	filter(), setQuery(), setPattern()
  */
  private $filtered = FALSE;
  
  /**
  * @copyfull{MapFilter_Interface::__construct()}
  */
  public function __construct (
      MapFilter_Pattern_Interface $pattern = NULL,
      Array $query = Array ()
  ) {
    
    $this->setPattern ( $pattern );
    
    $this->setQuery ( $query );
  }

  /**
  * @copyfull{MapFilter_Interface::setPattern()}
  */
  public function setPattern ( MapFilter_Pattern_Interface $pattern = NULL) {

    $this->filtered = FALSE;

    $this->pattern = ( $pattern === NULL )
        ? new MapFilter_Pattern_Null ()
        : clone ( $pattern );
    
    return $this;
  }
  
  /**
  * @copyfull{MapFilter_Interface::setQuery()}
  */
  public function setQuery ( Array $query ) {
  
    $this->filtered = FALSE;
  
    $this->query = $query;
    return $this;
  }
  
  /**
  * Parse filter configuration.
  *
  * @since	0.5
  *
  * @see	fetchResult(), getResults(), getAsserts(), getFlags()
  */
  private function filter () {
  
    if ( $this->filtered ) {
      
      return;
    }
  
    $this->filtered = TRUE;
  
    $this->usedPattern = clone ( $this->pattern );
    
    $this->usedPattern->parse ( $this->query );
  }
  
  /**
  * @copyfull{MapFilter_Interface::fetchResult()}
  */
  public function fetchResult () {
  
    $this->filter ();
  
    return $this->usedPattern;
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
  * Alias for getResults(). Just here for maintain backward compatibility
  *
  * @since	0.1
  *
  * @deprecated	since 0.2
  * @see	getResults()
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
  * Just here for maintain backward compatibility
  *
  * @note There is no replacement for this method since it has become
  * unnecessary
  *
  * @since	0.1
  *
  * @deprecated	since 0.5
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
    
    $this->filter ();
  }
}
