<?php
// +---------------------------------------------------------------------------+
// | Bad Behavior2 glFusion Upgrade                                            |
// +---------------------------------------------------------------------------+
// | $Id::                                                                    $|
// | This file has the functions necessary to upgrade Bad Behavior2            |
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


/*
 * If we are being called Geeklog's upgrade routine, there is no
 * COM_errorLog() function available.
 */

if (!function_exists('COM_errorLOG') ) {
    function COM_errorLOG() {
    }
}

function plugin_upgrade_bad_behavior2 ()
{
    global $_TABLES;

    // Bad Behavior handles its database changes automatically,
    // so only update the version number
    DB_query ("UPDATE {$_TABLES['plugins']} SET pi_version = '2.0.13a', pi_gl_version = '1.5.0', pi_homepage = 'http://www.gllabs.org/' WHERE pi_name = 'bad_behavior2'");

    return true;
}