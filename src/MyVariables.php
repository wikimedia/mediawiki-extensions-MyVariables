<?php

namespace MediaWiki\Extension\MyVariables;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
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

	/**
	 * Get the page image property of the given page
	 *
	 * @param Parser $parser
	 * @param string $input
	 * @return string
	 */
	public static function getPageImage( Parser $parser, string $input = '' ) {
		$title = Title::newFromText( $input );
		if ( $title === null || !$title->exists() ) {
			return '';
		}

		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
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
		return $image;
	}

}
