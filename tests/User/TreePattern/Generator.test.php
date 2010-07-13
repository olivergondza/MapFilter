<?php
/**
 * User Tests using generator.xml
 */  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
 * @group	User
 * @group	User::TreePattern
 * @group	User::TreePattern::Generator
 */
class MapFilter_Test_User_TreePattern_Generator extends
    PHPUnit_Framework_TestCase
{


  public static function provideGenerator () {
  
    return Array (
        // No source specified
        Array (
            Array (),
            Array (),
            Array (),
            Array ( 'no_source' => 'no_source' )
        ),
        // Default 'output_type' and its 'stylesheet' assigned
        Array (
            Array ( 'input' => 'a.out' ),
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => 'default.css' ),
            Array (),
            Array ()
        ),
        // Valid set
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => Array ( 'a.css', 'b.css' ) ),
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => Array ( 'a.css', 'b.css' ) ),
            Array (),
            Array ()
        ),
        // Default 'stylesheet' for the 'html' and the default 'extension' for 'man' assigned.
        Array (
            Array ( 'input' => 'a.out', 'output_format' => Array ( 'html', 'man' ) ),
            Array ( 'input' => 'a.out', 'output_format' => Array ( 'html', 'man' ), 'stylesheet' => 'default.css', 'extension' => '3' ),
            Array (),
            Array ()
        ),
        // Set the default value if no 'output_type' is valid
        Array (
            Array ( 'input' => 'a.out', 'output_format' => Array ( 'pdf', 'text' ) ),
            Array ( 'input' => 'a.out', 'output_format' => Array ( 'html' ), 'stylesheet' => 'default.css' ),
            Array (),
            Array ()
        ),
        // Set the default value if no 'stylesheet' is valid
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => Array ( 'a.sss', 'b.sss' ) ),
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => Array ( 'default.css' ) ),
            Array (),
            Array ()
        ),
        // Replace invalid 'stylesheet' by default value
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => 'a.sss' ),
            Array ( 'input' => 'a.out', 'output_format' => 'html', 'stylesheet' => 'default.css' ),
            Array (),
            Array ()
        ),
        // Assign default extension
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '3' ),
            Array (),
            Array ()
        ),
        // Valid set
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '2' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '2' ),
            Array (),
            Array ()
        ),
        // Replace invalid 'extension' value by default value.
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => 'info' ),
            Array ( 'input' => 'a.out', 'output_format' => 'man', 'extension' => '3' ),
            Array (),
            Array ()
        ),
        // Assign default values for those attributes that were not stated.
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'default.dtd' ),
            Array (),
            Array ()
        ),
        // AssInge default values for those attributes that were not stated.
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'dtd' => 'a.dtd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'a.dtd' ),
            Array (),
            Array ()
        ),
        // Replace invalid 'dtd' by default value
        Array (
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'dtd' => 'a.ddd' ),
            Array ( 'input' => 'a.out', 'output_format' => 'xml', 'stylesheet' => 'default.css', 'dtd' => 'default.dtd' ),
            Array (),
            Array ()
        ),
        // Truncate disallowed attribute 'extension' and set default values for chosen output type
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
        // Enable multiple case competence
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
   */
  public static function testGenerator (
      $query, $result, $flags, $asserts
  ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::GENERATOR
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
}