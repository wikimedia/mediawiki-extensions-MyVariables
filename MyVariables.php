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
 * @licence GNU General Public Licence 2.0 or later
 */

// Check to see if we are being called as an extension or directly
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is an extension to MediaWiki and thus not a valid entry point.' );
}

// Register this extension on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'MyVariables',
	'version' => '3.3.0',
	'url' => 'https://www.mediawiki.org/wiki/Extension:MyVariables',
	'author' => array( '[https://www.mediawiki.org/wiki/User:Nad Aran Dunkley]',
		'[https://www.mediawiki.org/wiki/User:Pastakhov Pavel Astakhov]', '...', ),
	'descriptionmsg' => 'myvariables-desc'
);

// Allow translations for this extension
$wgMessagesDirs['MyVariables'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['MyVariables'] = __DIR__ . '/MyVariables.i18n.php';
$wgExtensionMessagesFiles['MyVariablesMagic'] = __DIR__ . '/MyVariables.i18n.magic.php';

//Preparing classes for autoloading
$wgAutoloadClasses['MyVariables'] = __DIR__ . '/MyVariables.body.php';

// Register hooks
$wgHooks['MagicWordwgVariableIDs'][] = 'MyVariables::declareVarIds';
$wgHooks['ParserGetVariableValueSwitch'][] = 'MyVariables::assignAValue';
