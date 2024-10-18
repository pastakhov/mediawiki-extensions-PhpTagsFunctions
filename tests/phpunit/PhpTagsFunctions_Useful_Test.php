<?php

namespace PhpTags;

/**
 * @coversNothing
 */
class PhpTagsFunctions_Useful_Test extends \PHPUnit\Framework\TestCase {

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
				[ '36' ],
				Runtime::runSource( 'echo strlen( UUID );' )
			);
	}

	public function testRun_constant_2() {
		$this->assertEquals(
				Runtime::runSource( 'echo PHPTAGS_FUNCTIONS_VERSION;' ),
				[ \ExtensionRegistry::getInstance()->getAllThings()['PhpTags Functions']['version'] ]
			);
	}

	public function testRun_uuid_create_1() {
		$this->assertEquals(
				[ '36' ],
				Runtime::runSource( 'echo strlen( uuid_create() );' )
				);
	}

	public function testRun_uuid_mw_json_encode_1() {
		$this->assertEquals(
				[ '{"foo":"bar"}' ],
				Runtime::runSource( 'echo mw_json_encode( ["foo"=>"bar"] );' )
				);
	}

	public function testRun_uuid_mw_json_decode_1() {
		$this->assertEquals(
				[ 'true' ],
				Runtime::runSource( 'echo ["foo"=>"bar"] == mw_json_decode( "{\"foo\":\"bar\"}" ) ? "true" : "false";' )
				);
	}

	public function testRun_get_args_1() {
		$this->assertEquals(
				[ 'one', 'bar' ],
				Runtime::runSource( '$tmp = get_args(); echo $tmp[1], $tmp["foo"];', [ 'TestPage', 'one', 'foo' => 'bar' ] )
				);
	}

	public function testRun_get_args_2() {
		$this->assertEquals(
				[ 'one, bar' ],
				Runtime::runSource( 'echo implode( ", ", get_args() );', [ 'TestPage', 'one', 'foo' => 'bar' ] )
				);
	}

	public function testRun_get_arg_1() {
		$this->assertEquals(
				[ 'one', 'bar', 'not defined' ],
				Runtime::runSource( 'echo get_arg( 1 ), get_arg( "foo" ), get_arg( "bar", "not defined" );', [ 'TestPage', 'one', 'foo' => 'bar' ] )
				);
	}

	public function testRun_num_args_1() {
		$this->assertEquals(
				[ '2', '3' ],
				Runtime::runSource( 'echo num_args(), $argc;', [ 'TestPage', 'one', 'foo' => 'bar' ] )
				);
	}

}
