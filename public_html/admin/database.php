<?php
// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | database.php                                                             |
// |                                                                          |
// | glFusion database backup administration page.                            |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// |                                                                          |
// | Based on the Geeklog CMS                                                 |
// | Copyright (C) 2000-2008 by the following authors:                        |
// |                                                                          |
// | Authors: Tony Bibbs         - tony AT tonybibbs DOT com                  |
// |          Blaine Lang        - langmail AT sympatico DOT ca               |
// |          Dirk Haun          - dirk AT haun-online DOT de                 |
// |          Alexander Schmacks - Alexander.Schmacks AT gmx DOT de           |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

require_once '../lib-common.php';
require_once 'auth.inc.php';

$display = '';

// If user isn't a root user or if the backup feature is disabled, bail.
if (!SEC_inGroup('Root') OR $_CONF['allow_mysqldump'] == 0) {
    $display .= COM_siteHeader('menu', $LANG_DB_BACKUP['last_ten_backups']);
    $display .= COM_showMessageText($MESSAGE[46],$MESSAGE[30],true);
    $display .= COM_siteFooter();
    COM_accessLog("User {$_USER['username']} tried to illegally access the database backup screen.");
    echo $display;
    exit;
}


/**
* Sort backup files with newest first, oldest last.
* For use with usort() function.
* This is needed because the sort order of the backup files, coming from the
* 'readdir' function, might not be that way.
*/
function DBADMIN_compareBackupFiles($pFileA, $pFileB)
{
    global $_CONF;

    $lFiletimeA = filemtime($_CONF['backup_path'] . $pFileA);
    $lFiletimeB = filemtime($_CONF['backup_path'] . $pFileB);
    if ($lFiletimeA == $lFiletimeB) {
       return 0;
    }

    return ($lFiletimeA > $lFiletimeB) ? -1 : 1;
}

/**
* List all backups, i.e. all files ending in .sql
*
* @return   string      HTML for the list of files or an error when not writable
*
*/
function DBADMIN_list()
{
    global $_CONF, $_TABLES, $_IMAGE_TYPE, $LANG08, $LANG_ADMIN, $LANG_DB_BACKUP;

    USES_lib_admin();

    $retval = '';

    if (is_writable($_CONF['backup_path'])) {
        $backups = array();
        $fd = opendir($_CONF['backup_path']);
        $index = 0;
        while ((false !== ($file = @readdir($fd)))) {
            if ($file <> '.' && $file <> '..' && $file <> 'CVS' &&
                    preg_match('/\.sql$/i', $file)) {
                $index++;
                clearstatcache();
                $backups[] = $file;
            }
        }

        // AS, 2004-03-29 - Sort backup files by date, newest first.
        // Order given by 'readdir' might not be correct.
        usort($backups, 'DBADMIN_compareBackupFiles');

        $data_arr = array();
        $thisUrl = $_CONF['site_admin_url'] . '/database.php';
        $diskIconUrl = $_CONF['layout_url'] . '/images/admin/disk.' . $_IMAGE_TYPE;
        $attr['title'] = $LANG_DB_BACKUP['download'];
        $alt = $LANG_DB_BACKUP['download'];
        $num_backups = count($backups);
        for ($i = 0; $i < $num_backups; $i++) {
            $downloadUrl = $thisUrl . '?download=x&amp;file='
                         . urlencode($backups[$i]);

            $downloadLink = COM_createLink(COM_createImage($diskIconUrl, $alt, $attr), $downloadUrl, $attr);
            $downloadLink .= '&nbsp;&nbsp;';
            $attr['style'] = 'vertical-align:top;';
            $downloadLink .= COM_createLink($backups[$i], $downloadUrl, $attr);
            $backupfile = $_CONF['backup_path'] . $backups[$i];
            $backupfilesize = COM_numberFormat(filesize($backupfile))
                            . ' <b>' . $LANG_DB_BACKUP['bytes'] . '</b>';
            $data_arr[$i] = array('file' => $downloadLink,
                                  'size' => $backupfilesize,
                                  'filename' => $backups[$i]);
        }

        $token = SEC_createToken();
        $menu_arr = array(
            array('url' => $_CONF['site_admin_url']
                           . '/database.php?backup=x&amp;'.CSRF_TOKEN.'='.$token,
                  'text' => $LANG_ADMIN['create_new']),
            array('url' => $_CONF['site_admin_url'],
                  'text' => $LANG_ADMIN['admin_home'])
        );
        $retval .= COM_startBlock($LANG_DB_BACKUP['last_ten_backups'], '',
                            COM_getBlockTemplate('_admin_block', 'header'));
        $retval .= ADMIN_createMenu(
            $menu_arr,
            "<p>{$LANG_DB_BACKUP['db_explanation']}</p>" .
            '<p>' . sprintf($LANG_DB_BACKUP['total_number'], $index) . '</p>',
            $_CONF['layout_url'] . '/images/icons/database.' . $_IMAGE_TYPE
        );

        $header_arr = array(      // display 'text' and use table field 'field'
            array('text' => $LANG_DB_BACKUP['backup_file'], 'field' => 'file'),
            array('text' => $LANG_DB_BACKUP['size'],        'field' => 'size')
        );

        $text_arr = array(
            'form_url' => $thisUrl
        );
        $form_arr = array('bottom' => '', 'top' => '');
        if ($num_backups > 0) {
            $form_arr['bottom'] = '<input type="hidden" name="delete" value="x"' . XHTML . '>'
                                . '<input type="hidden" name="' . CSRF_TOKEN
                                . '" value="' . $token . '"' . XHTML . '>' . LB;
        }
        $options = array('chkselect' => true, 'chkminimum' => 0,
                             'chkfield' => 'filename');
        $retval .= ADMIN_simpleList('', $header_arr, $text_arr, $data_arr,
                                    $options, $form_arr);
        $retval .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));
    } else {
        $retval .= COM_startBlock($LANG08[06], '',
                            COM_getBlockTemplate('_msg_block', 'header'));
        $retval .= $LANG_DB_BACKUP['no_access'];
        COM_errorLog($_CONF['backup_path'] . ' is not writable.', 1);
        $retval .= COM_endBlock(COM_getBlockTemplate('_msg_block', 'footer'));
    }

    return $retval;
}

