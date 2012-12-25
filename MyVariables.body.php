<?php
/**
 * Main classes used by the MyVariables extension.
 *
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 */

/**
 *
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
        $customVariableIds[] = 'MAG_CURRENTUSERREALNAME';
        $customVariableIds[] = 'MAG_LOGO';

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
    public static function assignAValue(&$parser, &$cache, &$magicWordId, &$ret) {
        switch ($magicWordId) {
            case 'MAG_CURRENTUSER':
                $parser->disableCache(); # Mark this content as uncacheable
                $ret = $GLOBALS['wgUser']->mName;
                break;
            case 'MAG_LOGO':
                $ret = $GLOBALS['wgLogo'];
                break;
            case 'MAG_CURRENTUSERREALNAME':
                $parser->disableCache(); # Mark this content as uncacheable
                $ret = $GLOBALS['wgUser']->mRealName;
                break;

            default:
                return false;
        }
        return true;
    }
}