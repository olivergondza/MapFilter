<?php
/**
* MapFilter_TreePattern::satisfy Parameter Object
*
* @author	Oliver Gondža
* @link		http://github.com/olivergondza/MapFilter
* @license	GNU GPLv3
* @copyright	2009-2010 Oliver Gondža
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/

/** @cond	INTERNAL */

/**
* @file		3rdParty/ParameterObject.php
*/
require_once ( dirname ( __FILE__ ) . '/../../3rdParty/ParameterObject.php' );

/** @endcond */

/**
* MapFilter_TreePattern::Satisfy Parameter Object
*
* @class	MapFilter_TreePattern_SatisfyParam
* @ingroup	gtreepattern
* @package	MapFilter
* @subpackage	TreePattern
* @since	0.3
*/
final class MapFilter_TreePattern_SatisfyParam extends ParameterObject
{

  /**
  * User query.
  *
  * @since	0.3
  *
  * Input variable with user query.
  *
  * @var	Array		$query
  */
  public $query;
  
  /**
  * Parsing asserts.
  *
  * @since	0.3
  *
  * Out variable to get raised user asserts.
  *
  * @var	Array		$asserts
  */
  public $asserts;
  
  /**
  * Parsing flags.
  *
  * @since	0.3
  *
  * Out variables to get sat user flags.
  *
  * @var	Array		$flags
  */
  public $flags;
}