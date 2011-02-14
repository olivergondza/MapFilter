<?php
/**
 * Pathway
 */

require_once PHP_MAPFILTER_CLASS;

/**
 * @group	User
 * @group	User::TreePattern
 * @group	User::TreePattern::Pathway
 */
class MapFilter_Test_User_TreePattern_Pathway extends
    PHPUnit_Framework_TestCase
{
  
  public static function provideParse () {
  
    return Array (
        // Empty; Mandatory attribute "steps" is missing
        Array (
            Array (),
            Array ()
        ),
        // Just turn
        Array (
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' )
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' )
                )
            )
        ),
        // Just walk
        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    )
                )
            )
        ),
        // Walk and turn
        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                    
                )
            )
        ),
        // Turn and walk
        Array (
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    )
                )
            )
        ),

        Array (
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            ),
            Array ( 'steps' =>
                Array (
                    Array (
                        'walk' => Array ( 'yards' => 1 )
                    ),
                    Array ( 'turn' => 'left' ),
                    Array (
                        'walk' => Array ( 'metres' => 1 )
                    ),
                )
            )
        ),
    );
  }
  
  /**
   * test parse
   *
   * @dataProvider      provideParse
   */
  public static function testParse ( $query, $result ) {

    $pattern = MapFilter_TreePattern::fromFile (
        PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::PATHWAY
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
