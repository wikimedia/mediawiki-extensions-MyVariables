<?php

namespace MediaWiki\Extension\MyVariables\Hooks;

use MediaWiki\Extension\MyVariables\MyVariables;
use MediaWiki\Hook\ParserFirstCallInitHook;

class RegisterFunctions implements ParserFirstCallInitHook {

	/**
	 * Register parser functions associated with MAG_NIFTYVAR as a variable
	 * @inheritDoc
	 */
	public function onParserFirstCallInit( $parser ) {
		$parser->setFunctionHook( 'MAG_REALNAME', [ MyVariables::class, 'getRealName' ], SFH_NO_HASH );
	}

}
