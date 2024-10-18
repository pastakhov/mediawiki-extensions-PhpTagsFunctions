<?php

namespace PhpTags;

/**
 * @coversNothing
 */
class PhpTagsFunctions_Mbstring_Test extends \PHPUnit\Framework\TestCase {

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
				[ MB_CASE_UPPER ],
				Runtime::runSource( 'echo MB_CASE_UPPER;' )
				);
	}

	public function testRun_constant_2() {
		$this->markTestSkipped( 'php8+ no longer has MB_OVERLOAD_STRING' );
		$this->assertEquals(
				[ MB_OVERLOAD_STRING ],
				Runtime::runSource( 'echo MB_OVERLOAD_STRING;' )
				);
	}

	public function testRun_mb_convert_case_1() {
		$this->assertEquals(
				[ 'MARY HAD A LITTLE LAMB AND SHE LOVED IT SO' ],
				Runtime::runSource( '
$str = "mary had a Little lamb and she loved it so";
$str = mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
echo $str;' )
				);
	}

	public function testRun_mb_convert_case_2() {
		$this->assertEquals(
				[ 'Mary Had A Little Lamb And She Loved It So' ],
				Runtime::runSource( '
$str = "mary had a Little lamb and she loved it so";
$str = mb_convert_case($str, MB_CASE_TITLE);
echo $str;' )
				);
	}

	public function testRun_mb_convert_case_3() {
		$this->assertEquals(
				[ 'ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ' ],
				Runtime::runSource( '
$str = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";
$str = mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
echo $str;' )
				);
	}

	public function testRun_mb_stripos_1() {
		$this->assertEquals(
				[ '18' ],
				Runtime::runSource( '
$str = "mary had a Little lamb and she loved it so";
echo mb_stripos($str, "LAMB");' )
				);
	}

	public function testRun_mb_stripos_2() {
		$this->assertEquals(
				[ '21' ],
				Runtime::runSource( '
$str = "Τάχιστη Αλώπηξ Βαφήσ Ψημένη Γη, Δρασκελίζει Υπέρ Νωθρού Κυνόσ";
echo mb_stripos($str, "ΨΗΜΈΝΗ");' )
				);
	}

	public function testRun_mb_strlen_1() {
		$this->assertEquals(
				[ '42' ],
				Runtime::runSource( '
$str = "mary had a Little lamb and she loved it so";
echo mb_strlen($str);' )
				);
	}

	public function testRun_mb_strlen_2() {
		$this->assertEquals(
				[ '61' ],
				Runtime::runSource( '
$str = "Τάχιστη Αλώπηξ Βαφήσ Ψημένη Γη, Δρασκελίζει Υπέρ Νωθρού Κυνόσ";
echo mb_strlen($str);' )
				);
	}

	public function testRun_mb_strpos_1() {
		$this->assertEquals(
				[ '18' ],
				Runtime::runSource( '
$str = "mary had a Little lamb and she loved it so";
echo mb_strpos($str, "lamb");' )
				);
	}

	public function testRun_mb_strpos_2() {
		$this->assertEquals(
				[ '21' ],
				Runtime::runSource( '
$str = "Τάχιστη Αλώπηξ Βαφήσ Ψημένη Γη, Δρασκελίζει Υπέρ Νωθρού Κυνόσ";
echo mb_strpos($str, "Ψημένη");' )
				);
	}

}
