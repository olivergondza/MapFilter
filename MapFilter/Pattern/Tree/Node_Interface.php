<?php
/**
* MapFilter_Pattern_Tree_Node Interface
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* MapFilter_Pattern_Tree_Node Interface
* @package MapFilter
*/
interface MapFilter_Pattern_Tree_Node_Interface {

  public function setContent ( Array $content );

  public function &getContent ();
  
  public function __clone ();
}
