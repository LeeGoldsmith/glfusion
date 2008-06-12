<?php
// +---------------------------------------------------------------------------+
// | CAPTCHA glFusion Upgrade                                                  |
// +---------------------------------------------------------------------------+
// | $Id::                                                                    $|
// | This file has the functions necessary to upgrade CAPTCHA                  |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2005-2008 by the following authors:                         |
// |                                                                           |
// | Mark R. Evans              - mark@gllabs.org                              |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//

if (strpos ($_SERVER['PHP_SELF'], 'glupgrade.inc') !== false)
{
    die ('This file can not be used on its own.');
}

if ( !isset($_DB_table_prefix) ) {
    $_DB_table_prefix = '';
}

$_CP_CONF['version']    = '3.1.0';
$_CP_CONF['pi_name']    = 'captcha';
$_CP_CONF['gl_version'] = '1.5.0';
$_TABLES['cp_config']   = $_DB_table_prefix . 'cp_config';
$_TABLES['cp_sessions'] = $_DB_table_prefix . 'cp_sessions';

/*
 * If we are being called Geeklog's upgrade routine, there is no
 * COM_errorLog() function available.
 */

if (!function_exists('COM_errorLOG') ) {
    function COM_errorLOG() {
    }
}

function plugin_upgrade_captcha()
{
    global $_TABLES, $_CONF, $_CP_CONF;

    $currentVersion = DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='captcha'");

    switch( $currentVersion ) {
        case "2.0.0" :
        case "2.0.1" :
        case "2.0.2" :
            $_SQL['cp_config'] =
                "CREATE TABLE {$_TABLES['cp_config']} ( " .
                "  `config_name` varchar(255) NOT NULL default '', " .
                "  `config_value` varchar(255) NOT NULL default '', " .
                "   PRIMARY KEY  (`config_name`) " .
                " );";

            foreach ($_SQL as $table => $sql) {
                COM_errorLog("Creating $table table",1);
                DB_query($sql,1);
                if (DB_error()) {
                    COM_errorLog("Error Creating $table table",1);
                }
                COM_errorLog("Success - Created $table table",1);
            }

            $SQL_DEFAULTS = "INSERT INTO `{$_TABLES['cp_config']}` (`config_name`, `config_value`) VALUES " .
                            " ('anonymous_only', '1'), " .
                            " ('debug', '0'), " .
                            " ('enable_comment', '0'), " .
                            " ('enable_contact', '0'), " .
                            " ('enable_emailstory', '0'), " .
                            " ('enable_forum', '0'), " .
                            " ('enable_registration', '0'), " .
                            " ('enable_story', '0'), " .
                            " ('gfxDriver', '2'), " .
                            " ('gfxFormat', 'jpg'), " .
                            " ('gfxPath', '');";

            DB_query($SQL_DEFAULTS,1);
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='2.1.0' WHERE pi_name='captcha' LIMIT 1");
        case "2.1.0" :
        case "2.1.1" :
        case "2.1.2" :
            $_SQL['cp_sessions'] =
                            "CREATE TABLE {$_TABLES['cp_sessions']} ( " .
                            "  `session_id` varchar(40) NOT NULL default '', " .
                            "  `cptime`  INT(11) NOT NULL default 0, " .
                            "  `validation` varchar(40) NOT NULL default '', " .
                            "  `counter`    TINYINT(4) NOT NULL default 0, " .
                            "  PRIMARY KEY (`session_id`) " .
                            " );";

            foreach ($_SQL as $table => $sql) {
                COM_errorLog("Creating $table table",1);
                DB_query($sql,1);
                if (DB_error()) {
                    COM_errorLog("Error Creating $table table",1);
                }
                COM_errorLog("Success - Created $table table",1);
            }

            $SQL_DEFAULTS = "INSERT INTO `{$_TABLES['cp_config']}` (`config_name`, `config_value`) VALUES " .
                            " ('enable_mediagallery', '0'), " .
                            " ('remoteusers', '0');";

            DB_query($SQL_DEFAULTS,1);
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='3.0.0' WHERE pi_name='captcha' LIMIT 1");
        case "3.0.0" :
        case "3.0.1" :
        case "3.0.2" :
            $SQL_DEFAULTS = "INSERT INTO `{$_TABLES['cp_config']}` (`config_name`, `config_value`) VALUES " .
                            " ('logging', '0'), " .
                            " ('enable_rating', '0');";
            DB_query($SQL_DEFAULTS,1);
        default :
            DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='3.1.0' WHERE pi_name='captcha' LIMIT 1");
            break;
    }
    if ( DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='captcha'") == $_CP_CONF['version']) {
        return true;
    } else {
        return false;
    }
}
?>