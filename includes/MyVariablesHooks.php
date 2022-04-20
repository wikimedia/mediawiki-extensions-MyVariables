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
		$customVariableIds[] = 'MAG_FIRSTREVISIONID';
		$customVariableIds[] = 'MAG_FIRSTREVISIONTIMESTAMP';
		$customVariableIds[] = 'MAG_FIRSTREVISIONUSER';
		$customVariableIds[] = 'MAG_LOGO';
		$customVariableIds[] = 'MAG_PAGEIMAGE';
		$customVariableIds[] = 'MAG_REALNAME';
		$customVariableIds[] = 'MAG_SUBPAGES';
		$customVariableIds[] = 'MAG_UUID';
		$customVariableIds[] = 'MAG_USERLANGUAGECODE';
		$customVariableIds[] = 'MAG_WHATLINKSHERE';

		return true;
	}

	/**
	 * Register parser functions associated with MAG_NIFTYVAR as a variable
	 *
	 * @param Parser $parser Parser object
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'MAG_REALNAME', [ self::class, 'getRealName' ], SFH_NO_HASH );
	}

	/**
	 * Get the real name of the given user
	 *
	 * @param Parser $parser
	 * @param string $input
	 * @return string
	 */
	public static function getRealName( Parser $parser, string $input = '' ) {
		$user = User::newFromName( $input );
		if ( $user ) {
			return $user->getRealName();
		}
	}

	/**
	 * Assign a value to our variable
	 *
	 * @param Parser $parser
	 * @param array &$cache
	 * @param string $magicWordId
	 * @param string &$ret
	 * @return void
	 */
	public static function assignAValue( $parser, &$cache, $magicWordId, &$ret ) {
		// Disable parser cache for all variables except USERLANGUAGECODE and LOGO.
		// USERLANGUAGECODE will cause the parser cache to vary on userlang.
		// LOGO doesn't change often enough to be considered dynamic.
		switch ( $magicWordId ) {
			case 'MAG_CURRENTLOGGEDUSER':
				if ( $GLOBALS['wgUser']->isAnon() ) {
					$parser->getOutput()->updateCacheExpiry( 0 );
					$ret = $cache[$magicWordId] = '';
					break;
				}
				// break is not necessary here
			case 'MAG_CURRENTUSER':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $cache[$magicWordId] = $GLOBALS['wgUser']->mName;
				break;
			case 'MAG_CURRENTUSERREALNAME':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $cache[$magicWordId] = $GLOBALS['wgUser']->mRealName;
				break;
			case 'MAG_FIRSTREVISIONID':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$id = $parser->getTitle()->getArticleID();
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select( 'revision', 'rev_id',
					"rev_page = $id", __METHOD__, [ 'ORDER BY' => 'rev_id ASC', 'LIMIT' => 1 ] );
				foreach ( $result as $row ) {
					$ret = $cache[$magicWordId] = $row->rev_id;
				}
				break;
			case 'MAG_FIRSTREVISIONTIMESTAMP':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$id = $parser->getTitle()->getArticleID();
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select( 'revision', 'rev_timestamp',
					"rev_page = $id", __METHOD__, [ 'ORDER BY' => 'rev_id ASC', 'LIMIT' => 1 ] );
				foreach ( $result as $row ) {
					$ret = $cache[$magicWordId] = $row->rev_timestamp;
				}
				break;
			case 'MAG_FIRSTREVISIONUSER':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$id = $parser->getTitle()->getArticleID();
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select( 'revision_actor_temp', 'revactor_actor',
					"revactor_page = $id", __METHOD__, [ 'ORDER BY' => 'revactor_rev ASC', 'LIMIT' => 1 ] );
				foreach ( $result as $row ) {
					$actor = $row->revactor_actor;
					$user = User::newFromActorId( $actor );
					$ret = $cache[$magicWordId] = $user->getName();
				}
				break;
			case 'MAG_LOGO':
				$ret = $cache[$magicWordId] = $GLOBALS['wgLogo'];
				break;
			case 'MAG_PAGEIMAGE':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$id = $parser->getTitle()->getArticleID();
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select( 'page_props', 'pp_value',
					[ 'pp_propname = "page_image_free"', "pp_page = $id" ] );
				$image = '';
				foreach ( $result as $row ) {
					$image = $row->pp_value;
				}
				$ret = $cache[$magicWordId] = $image;
				break;
			case 'MAG_REALNAME':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$title = $parser->getTitle();
				if ( $title->getNamespace() === NS_USER ) {
					$name = $title->getText();
					$user = User::newFromName( $name );
					if ( $user ) {
						$name = $user->getRealName();
					}
					$ret = $cache[$magicWordId] = $name;
				}
				break;
			case 'MAG_SUBPAGES':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$subpages = [];
				foreach ( $parser->getTitle()->getSubpages() as $subpage ) {
					$subpages[] = $subpage;
				}
				$ret = $cache[$magicWordId] = implode( ', ', $subpages );
				break;
			case 'MAG_UUID':
				$parser->getOutput()->updateCacheExpiry( 0 );
				// Only one UUID per page.
				$ret = $cache[$magicWordId] = UIDGenerator::newUUIDv4();
				break;
			case 'MAG_USERLANGUAGECODE':
				$ret = $cache[$magicWordId] = $parser->getOptions()->getUserLang();
				break;
			case 'MAG_WHATLINKSHERE':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$links = [];
				foreach ( $parser->getTitle()->getLinksTo() as $link ) {
					$links[] = $link->getFullText();
				}
				$links = implode( ', ', $links );
				$ret = $cache[$magicWordId] = $links;
				break;
		}
	}
}
