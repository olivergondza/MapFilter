<?php
/**
* User Tests using cat.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Cat
*/
class MapFilter_Test_User_TreePattern_Cat extends PHPUnit_Framework_TestCase {

  /*@}*/
  public static function provideParseCat () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'help' => '' ),
            Array ( 'help' => '' ),
            Array ( 'show_help' ),
            Array ()
        ),
        Array (
            Array ( 'version' => '' ),
            Array ( 'version' => '' ),
            Array ( 'show_version' ),
            Array ()
        ),
        Array (
            Array ( 'no_such_option' => '' ),
            Array (),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'no_such_option' => '', 'version' => '' ),
            Array ( 'version' => '' ),
            Array ( 'show_version' )
        ),
        Array (
            Array ( 'no_such_option' => '', 'b' => '' ),
            Array ( 'b' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'u' => '' ),
            Array (),
            Array (),
            Array ( 'deprecated' => '' ),
        ),
        Array (
            Array (
                'A' => '', 'b' => '', 'E' => '', 'n' => '',
                's' => '', 'T' => '', 'v' => ''
            ),
            Array (
                'b' => '', 'E' => '', 'n' => '',
                's' => '', 'T' => '', 'v' => ''
            ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'A' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-all' => '' ),
            Array ( 'v' => '', 'E' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'e' => '' ),
            Array ( 'v' => '', 'E' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 't' => '' ),
            Array ( 'v' => '', 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'number-nonblank' => '' ),
            Array ( 'b' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-ends' => '' ),
            Array ( 'E' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'number' => '' ),
            Array ( 'n' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'squeeze-blank' => '' ),
            Array ( 's' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-tabs' => '' ),
            Array ( 'T' => '' ),
            Array ( 'perform_action' )
        ),
        Array (
            Array ( 'show-nonprinting' => '' ),
            Array ( 'v' => '' ),
            Array ( 'perform_action' )
        ),
    );
  }
  /*@}*/
  
  /**
   * @dataProvider      provideParseCat
   */
  public static function testParseCat (
      $query, $result, $flags, $asserts = Array ()
  ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::CAT
        ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->fetchResult ()->getResults ()
    );
    
    self::assertEquals (
        $flags,
        $filter->fetchResult ()->getFlags ()
    );
    
    self::assertEquals (
        $asserts,
        $filter->fetchResult ()->getAsserts ()
    );
  }
}