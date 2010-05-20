<?php
/**
* MapFilter_Pattern::pickUp Parameter Object
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.3
*/

/**
* @file		3rdParty/ParameterObject.php
*/
require_once ( dirname ( __FILE__ ) . '/../../3rdParty/ParameterObject.php' );

/**
* MapFilter_Pattern_PickUpParam Parameter Object
*
* @class	MapFilter_Pattern_PickUpParam
* @package	MapFilter
* @subpackage	DefaultPattern
* @since	0.3
*/
final class MapFilter_Pattern_PickUpParam
    extends ParameterObject
{

  /**
  * Out parameter for parsing results.
  *
  * @since	0.3
  *
  * @var	Array	$data
  */
  public $data;
}
