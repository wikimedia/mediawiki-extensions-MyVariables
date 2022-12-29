<?php

namespace MediaWiki\Extension\MyVariables\Hooks;

use ExtensionRegistry;
use MediaWiki\Hook\ParserGetVariableValueSwitchHook;
use MediaWiki\MediaWikiServices;
use RequestContext;

class AssignAValue implements ParserGetVariableValueSwitchHook {

	/**
	 * Assign a value to our variable
	 * @inheritDoc
	 */
	public function onParserGetVariableValueSwitch( $parser, &$variableCache, $magicWordId, &$ret, $frame ) {
		$user = RequestContext::getMain()->getUser();
		$title = $parser->getTitle();

		switch ( $magicWordId ) {
			case 'MAG_CURRENTLOGGEDUSER':
				// Notes: this sets flag later to be checked within DisableCache hook
				// to prevent content from being sourced from ParserCache
				// thus this forces the magic to be re-evaluated each page view
				// at the same time this keeps Cache-control untouched thus allows external
				// proxies to cache the page
				$parser->getOutput()->setFlag( 'my_variables_no_cache' );
				if ( $user->isAnon() ) {
					$ret = $variableCache[$magicWordId] = '';
					break;
				}
			// break is not necessary here
			case 'MAG_CURRENTUSER':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $variableCache[$magicWordId] = $user->getName();
				break;
			case 'MAG_CURRENTUSERREALNAME':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$ret = $variableCache[$magicWordId] = $user->getRealName();
				break;
			case 'MAG_FIRSTREVISIONID':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$rev = MediaWikiServices::getInstance()->getRevisionLookup()->getFirstRevision( $title );
				$ret = $variableCache[$magicWordId] = $rev ? $rev->getId() : '';
				break;
			case 'MAG_FIRSTREVISIONTIMESTAMP':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$rev = MediaWikiServices::getInstance()->getRevisionLookup()->getFirstRevision( $title );
				$ret = $variableCache[$magicWordId] = $rev ? $rev->getTimestamp() : '';
				break;
			case 'MAG_FIRSTREVISIONUSER':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$rev = MediaWikiServices::getInstance()->getRevisionLookup()->getFirstRevision( $title );
				$ret = $variableCache[$magicWordId] = $rev ? $rev->getUser()->getName() : '';
				break;
			case 'MAG_HITCOUNTER':
				if ( !ExtensionRegistry::getInstance()->isLoaded( 'HitCounters' ) ) {
					break;
				}
				$parser->getOutput()->updateCacheExpiry( 0 );
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select(
					'hit_counter',
					'page_counter',
					'page_id = ' . $title->getArticleID()
				);
				$counter = '';
				foreach ( $result as $row ) {
					$counter = $row->page_counter;
				}
				$ret = $variableCache[$magicWordId] = $counter;
				break;
			case 'MAG_LOGO':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$logos = MediaWikiServices::getInstance()->getMainConfig()->get( 'Logos' );
				$ret = $variableCache[$magicWordId] = $logos['1x'] ?? '';
				break;
			case 'MAG_PAGEIMAGE':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$dbr = wfGetDB( DB_REPLICA );
				$result = $dbr->select(
					'page_props',
					'pp_value',
					[
						'pp_propname = "page_image_free"',
						'pp_page = ' . $title->getArticleID()
					]
				);
				$image = '';
				foreach ( $result as $row ) {
					$image = $row->pp_value;
				}
				$ret = $variableCache[$magicWordId] = $image;
				break;
			case 'MAG_REDIRECTS':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$redirects = [];
				foreach ( $title->getRedirectsHere() as $redirect ) {
					$redirects[] = $redirect->getFullText();
				}
				$redirects = implode( ', ', $redirects );
				$ret = $variableCache[$magicWordId] = $redirects;
				break;
			case 'MAG_REALNAME':
				$parser->getOutput()->updateCacheExpiry( 0 );
				if ( $title->getNamespace() === NS_USER ) {
					$name = $title->getText();
					$u = MediaWikiServices::getInstance()->getUserFactory()->newFromName( $name );
					if ( $u ) {
						$name = $u->getRealName();
					}
					$ret = $variableCache[$magicWordId] = $name;
				}
				break;
			case 'MAG_SUBPAGES':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$subpages = [];
				foreach ( $title->getSubpages() as $subpage ) {
					$subpages[] = $subpage;
				}
				$ret = $variableCache[$magicWordId] = implode( ', ', $subpages );
				break;
			case 'MAG_UUID':
				$parser->getOutput()->updateCacheExpiry( 0 );
				// Only one UUID per page.
				$ret = $variableCache[$magicWordId] = MediaWikiServices::getInstance()
					->getGlobalIdGenerator()
					->newUUIDv4();
				break;
			case 'MAG_USERREGISTRATION':
				$parser->getOutput()->updateCacheExpiry( 0 );
				if ( $title->getNamespace() === NS_USER ) {
					$name = $title->getText();
					$u = MediaWikiServices::getInstance()->getUserFactory()->newFromName( $name );
					if ( $u ) {
						$registration = $u->getRegistration();
						$ret = $variableCache[$magicWordId] = $registration;
					}
				}
				break;
			case 'MAG_USERLANGUAGECODE':
				$ret = $variableCache[$magicWordId] = $parser->getOptions()->getUserLang();
				break;
			case 'MAG_WHATLINKSHERE':
				$parser->getOutput()->updateCacheExpiry( 0 );
				$links = [];
				foreach ( $title->getLinksTo() as $link ) {
					$links[] = $link->getFullText();
				}
				$links = implode( ', ', $links );
				$ret = $variableCache[$magicWordId] = $links;
				break;
		}
	}

}
