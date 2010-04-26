<?php
/**
* MapFilter Interface
* 
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* @package MapFilter
*/
interface MapFilter_Interface {
  
  public function __construct (
      MapFilter_Pattern $pattern = NULL,
      Array $query = Array ()
  );

  public function setPattern ( MapFilter_Pattern $pattern );
  
  public function setQuery ( Array $query );
  
  public function parse ();
  
  public function getResults ();
  public function fetch ();
  
  public function getAsserts ();
  
  public function getFlags ();
}
