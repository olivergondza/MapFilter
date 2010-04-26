<?php
/**
* MapFilter_Pattern::pickUp Parameter Object
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include PArameterObject
*/
require_once ( dirname ( __FILE__ ) . '/../ParameterObject.php' );

/**
* @package MapFilter
*/
final class MapFilter_Pattern_PickUpParam
    extends MapFilter_ParameterObject
{

  /**
  * Out parameter for parsing results.
  * @var Array ( Attribute => Value )
  */
  public $data;
}
