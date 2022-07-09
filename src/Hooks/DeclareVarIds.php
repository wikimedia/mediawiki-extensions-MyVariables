<?php

namespace MediaWiki\Extension\MyVariables\Hooks;

use MediaWiki\Hook\GetMagicVariableIDsHook;

class DeclareVarIds implements GetMagicVariableIDsHook {

	/**
	 * Register wiki markup words associated with MAG_NIFTYVAR as a variable
	 * @inheritDoc
	 */
	public function onGetMagicVariableIDs( &$variableIDs ) {
		$variableIDs[] = 'MAG_CURRENTUSER';
		$variableIDs[] = 'MAG_CURRENTLOGGEDUSER';
		$variableIDs[] = 'MAG_CURRENTUSERREALNAME';
		$variableIDs[] = 'MAG_FIRSTREVISIONID';
		$variableIDs[] = 'MAG_FIRSTREVISIONTIMESTAMP';
		$variableIDs[] = 'MAG_FIRSTREVISIONUSER';
		$variableIDs[] = 'MAG_HITCOUNTER';
		$variableIDs[] = 'MAG_LOGO';
		$variableIDs[] = 'MAG_PAGEIMAGE';
		$variableIDs[] = 'MAG_REDIRECTS';
		$variableIDs[] = 'MAG_REALNAME';
		$variableIDs[] = 'MAG_SUBPAGES';
		$variableIDs[] = 'MAG_UUID';
		$variableIDs[] = 'MAG_USERREGISTRATION';
		$variableIDs[] = 'MAG_USERLANGUAGECODE';
		$variableIDs[] = 'MAG_WHATLINKSHERE';

		return true;
	}

}
