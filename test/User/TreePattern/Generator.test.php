<?php
/**
* User Tests using generator.xml
*/  

/**
 * Require tested class
 */
require_once ( MAP_FILTER_TEST_DIR . '/../MapFilter.php' );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Generator
*/
class TestUserGenerator extends PHPUnit_Framework_TestCase {


  public static function provideGenerator () {
  
    return Array (
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_source' => 'no_source' )
        ),
        Array (
            Array ( 'input' => 'a.out' ),
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => 'default.css'
            ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => Array ( 'a.css', 'b.css' )
            ),
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => Array ( 'a.css', 'b.css' )
            ),
            Array (),
            Array ()
        ),
        3 => Array (
            Array (
                'input' => 'a.out', 'output_format' => Array ( 'html', 'man' )
            ),
            Array (
                'input' => 'a.out', 'output_format' => Array ( 'html', 'man' ),
                'stylesheet' => 'default.css', 'extension' => '3'
            ),
            Array (),
            Array ()
        ),
/** Set the default value just once */
        Array (
            Array (
                'input' => 'a.out', 'output_format' => Array ( 'pdf', 'text' )
            ),
            Array (
                'input' => 'a.out', 'output_format' => Array ( 'html' ),
                'stylesheet' => 'default.css'
            ),
            Array (),
            Array ()
        ),
/** Set the default value just once */
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => Array ( 'a.sss', 'b.sss' )
            ),
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => Array ( 'default.css' )
            ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => 'a.sss'
            ),
            Array ( 'input' => 'a.out', 'output_format' => 'html',
                    'stylesheet' => 'default.css'
            ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '3' ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '2' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man',
                    'extension' => '2'
            ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => 'info' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man',
                    'extension' => '3'
            ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'default.dtd' ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'dtd' => 'a.dtd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'a.dtd' ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'dtd' => 'a.ddd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'default.dtd' ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'extension' => '1' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'default.dtd' ),
            Array (),
            Array ()
        ),
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'extension' => '1', 'dtd' => 'a.dtd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'a.dtd' ),
            Array (),
            Array ()
        ),
/* Enable multiple case competence */
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'a.css', 'dtd' => 'a.dtd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'a.css', 'dtd' => 'a.dtd' ),
            Array (),
            Array ()
        ),
    );
  }
  
  /**
   * @dataProvider      provideGenerator
   * @group             current
   */
  public static function testGenerator (
      $query, $result, $flags, $asserts
  ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile ( Test_Source::GENERATOR ),
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
}