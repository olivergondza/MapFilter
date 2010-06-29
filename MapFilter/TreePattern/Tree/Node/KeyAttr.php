<?php
/**
 * Alias for KeyAttr Pattern leaf.
 *
 * @author      Oliver Gondža
 * @link        http://github.com/olivergondza/MapFilter
 * @license     GNU GPLv3
 * @copyright   2009-2010 Oliver Gondža
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 * @deprecated  since 0.5.2
 * @see         MapFilter_TreePattern_Tree_Leaf_KeyAttr
 */

/**
 * @file        MapFilter/TreePattern/Tree/Leaf/KeyAttr.php
 */
require_once ( dirname ( __FILE__ ) . '/../Leaf/KeyAttr.php' );

/**
 * Alias for MapFilter pattern tree KeyAttr leaf.
 *
 * @deprecated  since 0.5.2
 * @see         MapFilter_TreePattern_Tree_Leaf_KeyAttr
 *
 * @class       MapFilter_TreePattern_Tree_Node_KeyAttr
 * @ingroup     gtreepattern
 * @package     MapFilter
 * @subpackage  TreePattern
 * @since       0.4
 * 
 */
final class MapFilter_TreePattern_Tree_Node_KeyAttr extends
    MapFilter_TreePattern_Tree_Leaf_KeyAttr
{

  public function __construct () {
  
    $level = ( defined ( 'E_USER_DEPRECATED' ) )
        ? E_USER_DEPRECATED
        : E_USER_NOTICE
    ;

    $message = sprintf (
        '%s is deprecated. Use MapFilter_TreePattern_Tree_Leaf_KeyAttr instead.',
        __CLASS__
    );
  
    trigger_error ( $message, $level );
  }
}
