<?php

error_reporting ( ( E_ALL | E_STRICT ) );

if ( !is_readable ( '../MapFilter.php' ) ) {

  define ( 'PHP_MAPFILTER_DIR', 'PHP/MapFilter/' );
  define ( 'PHP_MAPFILTER_CLASS', 'PHP/MapFilter.php' );
} else {

  define ( 'PHP_MAPFILTER_DIR', dirname ( __FILE__ ) . '/../MapFilter/' );
  define ( 'PHP_MAPFILTER_CLASS', dirname ( __FILE__ ) . '/../MapFilter.php' );
}

define ( 'PHP_MAPFILTER_TEST_DIR', dirname ( __FILE__ ) );

class MapFilter_Test_Sources {
  const LOCATION = '/sources/location.xml';
  const LOGIN = '/sources/login.xml';
  const COFFEE_MAKER = '/sources/coffee_maker.xml';
  const CAT = '/sources/cat.xml';
  const ACTION = '/sources/action.xml';
  const FILTER = '/sources/filter.xml';
  const DURATION = '/sources/duration.xml';
  const GENERATOR = '/sources/generator.xml';
  const DIRECTION = '/sources/direction.xml';
  const PATHWAY = '/sources/pathway.xml';
  const PARSEINIFILE_INI = '/sources/parse_ini_file.ini';
  const PARSEINIFILE_XML = '/sources/parse_ini_file.xml';
}
