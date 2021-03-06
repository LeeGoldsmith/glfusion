<?php
// +--------------------------------------------------------------------------+
// | Forum Plugin for glFusion CMS                                            |
// +--------------------------------------------------------------------------+
// | index.php                                                                |
// |                                                                          |
// | Main Forum administration screen                                         |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2013 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Forum Plugin for Geeklog CMS                                |
// | Copyright (C) 2000-2008 by the following authors:                        |
// |                                                                          |
// | Authors: Blaine Lang       - blaine AT portalparts DOT com               |
// |                              www.portalparts.com                         |
// | Version 1.0 co-developer:    Matthew DeWyer, matt@mycws.com              |
// | Prototype & Concept :        Mr.GxBlock, www.gxblock.com                 |
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

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

if (!SEC_hasRights('forum.edit')) {
  $display = COM_siteHeader();
  $display .= COM_startBlock($LANG_GF00['access_denied']);
  $display .= $LANG_GF00['admin_only'];
  $display .= COM_endBlock();
  $display .= COM_siteFooter(true);
  echo $display;
  exit();
}

USES_forum_functions();
USES_forum_admin();

$display = FF_siteHeader();

$display .= FF_navbar($navbarMenu,$LANG_GF06['1']);

$display .= COM_startBlock($LANG_GF91['gfstats']);

// CATEGORIES
$numcats=DB_query("SELECT id FROM {$_TABLES['ff_categories']}");
$totalcats=DB_numRows($numcats);
// FORUMS
$numforums=DB_query("SELECT forum_id FROM {$_TABLES['ff_forums']}");
$totalforums=DB_numRows($numforums);
// TOPICS
$numtopics=DB_query("SELECT id FROM {$_TABLES['ff_topic']} WHERE pid = 0");
$totaltopics=DB_numRows($numtopics);
// POSTS
$numposts=DB_query("SELECT id FROM {$_TABLES['ff_topic']}");
$totalposts=DB_numRows($numposts);
// VIEWS
$numviews=DB_query("SELECT SUM(views) AS TOTAL FROM {$_TABLES['ff_topic']}");
$totalviews=DB_fetchArray($numviews);

// AVERAGE POSTS
if ($totalposts != 0) {
    $avgcposts = $totalposts / $totalcats;
    $avgcposts = round($avgcposts);
    $avgfposts = $totalposts / $totalforums;
    $avgfposts = round($avgfposts);
    $avgtposts = $totalposts / $totaltopics;
    $avgtposts = round($avgtposts);
} else {
    $avgcposts = 0;
    $avgfposts = 0;
    $avgtposts = 0;
}


// AVERAGE VIEWS
if ($totalviews['TOTAL'] != 0) {
    $avgcviews = $totalviews['TOTAL'] / $totalcats;
    $avgcviews = round($avgcviews);
    $avgfviews = $totalviews['TOTAL'] / $totalforums;
    $avgfviews = round($avgfviews);
    $avgtviews = $totalviews['TOTAL'] / $totaltopics;
    $avgtviews = round($avgtviews);
} else {
    $avgcviews = 0;
    $avgfviews = 0;
    $avgtviews = 0;
}

$indextemplate = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
$indextemplate->set_file ('indextemplate', 'index.thtml');

$indextemplate->set_var (array(
    'statsmsg'      => $LANG_GF91['statsmsg'],
    'totalcatsmsg'  => $LANG_GF91['totalcats'],
    'totalcats'     => $totalcats,
    'totalforumsmsg'=> $LANG_GF91['totalforums'],
    'totalforums'   => $totalforums,
    'totaltopicsmsg'=> $LANG_GF91['totaltopics'],
    'totaltopics'   => $totaltopics,
    'totalpostsmsg' => $LANG_GF91['totalposts'],
    'totalposts'    => $totalposts,
    'totalviewsmsg' => $LANG_GF91['totalviews'],
    'totalviews'    => $totalviews['TOTAL'],
    'category'      => $LANG_GF91['category'],
    'forum'         => $LANG_GF91['forum'],
    'topic'         => $LANG_GF91['topic'],
    'avgpmsg'       => $LANG_GF91['avgpmsg'],
    'avgcposts'     => $avgcposts,
    'avgfposts'     => $avgfposts,
    'avgtposts'     => $avgtposts,
    'avgvmsg'       => $LANG_GF91['avgvmsg'],
    'avgcviews'     => $avgcviews,
    'avgfviews'     => $avgfviews,
    'avgtviews'     => $avgtviews));

$indextemplate->parse ('output', 'indextemplate');
$display .= $indextemplate->finish ($indextemplate->get_var('output'));

$display .= COM_endBlock();
$display .= FF_adminfooter();
$display .= FF_siteFooter();
echo $display;
?>