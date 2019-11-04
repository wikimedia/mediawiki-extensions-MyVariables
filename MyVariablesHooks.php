<?php
/**
 * Main classes used by the MyVariables extension.
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 */
class MyVariablesHooks {
	/**
	 * Register wiki markup words associated with MAG_NIFTYVAR as a variable
	 *
	 * @param array &$customVariableIds
	 * @return bool
	 */
	public static function declareVarIds( &$customVariableIds ) {
		$customVariableIds[] = 'MAG_CURRENTUSER';
		$customVariableIds[] = 'MAG_CURRENTLOGGEDUSER';
		$customVariableIds[] = 'MAG_CURRENTUSERREALNAME';
		$customVariableIds[] = 'MAG_LOGO';
		$customVariableIds[] = 'MAG_UUID';
		$customVariableIds[] = 'MAG_USERLANGUAGECODE';

		return true;
	}

	/**
	 * Assign a value to our variable
	 *
	 * @param Parser &$parser
	 * @param array &$cache
	 * @param string &$magicWordId
	 * @param string &$ret
	 * @return bool
	 */
	public static function assignAValue( &$parser, &$cache, &$magicWordId, &$ret ) {
		// Disable parser cache for all variables except USERLANGUAGECODE and LOGO.
		// USERLANGUAGECODE will cause the parser cache to vary on userlang.
		// LOGO doesn't change often enough to be considered dynamic.
		switch ( $magicWordId ) {
			case 'MAG_CURRENTLOGGEDUSER':
				if ( $GLOBALS['wgUser']->isAnon() ) {
					$parser->getOutput()->updateCacheExpiry( 0 );
					$ret = '';
					break;
				}
				// break is not necessary here
			case 'MAG_CURRENTUSER':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $GLOBALS['wgUser']->mName;
				break;
			case 'MAG_LOGO':
				$ret = $GLOBALS['wgLogo'];
				break;
			case 'MAG_CURRENTUSERREALNAME':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $GLOBALS['wgUser']->mRealName;
				break;
			case 'MAG_UUID':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = UIDGenerator::newUUIDv4();
				break;
			case 'MAG_USERLANGUAGECODE':
				$ret = $parser->getOptions()->getUserLang();
				break;
			default:
				return false;
		}
		return true;
	}
}