/**
* Perform database backup
*
* @return   string      HTML success or error message
*
*/
function DBADMIN_backup()
{
    global $_CONF, $LANG08, $LANG_DB_BACKUP, $MESSAGE, $_IMAGE_TYPE,
           $_DB_host, $_DB_name, $_DB_user, $_DB_pass, $_DB_mysqldump_path;

    $retval = '';

    if (is_dir($_CONF['backup_path'])) {
        $curdatetime = date('Y_m_d_H_i_s');
        $backupfile = "{$_CONF['backup_path']}glfusion_db_backup_{$curdatetime}.sql";
        $command = '"'.$_DB_mysqldump_path.'" ' . " -h$_DB_host -u$_DB_user";
        if (!empty($_DB_pass)) {
            $command .= " -p".escapeshellarg($_DB_pass);
            $parg = " -p".escapeshellarg($_DB_pass);
        }
        if (!empty($_CONF['mysqldump_options'])) {
            $command .= ' ' . $_CONF['mysqldump_options'];
        }
        $command .= " $_DB_name > \"$backupfile\"";
        $log_command = $command;
        if (!empty($_DB_pass)) {
            $log_command = str_replace($parg, ' -p*****', $command);
        }

        if (function_exists('is_executable')) {
            $canExec = @is_executable($_DB_mysqldump_path);
        } else {
            $canExec = @file_exists($_DB_mysqldump_path);
        }
        if ($canExec) {
            DBADMIN_execWrapper($command);
            if (file_exists($backupfile) && filesize($backupfile) > 1000) {
                @chmod($backupfile, 0644);
                $retval .= COM_showMessage(93);
            } else {
                $retval .= COM_showMessage(94);
                COM_errorLog('Backup Filesize was less than 1kb', 1);
                COM_errorLog("Command used for mysqldump: $log_command", 1);
            }
        } else {
            $retval .= COM_startBlock($LANG08[06], '',
                                COM_getBlockTemplate('_msg_block', 'header'));
            $retval .= $LANG_DB_BACKUP['not_found'];
            $retval .= COM_endBlock(COM_getBlockTemplate('_msg_block',
                                                         'footer'));
            COM_errorLog('Backup Error: Bad path, mysqldump does not exist or open_basedir restriction in effect.', 1);
            COM_errorLog("Command used for mysqldump: $log_command", 1);
        }
    } else {
        $retval .= COM_startBlock($MESSAGE[30], '',
                            COM_getBlockTemplate('_msg_block', 'header'));
        $retval .= $LANG_DB_BACKUP['path_not_found'];
        $retval .= COM_endBlock(COM_getBlockTemplate('_msg_block', 'footer'));
        COM_errorLog("Backup directory '" . $_CONF['backup_path'] . "' does not exist or is not a directory", 1);
    }

    return $retval;
}

