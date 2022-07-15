<?php

namespace MediaWiki\Extension\MyVariables\Hooks;

use Article;
use MediaWiki\Page\Hook\ArticleViewHeaderHook;
use ParserOutput;

class DisableCache implements ArticleViewHeaderHook {

	/**
	 * @param Article $article
	 * @param bool &$pcache
	 * @param bool|ParserOutput &$outputDone
	 *
	 * @return bool|void
	 */
	public function onArticleViewHeader( $article, &$pcache, &$outputDone ) {
		if ( $article->getParserOutput() && $article->getParserOutput()->getFlag( 'my_variables_no_cache' ) ) {
			// Effectively prevents contents from being sourced from parser cache
			// See Article::view for details
			$outputDone = false;
		}
	}

}
