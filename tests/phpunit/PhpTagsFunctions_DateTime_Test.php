<?php

namespace PhpTags;

/**
 * @coversNothing
 */
class PhpTagsFunctions_DateTime_Test extends \PHPUnit\Framework\TestCase {

	public static function setUpBeforeClass(): void {
		if ( Renderer::$needInitRuntime ) {
			wfDebug( 'PHPTags: run hook PhpTagsRuntimeFirstInit ' . __FILE__ );
			\MediaWiki\MediaWikiServices::getInstance()->getHookContainer()->run( 'PhpTagsRuntimeFirstInit' );
			Hooks::loadData();
			Runtime::$loopsLimit = 1000;
			Renderer::$needInitRuntime = false;
		}
	}

	public function testRun_constant_1() {
		$this->assertEquals(
				[ SUNFUNCS_RET_DOUBLE ],
				Runtime::runSource( 'echo SUNFUNCS_RET_DOUBLE;' )
				);
	}

	public function testRun_constant_2() {
		$this->assertEquals(
				[ DATE_ATOM ],
				Runtime::runSource( 'echo DATE_ATOM;' )
				);
	}

	public function testRun_checkdate_1() {
		$this->assertEquals(
				[ 'true' ],
				Runtime::runSource( 'echo checkdate(12, 31, 2000) === true ? "true" : "false";' )
			);
	}

	public function testRun_checkdate_2() {
		$this->assertEquals(
				[ 'true' ],
				Runtime::runSource( 'echo checkdate(2, 29, 2001) === false ? "true" : "false";' )
			);
	}

	public function testRun_checkdate_3() {
		$this->assertEquals(
				[ 'true' ],
				Runtime::runSource( '$func = "checkdate"; echo $func(2, 29, 2001) === false ? "true" : "false";' )
			);
	}

