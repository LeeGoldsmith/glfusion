<?php
/*
Bad Behavior - detects and blocks unwanted Web accesses
Copyright (C) 2005-2011 Michael Hampton

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

As a special exemption, you may link this program with any of the
programs listed below, regardless of the license terms of those
programs, and distribute the resulting program, without including the
source code for such programs: ExpressionEngine

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

Please report any problems to badbots AT ioerror DOT us
*/

###############################################################################
###############################################################################

if (!defined ('GVERSION')) {
    die('This file can not be used on its own.');
}

global $_DB_table_prefix;

define('BB2_CWD', dirname(__FILE__));

// Settings you can adjust for Bad Behavior.
// Most of these are unused in non-database mode.
$bb2_settings_defaults = array(
	'log_table'     => $_DB_table_prefix . 'bad_behavior2',
	'display_stats' => true,
	'strict'        => false,
	'verbose'       => false,
	'logging'       => true,
	'httpbl_key'    => '',
	'httpbl_threat' => '25',
	'httpbl_maxage' => '30',
	'offsite_forms' => false,
);

// Bad Behavior callback functions.

// Return current time in the format preferred by your database.
function bb2_db_date() {
    return gmdate('Y-m-d H:i:s');	// Example is MySQL format
}

// Return affected rows from most recent query.
function bb2_db_affected_rows() {
    return DB_affectedRows();
}

// Escape a string for database usage
function bb2_db_escape($string) {
    return DB_escapeString($string);
}

// Return the number of rows in a particular query.
function bb2_db_num_rows($result) {
    if ( $result !== FALSE ) {
        return DB_numRows($result);
    }
    return 0;
}

// Run a query and return the results, if any.
// Should return FALSE if an error occurred.
// Bad Behavior will use the return value here in other callbacks.
function bb2_db_query($query) {

    $result = DB_query($query);
    if ( $result === false ) {
	    return FALSE;
    }
    return $result;
}

// Return all rows in a particular query.
// Should contain an array of all rows generated by calling mysql_fetch_assoc()
// or equivalent and appending the result of each call to an array.
function bb2_db_rows($result) {
    return DB_fetchArray($result);
}

// Return emergency contact email address.
function bb2_email() {
    global $_CONF;

    return $_CONF['site_mail'];
}

// retrieve settings from database
// Settings are hard-coded for non-database use
function bb2_read_settings() {
    global $_TABLES, $bb2_settings_defaults;

    static $isInstalled   = null;

    if ($isInstalled === null) {
        $isInstalled = DB_getItem($_TABLES['vars'],'value','name="bb2_installed"');
    }

    return array('log_table'      => $bb2_settings_defaults['log_table'],
			     'display_stats'  => $bb2_settings_defaults['display_stats'],
			     'verbose'        => $bb2_settings_defaults['verbose'],
			     'logging'        => $bb2_settings_defaults['logging'],
			     'httpbl_key'     => $bb2_settings_defaults['httpbl_key'],
			     'httpbl_threat'  => $bb2_settings_defaults['httpbl_threat'],
			     'httpbl_maxage'  => $bb2_settings_defaults['httpbl_maxage'],
			     'strict'         => $bb2_settings_defaults['strict'],
			     'offsite_forms'  => $bb2_settings_defaults['offsite_forms'],
			     'is_installed'   => $isInstalled );
}

// write settings to database
function bb2_write_settings($settings) {
   global $_TABLES;

   DB_save($_TABLES['vars'],"name,value","'bb2_installed','{$settings['is_installed']}'");

   return true;
}

// installation
function bb2_install() {
    $settings = bb2_read_settings();
    if( $settings['is_installed'] == false ) {
	    bb2_db_query(bb2_table_structure($settings['log_table']));
	    $settings['is_installed'] = true;
	    bb2_write_settings( $settings );
    }
}

// Screener
// Insert this into the <head> section of your HTML through a template call
// or whatever is appropriate. This is optional we'll fall back to cookies
// if you don't use it.
function bb2_insert_head() {
    global $bb2_timer_total;
    global $bb2_javascript;
    $retval = '';

    $retval =  "\n<!-- Bad Behavior " . BB2_VERSION . " run time: " . number_format(1000 * $bb2_timer_total, 3) . " ms -->\n";
    $retval .= $bb2_javascript;
    return $retval;
}

// Display stats? This is optional.
function bb2_insert_stats($force = false) {

    static $retval = null;

    $settings = bb2_read_settings();

    if (!$force && !$settings['display_stats']) {
        return ''; // not cached
    }
    if ($retval !== null) {
        return $retval;
    }

    $blocked = bb2_db_query("SELECT COUNT(*) AS blocked FROM " . $settings['log_table'] . " WHERE `key` NOT LIKE '00000000'");
    $row = bb2_db_rows($blocked);
    if ($blocked !== FALSE) {
        $retval =  sprintf('<p><a href="http://www.bad-behavior.ioerror.us/">%1$s</a> %2$s <strong>%3$s</strong> %4$s</p>', 'Bad Behavior', 'has blocked', $row['blocked'], 'access attempts in the last 7 days.');
    }
    return $retval;
}

// Return the top-level relative path of wherever we are (for cookies)
// You should provide in $url the top-level URL for your site.
function bb2_relative_path() {
    global $_CONF;

    return $_CONF['cookie_path'];
}

$bb2_mtime = explode(" ", microtime());
$bb2_timer_start = $bb2_mtime[1] + $bb2_mtime[0];

// Calls inward to Bad Behavor itself.
require_once(BB2_CWD . "/bad-behavior/version.inc.php");
require_once(BB2_CWD . "/bad-behavior/core.inc.php");

bb2_install();

bb2_start(bb2_read_settings());

$bb2_mtime = explode(" ", microtime());
$bb2_timer_stop = $bb2_mtime[1] + $bb2_mtime[0];
$bb2_timer_total = $bb2_timer_stop - $bb2_timer_start;
?>