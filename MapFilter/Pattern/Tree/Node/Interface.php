<?php
/**
* MapFilter_Pattern_Tree_Node Interface
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.4
*/

/**
* MapFilter_Pattern_Tree_Node Interface
*
* @class	MapFilter_Pattern_Tree_Node_Interface
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.4
*/
interface MapFilter_Pattern_Tree_Node_Interface {

  /**
  * @copyfull{MapFilter_Pattern_Tree_Interface::setContent()}
  */
  public function setContent ( Array $content );

  /**
  * Get node followers reference
  *
  * @since	0.4
  *
  * @return	Array	Node followers reference
  */
  public function &getContent ();
}
