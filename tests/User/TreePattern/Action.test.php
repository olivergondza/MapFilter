<?php
/**
* User Tests using action.xml
*/  

/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_CLASS );

/**
* @group	User
* @group	User::TreePattern
* @group	User::TreePattern::Action
*/
class MapFilter_Test_User_TreePattern_Action extends
    PHPUnit_Framework_TestCase
{

  public static function provideParseAction () {
  
    return Array (
        Array (
            Array (),
            Array ()
        ),
        Array (
            Array ( 'action' => "noSuchAction" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'id' => 42 ),
            Array ( 'action' => "delete", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "delete", 'file_name' => "myFile" ),
            Array ( 'action' => "delete", 'file_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "delete", 'wrongAttr' => NULL ),
            Array ()
        ),
        Array (
            Array ( 'action' => "delete", 'id' => 42, 'file_name' => "myFile" ),
            Array ( 'action' => "delete", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "create", 'new_file' => "fileObj", 'new_name' => "myFile" ),
            Array ( 'action' => "create", 'new_file' => "fileObj", 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "create", 'new_file' => "fileObj" ),
            Array ( 'action' => "create", 'new_file' => "fileObj" )
        ),
        Array (
            Array ( 'action' => "rename", 'id' => 42, 'new_name' => "myFile" ),
            Array ( 'action' => "rename", 'id' => 42, 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "rename", 'old_name' => "myFile", 'new_name' => "myFile" ),
            Array ( 'action' => "rename", 'old_name' => "myFile", 'new_name' => "myFile" )
        ),
        Array (
            Array ( 'action' => "rename", 'new_name' => "myFile" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "rename", 'old_name' => "myFile" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "rename" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "report", 'id' => 42 ),
            Array ( 'action' => "report", 'id' => 42 )
        ),
        Array (
            Array ( 'action' => "report" ),
            Array ()
        ),
        Array (
            Array ( 'action' => "report", 'file_name' => "myName" ),
            Array ()
        )
    );
  }
  
  /**
   * Test parse external source and validate
   * @dataProvider      provideParseAction
   */
  public static function testParseAction ( $query, $result ) {
  
    $filter = new MapFilter (
        MapFilter_TreePattern::fromFile (
            PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::ACTION
        ),
        $query
    );
    
    self::assertEquals (
        $result,
        $filter->getResults()
    );
  }
}