<?php
/**
* User Tests using duration.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Duration
*/
class MapFilter_Test_User_TreePattern_Duration extends
    PHPUnit_Framework_TestCase
{

    public static function provideDuration () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array (
                'no_beginning_time' => 'no_beginning_time', 'no_start_hour' => 'no_start_hour'
            )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0 ),
            Array (),
            Array (),
            Array ( 'no_duration_hour' => 'no_duration_hour', 'no_end_hour' => 'no_end_hour', 'no_termination_time' => 'no_termination_time' )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 0, 'start_second' => 'now' ),
            Array (),
            Array (),
            Array ( 'no_beginning_time' => 'no_beginning_time', 'no_start_second' => 'now' )
        ),
        Array (
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59,
            ),
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'end_hour' => 1, 'end_minute' => 59, 'end_second' => 59,
            ),
            Array ( 'duration', 'ending_time' ),
            Array ()
        ),
        Array (
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59,
            ),
            Array (
                'start_hour' => 0, 'start_minute' => 0, 'start_second' => 0,
                'duration_hour' => 1, 'duration_minute' => 59, 'duration_second' => 59,
            ),
            Array ( 'duration', 'duration_time' ),
            Array ( 'no_end_hour' => 'no_end_hour' )
        ),
        Array (
            Array ( 'start_hour' => -1 ),
            Array (),
            Array (),
            Array ( 'no_beginning_time' => 'no_beginning_time', 'no_start_hour' => -1 )
        ),
        Array (
            Array ( 'start_hour' => 0, 'start_minute' => 60 ),
            Array (),
            Array (),
            Array ( 'no_beginning_time' => 'no_beginning_time', 'no_start_minute' => 60 )
        )
    );
  }
  
  /**@{*/
  /**
   * @dataProvider      provideDuration
   */
  public static function testDuration ( $query, $result, $flags, $asserts ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults ()
    );
    
    self::assertEquals (
        array_diff ( $flags, $filter->getFlags () ),
        array_diff ( $filter->getFlags (), $flags )
    );
    
    self::assertEquals (
         $asserts,
         $filter->getAsserts ()
    );
  }
  /**@}*/
  
  /**{@*/
  /**
    * @dataProvider     provideDuration
    */
  public static function testDurationArrayAccess (
      $query, $result, $flags, $asserts
  ) {
  
    $filterObject = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        new ArrayObject ( $query )
    );
    
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        $query
    );
    
    self::assertEquals (
        $filterObject->getResults (),
        $filter->getResults ()
    );
    
    self::assertEquals (
        $filterObject->getFlags (),
        $filter->getFlags ()
    );
    
    self::assertEquals (
        $filterObject->getAsserts (),
        $filter->getAsserts ()
    );
  }
  /**@}*/
  
  /**{@*/
  /**
   * @dataProvider      provideDuration
   */
  public static function testDurationByFetchResult (
      $query, $result, $flags, $asserts
  ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::DURATION
        ),
        $query
    );
    
    self::assertEquals (
        $filter->fetchResult ()->getResults (),
        $filter->getResults ()
    );
    
    self::assertEquals (
        $filter->fetchResult ()->getFlags (),
        $filter->getFlags ()
    );
    
    self::assertEquals (
        $filter->fetchResult ()->getAsserts (),
        $filter->getAsserts ()
    );
  }
  /**@}*/
}