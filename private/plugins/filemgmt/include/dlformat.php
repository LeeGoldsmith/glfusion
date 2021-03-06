<?php
// +--------------------------------------------------------------------------+
// | FileMgmt Plugin - glFusion CMS                                           |
// +--------------------------------------------------------------------------+
// | dlformat.php                                                             |
// |                                                                          |
// | Enhancements to display formatting                                       |
// +--------------------------------------------------------------------------+
// | $Id:: dlformat.php 3155 2008-09-16 02:13:18Z mevans0263                 $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2002-2011 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the FileMgmt Plugin for Geeklog                                 |
// | Copyright (C) 2004 by Consult4Hire Inc.                                  |
// | Author:                                                                  |
// | Blaine Lang            blaine@portalparts.com                            |
// |                                                                          |
// | Based on:                                                                |
// | myPHPNUKE Web Portal System - http://myphpnuke.com/                      |
// | PHP-NUKE Web Portal System - http://phpnuke.org/                         |
// | Thatware - http://thatware.org/                                          |
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

// this file can't be used on its own
if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

$dt = new Date('now',$_USER['tzid']);


$path = $mytree->getPathFromId($cid, "title");
$path = substr($path, 1);
$path = str_replace("/"," <img src='" .$_CONF['site_url'] ."/filemgmt/images/arrow.gif' alt=''" . XHTML . "> ",$path);

$p->set_var('LANG_CATEGORY',_MD_CATEGORYC);
$p->set_var('category_path',$path);

if (empty($submitter_name)) {
    $submitter_name = 'Unknown UID';
} else {
    $submitter_name = "<a href='" . $_CONF['site_url'] . "/users.php?mode=profile&amp;uid=" . $submitter . "'>" .$submitter_name. "</a>";
}
$p->set_var('LANG_SUBMITTEDBY',_MD_SUBMITTEDBY);
$p->set_var('submitter_name',$submitter_name);
$p->set_var('lid',$lid);
$p->set_var('LANG_DLNOW',_MD_DLNOW);
$p->set_var('dtitle',$dtitle);
$p->set_var('image_newdownload',newdownloadgraphic($time, $status));
$p->set_var('image_popular', popgraphic($hits));

$p->set_var('download_title', _MD_CLICK2DL . urldecode($url));
$p->set_var('url',$url);
$p->set_var('file_description',$description);

if ( $_FM_CONF['enable_rating'] ) {
    if ( $rating!="0" || $rating!="0.00" ) {
        $votestring = sprintf(_MD_NUMVOTES,$votes);
        $p->set_var('rating',$rating);
        $p->set_var('votestring', $votestring);
    } else {
        $p->set_var('rating',$rating);
        $p->set_var('votestring', '');
    }
}
if ($logourl != '') {
    $p->set_var('snapshot_icon','<img src="'.$_CONF['site_url'] .'/filemgmt/images/screenshoticon.gif" width="14" height="14" alt="" border="0" />');
    $p->set_var('snapshot_url',$filemgmt_FileSnapURL . $logourl);
    $p->set_var('LANG_CLICK2SEE', _MD_CLICK2SEE.$logourl);
    $p->set_var('show_snapshoticon','');
    $p->set_var('show_snapshoticon_na','none');
} else {
    $p->set_var('show_snapshoticon','none');
    $p->set_var('show_snapshoticon_na','');
    $p->set_var('snapshot_icon','');
    $p->set_var('snapshot_url','');
    $p->set_var('LANG_CLICK2SEE','');
}

$p->set_var('LANG_MD_SCREENSHOT_NA', _MD_SCREENSHOT_NA);
$p->set_var('LANG_VERSION', _MD_VERSION);
if ( $_FM_CONF['enable_rating'] ) {
    $p->set_var('LANG_RATING', _MD_RATINGC);
}
$p->set_var('LANG_SUBMITDATE', _MD_SUBMITDATE);
$p->set_var('datetime',$datetime);
$p->set_var('version',$version);

