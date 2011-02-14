<?php
/**
 * Direction
 */
 
require_once PHP_MAPFILTER_DIR . '/TreePattern.php';
require_once PHP_MAPFILTER_DIR . '/Pattern/Null.php';

/**
 * @group	User
 * @group	User::TreePattern
 * @group	User::TreePattern::Direction
 */
class MapFilter_Test_User_TreePattern_Direction extends
    PHPUnit_Framework_TestCase
{
  
  /**@{*/
  public static function provideParse () {
  
    return Array (
        // Single direction
        Array (
            Array (
                'top' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
            Array (
                'top' => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Two compatible directions
        Array (
            Array (
                'top'  => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'  => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Two incompatible directions; one will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
            ),
        ),
        // Three directions; one will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        // Four directions; two will be trimmed
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
        Array (
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'left'   => Array ( 'unit' => 'meter', 'count' => 1 ),
                'bottom' => Array ( 'unit' => 'yard',  'count' => 1 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
            Array (
                'top'    => Array ( 'unit' => 'meter', 'count' => 2 ),
                'right'  => Array ( 'unit' => 'yard',  'count' => 1 ),
            ),
        ),
    );
  }
  /**@}*/
  
  /**
   * test parse
   *
   * @dataProvider      provideParse
   */
  public static function testParse ( $query, $result ) {
  
    $pattern = MapFilter_TreePattern::fromFile (
        PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DIRECTION
    );

    $filter = new MapFilter (
        $pattern,
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
  }
}
