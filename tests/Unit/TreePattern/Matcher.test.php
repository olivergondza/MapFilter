<?php
/**
 * Require tested class
 */
require_once ( PHP_MAPFILTER_DIR . '/TreePattern/Tree/Matcher.php' );

/**
 * @group	Unit
 * @group	Unit::TreePattern
 * @group	Unit::TreePattern::Matcher
 */
class MapFilter_Test_Unit_TreePattern_Matcher extends
    PHPUnit_Framework_TestCase
{
  
  public static function provideUnsanitizedEquality () {
  
    return Array (
        Array ( 'hello', '/^hello$/' ),
        Array ( 'hell/o', '/^hell\/o$/' ),
        Array ( '/hello/m', '/hello/m' ),
        Array ( '/hello\/?/imsxeADSUXu', '/hello\/?/imsxeADSUXu' ),
        Array ( '?hello\?*?imsxeADSUXu', '?hello\?*?imsxeADSUXu' ),
        Array ( '`hello\`?`imsxeADSUXu', '`hello\`?`imsxeADSUXu' ),
        Array ( '!hello\!?!imsxeADSUXu', '!hello\!?!imsxeADSUXu' ),
        Array ( '@hello\@?@imsxeADSUXu', '@hello\@?@imsxeADSUXu' ),
        Array ( '#hello\#?#imsxeADSUXu', '#hello\#?#imsxeADSUXu' ),
        Array ( '$hello\$?$imsxeADSUXu', '$hello\$?$imsxeADSUXu' ),
        Array ( '%hello\%?%imsxeADSUXu', '%hello\%?%imsxeADSUXu' ),
        Array ( '^hello\^?^imsxeADSUXu', '^hello\^?^imsxeADSUXu' ),
        Array ( '&hello\&?&imsxeADSUXu', '&hello\&?&imsxeADSUXu' ),
        Array ( '*hello\*?*imsxeADSUXu', '*hello\*?*imsxeADSUXu' ),
        Array ( '+hello\+?+imsxeADSUXu', '+hello\+?+imsxeADSUXu' ),
        Array ( '-hello\-?-imsxeADSUXu', '-hello\-?-imsxeADSUXu' ),
        Array ( ';hello\;?;imsxeADSUXu', ';hello\;?;imsxeADSUXu' ),
        Array ( ',hello\,?,imsxeADSUXu', ',hello\,?,imsxeADSUXu' ),
        Array ( '.hello\.?.imsxeADSUXu', '.hello\.?.imsxeADSUXu' ),
        Array ( '', '/^$/' ),
        Array ( '^hello$', '/^^hello$$/' ),
        Array ( '///', '///' ),
    );
  }
  
  /**
   * @dataProvider      provideUnsanitizedEquality
   */
  public static function testUnsanitizedEquality ( $sanitized, $unsanitized ) {
  
    $sanitized = new MapFilter_TreePattern_Tree_Matcher ( $sanitized );
    $unsanitized = new MapFilter_TreePattern_Tree_Matcher ( $unsanitized );

    self::assertEquals ( $sanitized, $unsanitized );
    
    self::assertEquals (
        $sanitized->match ( 'hello' ),
        $unsanitized->match ( 'hello' )
    );
  }
}