// Check if restricted access has been enabled for download report to admin's only
if (($hits > 0 && !$mydownloads_dlreport) || ( $hits > 0 && SEC_hasRights('filemgmt.edit'))) {
    $p->set_var('begin_dlreport_link',"<a href=\"{$_CONF['site_url']}/filemgmt/downloadhistory.php?lid=$lid\" target=\"_blank\">");
    $p->set_var('end_dlreport_link','</a>');
} else {
    $p->set_var('begin_dlreport_link','');
    $p->set_var('end_dlreport_link','');
}
$p->set_var('download_times',sprintf(_MD_DLTIMES,$hits));
$p->set_var('download_count',$hits);
$p->set_var('LANG_FILESIZE',_MD_FILESIZE);
$pos = MBYTE_strpos( $url, ':' );
if( $pos === false ) {
    $p->set_var('file_size',PrettySize($size));
} else {
    if ( $size != 0 ) {
        $p->set_var('file_size',PrettySize($size));
    } else {
        $p->set_var('file_size','Remote');
    }
}
$p->set_var('homepage_url',$homepage);
$p->set_var('LANG_HOMEPAGE',_MD_HOMEPAGE);
$p->set_var('homepage',$homepage);

if ($comments) {
    $commentCount=DB_count($_TABLES['comments'],'sid',"fileid_$lid");
    $recentPostMessage =_MD_COMMENTSWANTED;
    if ($commentCount > 0) {
        $result4 = DB_query("SELECT cid, UNIX_TIMESTAMP(date) AS day,username FROM {$_TABLES['comments']},{$_TABLES['users']} WHERE {$_TABLES['users']}.uid = {$_TABLES['comments']}.uid AND sid = 'fileid_$lid' ORDER BY date desc LIMIT 1");
        $C = DB_fetchArray($result4);
        $dt->setTimestamp($C['day']);
        $recentPostMessage = $LANG01[27].': '.$dt->format($_CONF['daytime'],true). ' ' . $LANG01[104] . ' ' . $C['username'];
        $comment_link = '<a href="' .$_CONF['site_url'] .'/filemgmt/index.php?id=' .$lid.'" title="'.$recentPostMessage.'">' .$commentCount.'&nbsp;' .$LANG01[3]. '</a>';
    } else {
        $comment_link = '<a href="' .$_CONF['site_url'] . '/comment.php?type=filemgmt&amp;sid=fileid_' .$lid.'" title="'.$recentPostMessage.'">' . _MD_ENTERCOMMENT . '</a>';
    }
    $p->set_var('comment_link',$comment_link);
    $p->set_var('show_comments','');
} else {
    $p->set_var('show_comments','none');
}

$p->set_var('LANG_DOWNLOAD',_MD_DOWNLOAD);
$p->set_var('LANG_FILELINK',_MD_FILELINK);
$p->set_var('LANG_RATETHISFILE',_MD_RATETHISFILE);
$p->set_var('LANG_REPORTBROKEN',_MD_REPORTBROKEN);

if ($FilemgmtAdmin) {
    $p->set_var('LANG_EDIT', _MD_EDIT);
    $p->set_var('show_editlink','');
} else {
    $p->set_var('LANG_EDIT', '');
    $p->set_var('show_editlink','none');
}

$static = false;
$voted  = 0;
if ( COM_isAnonUser() ) {
        $static = true;
        $voted = 1;
} else if (isset($_USER['uid']) && $_USER['uid'] == $submitter ) {
    $static = true;
    $voted = 0;
} else {
    if ( @in_array($lid,$FM_ratedIds)) {
        $static = true;
        $voted = 1;
    } else {
        $static = 0;
        $voted = 0;
    }
}

if ( $_FM_CONF['enable_rating'] ) {
    $rating_box = RATING_ratingBar( 'filemgmt',$lid, $votes,$rating, $voted ,5,$static,'sm');
    $p->set_var('rating_bar',$rating_box);
}
?>