<?php
/**
* MapFilter_Pattern Interface
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
interface MapFilter_Pattern_Interface {

  public function __construct ( MapFilter_Pattern_Tree $patternTree );

  public function getTree ();

  public static function load ( $XMLSource );
  
  public static function fromFile ( $url );
  
  public function satisfy ( MapFilter_Pattern_SatisfyParam $param );
  
  public function pickUp ( MapFilter_Pattern_PickUpParam $param );

  public function __clone ();
}