<?php
/**
 * Main classes used by the MyVariables extension.
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 */
class MyVariables {
	/**
	 * Register wiki markup words associated with MAG_NIFTYVAR as a variable
	 *
	 * @param array $customVariableIds
	 * @return boolean
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
	 * @param Parser $parser
	 * @param array $cache
	 * @param string $magicWordId
	 * @param string $ret
	 * @return boolean
	 */
	public static function assignAValue( &$parser, &$cache, &$magicWordId, &$ret ) {
		$parser->disableCache(); # Mark this content as uncacheable
		switch ( $magicWordId ) {
			case 'MAG_CURRENTLOGGEDUSER':
				if ( $GLOBALS['wgUser']->isAnon() ) {
					$ret = '';
					break;
				}
				// break is not necessary here
			case 'MAG_CURRENTUSER':
				$ret = $GLOBALS['wgUser']->mName;
				break;
			case 'MAG_LOGO':
				$ret = $GLOBALS['wgLogo'];
				break;
			case 'MAG_CURRENTUSERREALNAME':
				$ret = $GLOBALS['wgUser']->mRealName;
				break;
			case 'MAG_UUID':
				$ret = UIDGenerator::newUUIDv4();
				break;
			case 'MAG_USERLANGUAGECODE':
				$ret = $GLOBALS['wgUser']->getOption( 'language' );
				break;
			default:
				return false;
		}
		return true;
	}
}
