<?php
/**
* MapFilter_Pattern::satisfy Parameter Object
*
* @author Oliver Gondža
* @link http://github.com/olivergondza/MapFilter
* @license GNU GPLv3
* @copyright 2009-2010 Oliver Gondža
* @package MapFilter
*/

/**
* Include ParameterObject
*/
require_once ( dirname ( __FILE__ ) . '/../ParameterObject.php' );

/**
* @package MapFilter
*/
final class MapFilter_Pattern_SatisfyParam
    extends MapFilter_ParameterObject
{

  /**
  * In. User query
  * @var Array ( Attribute => Value )
  */
  public $query;
  
  /**
  * Out. Parsing asserts
  * @var Array ( String )
  */
  public $asserts;
  
  /**
  * Out. Parsing flags
  * @var Array ( String )
  */
  public $flags;
}