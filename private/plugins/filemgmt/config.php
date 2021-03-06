<?php
// +--------------------------------------------------------------------------+
// | FileMgmt Plugin - glFusion CMS                                           |
// +--------------------------------------------------------------------------+
// | config.php                                                               |
// |                                                                          |
// | FileMgmt configuration options                                           |
// +--------------------------------------------------------------------------+
// | $Id:: config.php 3274 2008-09-30 03:30:57Z mevans0263                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008 by the following authors:                             |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the FileMgmt Plugin for Geeklog                                 |
// | Copyright (C) 2004 by Consult4Hire Inc.                                  |
// | Author:                                                                  |
// | Blaine Lang            blaine@portalparts.com                            |
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

// Permissions that will be used for new files into the repository
$filemgmtFilePermissions = (int) 0755;

/*
 * Configuration for the auto-rename or reject logic when users are submitted
 * files for approval. Map any extensions to a new extension or 'reject' if
 * they should not be allowed. Any file type not listed will be uploaded using
 * original file extension.
 */

$_FMDOWNLOAD  = array(
    'php'   => 'phps',
    'pl'    => 'txt',
    'cgi'   => 'txt',
    'py'    => 'txt',
    'sh'    => 'txt',
    'html'  => 'txt',
    'htm'   => 'txt',
    'exe'   => 'reject'
);

$_FMDOWNLOAD['inconlib']    = array(
    'php'  => "php.gif",
    'phps' => "php.gif",
    'bmp'  => "bmp.gif",
    'gif'  => "gif.gif",
    'jpg'  => "jpg.gif",
    'html' => "htm.gif",
    'htm'  => "htm.gif",
    'mov'  => "mov.gif",
    'mp3'  => "mp3.gif",
    'pdf'  => "pdf.gif",
    'ppt'  => "ppt.gif",
    'tar'  => "zip.gif",
    'gz'   => "zip.gif",
    'zip'  => "zip.gif",
    'txt'  => "txt.gif",
    'doc'  => "doc.gif",
    'xls'  => "xls.gif",
    'mpp'  => "mpp.gif",
    'exe'  => "exe.gif",
    'swf'  => "swf.gif",
    'vsd'  => "visio.gif",
    'none' => "file.gif"
    );
?>