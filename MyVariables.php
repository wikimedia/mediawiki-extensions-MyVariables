<?php
/**
 * MyVariables - An extension that allows the addition of new built in variables
 *
 * @link https://www.mediawiki.org/wiki/Extension:MyVariables Documentation
 * @file MyVariables.php
 * @defgroup MyVariables
 * @ingroup Extensions
 * @author Aran Dunkley [http://www.organicdesign.co.nz/nad User:Nad]
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @copyright © 2007 Aran Dunkley
 * @license GPL-2.0-or-later
 */

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'MyVariables' );
	wfWarn(
		'Deprecated PHP entry point used for MyVariables extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the MyVariables extension requires MediaWiki 1.25+' );
}
