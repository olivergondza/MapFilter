<?php
/**
* Every tree node that has an attribute must implement this interface
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
interface MapFilter_Pattern_Tree_Attribute_Interface {

  public function __toString ();
  
  public function setAttribute ( $attribute );
}