/**
* Download a backup file
*
* @param    string  $file   Filename (without the path)
* @return   void
* @note     Filename should have been sanitized and checked before calling this.
*
*/
function DBADMIN_download($file)
{
    global $_CONF;

    require_once $_CONF['path_system'] . 'classes/downloader.class.php';

    $dl = new downloader;

    $dl->setLogFile($_CONF['path'] . 'logs/error.log');
    $dl->setLogging(true);
    $dl->setDebug(true);

    $dl->setPath($_CONF['backup_path']);
    $dl->setAllowedExtensions(array('sql' =>  'application/x-gzip-compressed'));

    $dl->downloadFile($file);
}

function DBADMIN_exec($cmd) {
    global $_CONF, $_DB_pass;

    $debugfile = "";
    $status="";
    $results=array();

    if (!empty($_DB_pass)) {
        $log_command = str_replace($_DB_pass,'*****', $cmd);
    }
    COM_errorLog(sprintf("DBADMIN_exec: Executing: %s",$log_command));

    $debugfile = $_CONF['path'] . 'logs/debug.log';

    if (PHP_OS == "WINNT") {
        $cmd .= " 2>&1";
        exec('"' . $cmd . '"',$results,$status);
    } else {
        exec($cmd, $results, $status);
    }

    return array($results, $status);
}

function DBADMIN_execWrapper($cmd) {

    list($results, $status) = DBADMIN_exec($cmd);

    if ( $status == 0 ) {
        return true;
    } else {
        COM_errorLog("DBADMIN_execWrapper: Failed Command: " . $cmd);
        return false;
    }
}

// MAIN ========================================================================

$action = '';
$expected = array('backup','download','delete');
foreach($expected as $provided) {
    if (isset($_POST[$provided])) {
        $action = $provided;
    } elseif (isset($_GET[$provided])) {
	$action = $provided;
    }
}

switch ($action) {

    case 'backup':
        if (SEC_checkToken()) {
            $display .= DBADMIN_backup();
        } else {
            COM_accessLog("User {$_USER['username']} tried to illegally backup the database and failed CSRF checks.");
            echo COM_refresh($_CONF['site_admin_url'] . '/index.php');
        }
        break;

    case 'download':
        $file = '';
        if (isset($_GET['file'])) {
            $file = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', COM_applyFilter($_GET['file']));
            $file = str_replace('..', '', $file);
            if (!file_exists($_CONF['backup_path'] . $file)) {
                $file = '';
            }
        }
        if (!empty($file)) {
            DBADMIN_download($file);
            exit;
        }
        break;

    case 'delete':
        if (isset($_POST['delitem']) AND SEC_checkToken()) {
            foreach ($_POST['delitem'] as $delfile) {
                $file = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', COM_applyFilter($delfile));
                $file = str_replace('..', '', $file);
                if (!@unlink($_CONF['backup_path'] . $file)) {
                    COM_errorLog('Unable to remove backup file "' . $file . '"');
                }
            }
        } else {
            COM_accessLog("User {$_USER['username']} tried to illegally delete database backup(s) and failed CSRF checks.");
            echo COM_refresh($_CONF['site_admin_url'] . '/index.php');
        }
        break;
}

$display .= COM_siteHeader('menu', $LANG_DB_BACKUP['last_ten_backups']);
$display .= DBADMIN_list();
$display .= COM_siteFooter();

echo $display;

?>
