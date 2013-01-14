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
            case 'MAG_UUID':
                $parser->disableCache(); # Mark this content as uncacheable
                $ret = self::get_UUID();
                break;
            case 'MAG_USERLANGUAGECODE':
                $parser->disableCache();
                $ret = $GLOBALS['wgUser']->getOption('language');
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * Generates version 4 UUID (random)
     *
     * @author Ryan McKeel ( Rmckeel )
     * @return string
     */
    public static function get_UUID( ) {
        if( function_exists('uuid_create') ) {
            // create random UUID use PECL uuid extension
            return uuid_create();
        }
        // Alternate creation method from http://aaronsaray.com/blog/2009/01/14/php-and-the-uuid/comment-page-1/#comment-1522
        // May not be as fast or as accurate to specification as php5-uuid
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }
}