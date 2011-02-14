<?php
/**
 * Require tested class
 */
require_once PHP_MAPFILTER_DIR . '/TreePattern/Tree/Replacer.php';

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Replacer
 */
class MapFilter_Test_Unit_TreePattern_Replacer extends
    PHPUnit_Framework_TestCase
{
  
  public static function provideInvalidReplacement () {
  
    return Array (
        Array ( '' ),
        Array ( '/^asdf$/' ),
        Array ( 's/^asdf$/' ),
        Array ( '^asdf$/asdf' ),
        Array ( '/^asdf$/asdf' ),
        Array ( '^asdf$/asdf/' ),
        Array ( '/^asdf$/ghjk/lm/' ),
        Array ( 'asdf/asdf' ),
        Array ( '////' ),
        Array ( '/^$///' ),
        Array ( '/^/$//' ),
    );
  }
  
  /**
   * @dataProvider      provideInvalidReplacement
   * @expectedException MapFilter_TreePattern_Tree_Replacer_InvalidStructureException
   */
  public static function testInvalidReplacement ( $replacement ) {
  
    new MapFilter_TreePattern_Tree_Replacer ( $replacement );
  }
  
  public static function provideReplacement () {
  
    return Array (
        Array ( '/^a$/b/' ),
        Array ( 's/^a$/b/' ),
        Array ( 's/^\/$/b/' ),
    );
  }
  
  /**
   * @dataProvider      provideReplacement
   */
  public static function testReplacement ( $replacement ) {
  
    new MapFilter_TreePattern_Tree_Replacer ( $replacement );
  }
}
