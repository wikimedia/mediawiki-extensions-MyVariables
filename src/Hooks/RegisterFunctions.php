<?php

namespace MediaWiki\Extension\MyVariables\Hooks;

use MediaWiki\Extension\MyVariables\MyVariables;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Hook\ParserOptionsRegisterHook;
use ParserOptions;

class RegisterFunctions implements ParserFirstCallInitHook, ParserOptionsRegisterHook {

	/**
	 * Register parser functions associated with MAG_NIFTYVAR as a variable
	 * @inheritDoc
	 */
	public function onParserFirstCallInit( $parser ) {
		$parser->setFunctionHook( 'MAG_REALNAME', [ MyVariables::class, 'getRealName' ], SFH_NO_HASH );
	}

	/**
	 * Allow varying cache key for whether user is logged in.
	 * @inheritDoc
	 */
	public function onParserOptionsRegister( &$defaults, &$inCacheKey, &$lazyLoad ) {
		// We use null here to say use lazy initialization
		// Since the lazy callback has access to the correct user but we do not.
		$defaults['MyVarLoggedIn'] = null;
		$inCacheKey['MyVarLoggedIn'] = true;
		$lazyLoad['MyVarLoggedIn'] = static function ( ParserOptions $options ) {
			if ( method_exists( $options, 'getUser' ) ) {
				// pre 1.37
				// @phan-suppress-next-line PhanUndeclaredMethod
				$user = $options->getUser();
			} else {
				$user = $options->getUserIdentity();
			}
			return $user->isRegistered() ? "yes" : "no";
		};
	}
}
