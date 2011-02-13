<?php
/**
 *
 */

/**
 * Require tested class
 */
require_once PHP_MAPFILTER_CLASS;

/**
 * @group       User
 * @group       User::TreePattern
 * @group       User::TreePattern::ParseIniFile
 */
class MapFilter_Test_User_TreePattern_ParseIniFile extends
    PHPUnit_Framework_TestCase
{

  const EXPAND_SECTIONS = TRUE;
 
  /**@{*/
  public static function testParse () {
  
    $content = parse_ini_file (
        PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_INI,
        self::EXPAND_SECTIONS
    );

    $pattern = MapFilter_TreePattern::fromFile (
        PHP_MAPFILTER_TEST_DIR . MapFilter_Test_Sources::PARSEINIFILE_XML
    );
    
    $filter = new MapFilter (
        $pattern,
        $content
    );
    
    $result = $filter->fetchResult ();

    self::assertEquals (
        $content,
        $result->getResults ()
    );
  }
  /**@}*/
}