	public function testRun_date_create_1() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Warning:  date_create() expects parameter 1 to be string, integer given in Page on line 1</span><br />', 'false' ],
				Runtime::runSource( '$date = date_create(5); echo $date === false ? "false" : "not false";', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_1() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to undefined function DateTime() in Page on line 1</span><br />' ],
				Runtime::runSource( '$date = DateTime(5);', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_2() {
		$this->assertEquals(
				Runtime::runSource( '$date = new DateTimeUndefined(5);', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::FATAL_CLASS_NOT_FOUND, 'DateTimeUndefined', 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_3() {
		$return = Runtime::runSource( '$date = new DateTime(5);', [ 'Page' ] );
		$this->assertStringStartsWith( '<span class="error">', $return[0] );
	}

	public function testRun_DateTime_exception_4() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to undefined method DateTime->undefined() in Page on line 1</span><br />' ],
				Runtime::runSource( '$date = new DateTime(); $date->undefined();', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_5() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to undefined function date_create_undefined() in Page on line 1</span><br />' ],
				Runtime::runSource( '$date = date_create_undefined();', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_6() {
		$this->assertEquals(
				Runtime::runSource( 'new DateTime() + 5;', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_OBJECT_CONVERTED, [ 'DateTime', 'int' ], 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_7() {
		$this->assertEquals(
				Runtime::runSource( '5 + new DateInterval("P2Y4DT6H8M");', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_OBJECT_CONVERTED, [ 'DateInterval', 'int' ], 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_8() {
		$this->assertEquals(
				Runtime::runSource( 'new DateTime() + new DateInterval("P2Y4DT6H8M");', [ 'Page' ] ),
				[
					(string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_OBJECT_CONVERTED, [ 'DateTime', 'int' ], 1, 'Page' ),
					(string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::NOTICE_OBJECT_CONVERTED, [ 'DateInterval', 'int' ], 1, 'Page' )
				]
			);
	}

	public function testRun_DateTime_exception_9() {
		$this->assertEquals(
				Runtime::runSource( 'new DateTime() . 5;', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::FATAL_OBJECT_COULD_NOT_BE_CONVERTED, [ 'DateTime', 'string' ], 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_10() {
		$this->assertEquals(
				Runtime::runSource( '5 . new DateInterval("P2Y4DT6H8M");', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::FATAL_OBJECT_COULD_NOT_BE_CONVERTED, [ 'DateInterval', 'string' ], 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_11() {
		$this->assertEquals(
				Runtime::runSource( 'new DateTime() . new DateInterval("P2Y4DT6H8M");', [ 'Page' ] ),
				[
					(string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::FATAL_OBJECT_COULD_NOT_BE_CONVERTED, [ 'DateTime', 'string' ], 1, 'Page' ),
				]
			);
	}

	public function testRun_DateTime_exception_12() {
		$this->assertEquals(
				[
					'<span class="error">PhpTags Warning:  date_format() expects parameter 1 to be DateTime, integer given in Page on line 1</span><br />',
					false
				],
				Runtime::runSource( 'echo date_format(5, "Y-m-d");', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_13() {
		$this->assertEquals(
				[
					'<span class="error">PhpTags Warning:  date_format() expects parameter 2 to be string, DateTime given in Page on line 1</span><br />',
					false
				],
				Runtime::runSource( 'echo date_format( new DateTime(), new DateTime() );', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_14() {
		$this->assertEquals(
				[
					'<span class="error">PhpTags Warning:  date_format() expects exactly 2 parameter, 3 given in Page on line 1</span><br />',
					false
				],
				Runtime::runSource( 'echo date_format(new DateTime(), "Y-m-d", 5);', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_15() {
		$this->assertEquals(
				[
					'<span class="error">PhpTags Warning:  date_format() expects exactly 2 parameter, 1 given in Page on line 1</span><br />',
					false
				],
				Runtime::runSource( 'echo date_format(new DateTime());', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_16() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Non-static method DateTime::format() cannot be called statically in Page on line 1</span><br />' ],
				Runtime::runSource( 'echo DateTime::format("Y-m-d");', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_17() {
		$this->assertEquals(
				Runtime::runSource( 'echo new DateTime();', [ 'Page' ] ),
				[ (string)new \PhpTags\PhpTagsException( \PhpTags\PhpTagsException::FATAL_OBJECT_COULD_NOT_BE_CONVERTED, [ 'DateTime', 'string' ], 1, 'Page' ) ]
			);
	}

	public function testRun_DateTime_exception_18() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to a member function format() on a non-object in Page on line 1</span><br />' ],
				Runtime::runSource( '$foo = "DateTime"; echo $foo->format("Y-m-d");', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_19() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to undefined method DateTime::undefined() in Page on line 1</span><br />' ],
				Runtime::runSource( '$date = new DateTime(); $date::undefined();', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_exception_20() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Fatal error:  Call to undefined method DateTime::undefined() in Page on line 1</span><br />' ],
				Runtime::runSource( 'DateTime::undefined();', [ 'Page' ] )
			);
	}

	public function testRun_datetime_format_1() {
		$this->assertEquals(
				[ '2000-01-01' ],
				Runtime::runSource( '$date = date_create("2000-01-01"); echo date_format($date, "Y-m-d");' )
			);
	}

	public function testRun_datetime_format_2() {
		$this->assertEquals(
				[ '2000-01-01' ],
				Runtime::runSource( '$date = new DateTime("2000-01-01"); echo date_format($date, "Y-m-d");' )
			);
	}

	public function testRun_datetime_format_3() {
		$this->assertEquals(
				[ '2000-01-01' ],
				Runtime::runSource( '$date = new DateTime("2000-01-01"); echo $date->format("Y-m-d");' )
			);
	}

	public function testRun_datetime_add_1() {
		$this->assertEquals(
				[ '2000-01-11' ],
				Runtime::runSource( '
$date = new DateTime("2000-01-01");
$date->add(new DateInterval("P10D"));
echo $date->format("Y-m-d");' )
			);
	}

	public function testRun_datetime_add_2() {
		$this->assertEquals(
				[ '2000-01-11' ],
				Runtime::runSource( '
$method = "add";
$date = new DateTime( "2000-01-01" );
$date->$method( new DateInterval("P10D") );
echo $date->format( "Y-m-d" );' )
			);
	}

	public function testRun_DateInterval_1() {
		$this->assertEquals(
				[
					2,
					'<span class="error">PhpTags Notice:  Undefined property: DateInterval->yyyyyy in Page on line 4</span><br />',
					null,
				],
				Runtime::runSource( '
$i = new DateInterval("P2Y4DT6H8M");
echo $i->y;
echo $i->yyyyyy;', [ 'Page' ] )
			);
	}

	public function testRun_DateInterval_2() {
		$di = \DateInterval::createFromDateString( "last thursday of next month" );
		$this->assertEquals(
				Runtime::runSource( '$di = DateInterval::createFromDateString("last thursday of next month"); echo $di->d;' ),
				[ $di->d ]
			);
	}

	public function testRun_DateTimeZone_1() {
		$date = new \DateTime( "2000-01-01", new \DateTimeZone( "Pacific/Nauru" ) );
		$this->assertEquals(
				Runtime::runSource( '
$date = new DateTime("2000-01-01", new DateTimeZone("Pacific/Nauru"));
echo $date->format("Y-m-d H:i:sP");', [ 'Page' ] ),
				[ $date->format( "Y-m-d H:i:sP" ) ]
			);
	}

	public function testRun_date_date_set_1() {
		$this->assertEquals(
				[ '2001-02-03' ],
				Runtime::runSource( '
$date = date_create();
date_date_set($date, 2001, 2, 3);
echo date_format($date, "Y-m-d");' )
			);
	}

	public function testRun_date_date_set_2() {
		$this->assertEquals(
				[ '2001-02-03' ],
				Runtime::runSource( '
$date = new DateTime();
$date->setDate(2001, 2, 3);
echo $date->format("Y-m-d");' )
			);
	}

	public function testRun_DatePeriod_1() {
		$this->assertEquals(
				[ \DatePeriod::EXCLUDE_START_DATE ],
				Runtime::runSource( 'echo DatePeriod::EXCLUDE_START_DATE;' )
			);
	}

	public function testRun_DatePeriod_2() {
		$this->assertEquals(
				[ '<span class="error">PhpTags Notice:  Undefined class constant: DatePeriod::UNDEFINED in Page on line 1</span><br />', null ],
				Runtime::runSource( 'echo DatePeriod::UNDEFINED;', [ 'Page' ] )
			);
	}

	public function testRun_DateTime_1() {
		$this->assertEquals(
				[ \DateTime::ATOM ],
				Runtime::runSource( 'echo DateTime::ATOM;' )
			);
	}

	public function testRun_DateTime_2() {
		$this->assertEquals(
				[ \datetime::ATOM ],
				Runtime::runSource( 'echo datetime::ATOM;' )
			);
	}

	public function testRun_DateTime_3() {
		$return = Runtime::runSource( '$d = new dateTIME(); print_r( $d->getlastERRORS() );' );
		$this->assertEquals(
				(string)new outPrint( null, print_r( \DateTime::getLastErrors(), true ) ),
				(string)$return[0]
			);
	}

}
