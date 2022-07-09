<?php

namespace MediaWiki\Extension\MyVariables;

use MediaWiki\MediaWikiServices;
use Parser;

/**
 * Main classes used by the MyVariables extension.
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 */
class MyVariables {

	/**
	 * Get the real name of the given user
	 *
	 * @param Parser $parser
	 * @param string $input
	 * @return string
	 */
	public static function getRealName( Parser $parser, string $input = '' ) {
		$user = MediaWikiServices::getInstance()->getUserFactory()->newFromName( $input );
		if ( $user ) {
			return $user->getRealName();
		}
		return '';
	}

}
