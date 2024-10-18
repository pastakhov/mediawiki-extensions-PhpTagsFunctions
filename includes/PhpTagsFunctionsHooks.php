<?php

namespace PhpTagsObjects;

use ExtensionRegistry;
use MWException;
use PhpTags\Hooks;

/**
 * PhpTags Functions MediaWiki Hooks.
 *
 * @ingroup PhpTags
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @license GPL-2.0-or-later
 */
class PhpTagsFunctionsHooks {

	/**
	 * Check on version compatibility
	 *
	 * @return void
	 * @throws MWException
	 */
	public static function onParserFirstCallInit() {
		if ( PHPTAGS_HOOK_RELEASE != 8 ) {
			throw new MWException( "\n\nThis version of the PhpTags Functions extension " .
				"is outdated and not compatible with current version of the PhpTags extension.\n" .
				" Please update it." );
		}
	}

	/**
	 * @return void
	 */
	public static function onPhpTagsRuntimeFirstInit() {
		$version = ExtensionRegistry::getInstance()->getAllThings()['PhpTags Functions']['version'];
		Hooks::addJsonFile( __DIR__ . '/../PhpTagsFunctions.json', $version . PHP_VERSION );
	}

}
