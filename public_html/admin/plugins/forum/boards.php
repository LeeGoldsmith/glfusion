<?php
// +--------------------------------------------------------------------------+
// | Forum Plugin for glFusion CMS                                            |
// +--------------------------------------------------------------------------+
// | boards.php                                                               |
// |                                                                          |
// | Forum Plugin admin - Main program to setup Forums                        |
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
USES_forum_format();
USES_forum_admin();

$mode       = isset($_REQUEST['mode']) ? COM_applyFilter($_REQUEST['mode']) : '';
$type       = isset($_REQUEST['type']) ? COM_applyFilter($_REQUEST['type']) : '';
$confirm    = isset($_POST['confirm']) ? COM_applyFilter($_POST['confirm'],true) : 0;
$id         = isset($_POST['id']) ? COM_applyFilter($_POST['id'],true) : 0;
$catorder   = isset($_POST['catorder']) ? COM_applyFilter($_POST['catorder'],true) : 0;

$display = '';

$display .= FF_siteHeader();
$display .= FF_navbar($navbarMenu,$LANG_GF06['3']);
$display .= COM_startBlock($LANG_GF93['gfboard']);

$grouplist = '';
$ugrouplist = '';

// CATEGORY Maintenance Section
if ($type == "category") {
    if ($mode == 'add') {
        if ($confirm == 1) {
            $name = _ff_preparefordb($_POST['name'],'text');
            $dscp = _ff_preparefordb($_POST['dscp'],'text');
            if ( !empty($name) && $name != '' ) {
                DB_query("INSERT INTO {$_TABLES['ff_categories']} (cat_order,cat_name,cat_dscp) VALUES (".(int) $catorder.",'$name','$dscp')");
                $display .= FF_statusMessage($LANG_GF93['catadded'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['catadded']);
            } else {
                $display .= FF_statusMessage($LANG_GF93['catadderror'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['catadderror']);
            }
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        } else {
            $boards_addcategory = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
            $boards_addcategory->set_file ('boards_addcategory','boards_edtcategory.thtml');

            $boards_addcategory->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
            $boards_addcategory->set_var ('title', $LANG_GF93['addcat']);
            $boards_addcategory->set_var ('mode', 'add');
            $boards_addcategory->set_var ('confirm', '1');
            $boards_addcategory->set_var ('LANG_ADDNOTE', $LANG_GF93['addnote']);
            $boards_addcategory->set_var ('LANG_NAME', $LANG_GF01['NAME']);
            $boards_addcategory->set_var ('LANG_DESCRIPTION', $LANG_GF01['DESCRIPTION']);
            $boards_addcategory->set_var ('LANG_ORDER', $LANG_GF01['ORDER']);
            $boards_addcategory->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
            $boards_addcategory->set_var ('LANG_SAVE', $LANG_GF01['SUBMIT']);
            $boards_addcategory->parse ('output', 'boards_addcategory');
            $display .= $boards_addcategory->finish ($boards_addcategory->get_var('output'));
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        }

    } elseif ($mode == $LANG_GF01['DELETE']) {
        if ($confirm == 1) {
            DB_query("DELETE FROM {$_TABLES['ff_categories']} WHERE id=".(int) $id);
            $result = DB_query("SELECT * FROM {$_TABLES['ff_forums']} WHERE forum_cat=".(int) $id);
            while ($A = DB_fetchArray($result) ) {
                $fid = $A['forum_id'];
                ff_deleteForum($fid);
            }
            DB_query("DELETE FROM {$_TABLES['ff_forums']} WHERE forum_cat=".(int) $id);
            $display .= FF_statusMessage($LANG_GF93['catdeleted'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['catdeleted']);
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        } else {
            $catname = DB_getItem($_TABLES['ff_categories'], "cat_name","id=".(int) $id);
            $boards_delcategory = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
            $boards_delcategory->set_file (array ('boards_delcategory'=>'boards_delete.thtml'));
            $boards_delcategory->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
            $boards_delcategory->set_var ('deletenote1', sprintf($LANG_GF93['deletecatnote1'], $catname));
            $boards_delcategory->set_var ('id', $id);
            $boards_delcategory->set_var ('type', 'category');
            $boards_delcategory->set_var ('deletenote2', $LANG_GF93['deletecatnote2']);
            $boards_delcategory->set_var ('LANG_DELETE', $LANG_GF01['DELETE']);
            $boards_delcategory->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
            $boards_delcategory->parse ('output', 'boards_delcategory');
            $display .= $boards_delcategory->finish ($boards_delcategory->get_var('output'));
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        }

    } elseif  ($mode == 'save') {
        $name = _ff_preparefordb($_POST['name'],'text');
        $dscp = _ff_preparefordb($_POST['dscp'],'text');
        DB_query("UPDATE {$_TABLES['ff_categories']} SET cat_order=".(int) $catorder.",cat_name='$name',cat_dscp='$dscp' WHERE id=".(int) $id);
        $display .= FF_statusMessage($LANG_GF93['catedited'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['catedited']);
        $display .= COM_endBlock();
        $display .= FF_siteFooter();
        echo $display;
        exit();

    } elseif ($mode == $LANG_GF01['EDIT']) {
        $esql = DB_query("SELECT * FROM {$_TABLES['ff_categories']} WHERE id=".(int) $id);
        $E = DB_fetchArray($esql);
        $boards_edtcategory = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
        $boards_edtcategory->set_file (array ('boards_edtcategory'=>'boards_edtcategory.thtml'));
        $boards_edtcategory->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
        $boards_edtcategory->set_var ('title', sprintf($LANG_GF93['editcatnote'], $E['cat_name']));
        $boards_edtcategory->set_var ('catname', $E['cat_name']);
        $boards_edtcategory->set_var ('catorder', $E['cat_order']);
        $boards_edtcategory->set_var ('catdscp', $E['cat_dscp']);
        $boards_edtcategory->set_var ('id', $id);
        $boards_edtcategory->set_var ('mode', 'save');
        $boards_edtcategory->set_var ('confirm', '0');
        $boards_edtcategory->set_var ('LANG_NAME', $LANG_GF01['NAME']);
        $boards_edtcategory->set_var ('LANG_DESCRIPTION', $LANG_GF01['DESCRIPTION']);
        $boards_edtcategory->set_var ('LANG_ORDER', $LANG_GF01['ORDER']);
        $boards_edtcategory->set_var ('LANG_SAVE', $LANG_GF01['SAVE']);
        $boards_edtcategory->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
        $boards_edtcategory->parse ('output', 'boards_edtcategory');
        $display .= $boards_edtcategory->finish ($boards_edtcategory->get_var('output'));
        $display .= COM_endBlock();
        $display .= FF_adminfooter();
        $display .= FF_siteFooter();
        echo $display;
        exit();

    } elseif ($mode == $LANG_GF01['RESYNCCAT'])  {
        // Resync each forum in this category
        $query = DB_query("SELECT forum_id FROM {$_TABLES['ff_forums']} WHERE forum_cat=".(int) $id);
        while (list($forum_id) = DB_fetchArray($query)) {
            gf_resyncforum($forum_id);
        }
    }

}

// FORUM Maintenance Section
if ($type == "forum") {
    if ($mode == 'add') {
        if ($confirm == 1) {
            $category   = isset($_POST['category']) ? COM_applyFilter($_POST['category'],true) : 0;
            $order      = isset($_POST['order']) ? COM_applyFilter($_POST['order'],true) : 0;
            $name       = _ff_preparefordb($_POST['name'],'text');
            $dscp       = _ff_preparefordb($_POST['dscp'],'text');
            $is_readonly = isset($_POST['is_readonly']) ? COM_applyFilter($_POST['is_readonly'],true) : 0;
            $is_hidden = isset($_POST['is_hidden']) ? COM_applyFilter($_POST['is_hidden'],true) : 0;
            $no_newposts = isset($_POST['no_newposts']) ? COM_applyFilter($_POST['no_newposts'],true) : 0;
            $privgroup = isset($_POST['privgroup']) ? COM_applyFilter($_POST['privgroup'],true) : 0;
            if ($privgroup == 0) {
                $privgroup = 2;
            }
            $attachmentgroup = COM_applyFilter($_POST['attachmentgroup'],true);
            if ( $attachmentgroup == 0) $privgroup = 1;
            if (ff_addForum($name,$category,$dscp,$order,$privgroup,$is_readonly,$is_hidden,$no_newposts,$attachmentgroup) > 0 ) {
                $display .= FF_statusMessage($LANG_GF93['forumadded'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['forumadded']);
            } else {
                $display .= FF_statusMessage($LANG_GF93['forumaddError'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['forumaddError']);
            }
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        } else {
            $result = DB_query("SELECT DISTINCT grp_id, grp_name FROM {$_TABLES['groups']} ORDER BY grp_name");
            $nrows = DB_numRows($result);
            if ( $nrows > 0 ) {
                for ( $i = 1; $i <= $nrows; $i++ ) {
                    $G = DB_fetchArray($result);
                    if ( $G['grp_id'] == 2 ) {
                        $grouplist .= '<option value="' . $G['grp_id'] . '" selected="selected">' . $G['grp_name'] . '</option>';
                    } else {
                        $grouplist .= '<option value="' . $G['grp_id'] . '">' . $G['grp_name'] . '</option>';
                    }
                    if ( $G['grp_name'] == 'Root' ) {
                        $ugrouplist .= '<option value="' . $G['grp_id'] . '" selected="selected">' . $G['grp_name'] . '</option>';
                    } else {
                        $ugrouplist .= '<option value="' . $G['grp_id'] . '">' . $G['grp_name'] . '</option>';
                    }
                }
            }
            $catname = DB_getItem($_TABLES['ff_categories'], "cat_name","id=".(int) $id);
            $boards_addforum = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
            $boards_addforum->set_file (array ('boards_addforum'=>'boards_edtforum.thtml'));
            $boards_addforum->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
            $boards_addforum->set_var ('title', "{$LANG_GF93['addforum']}&nbsp;{$LANG_GF93['undercat']}&nbsp;" .$catname);
            $boards_addforum->set_var ('mode', 'add');
            $boards_addforum->set_var ('category_id', COM_applyFilter($_GET['category'],true));
            $boards_addforum->set_var ('cat_select','<input type="hidden" name="category" value="'.COM_applyFilter($_GET['category'],true).'"'.XHTML.'>');
            $boards_addforum->set_var ('lang_category','');
            $boards_addforum->set_var ('id', $id);
            $boards_addforum->set_var ('confirm', '1');
            $boards_addforum->set_var ('LANG_DESCRIPTION', $LANG_GF01['DESCRIPTION']);
            $boards_addforum->set_var ('LANG_NAME', $LANG_GF01['NAME']);
            $boards_addforum->set_var ('LANG_GROUPACCESS', $LANG_GF93['groupaccess']);
            $boards_addforum->set_var ('LANG_ATTACHACCESS', $LANG_GF93['attachaccess']);

            $boards_addforum->set_var ('LANG_readonly', $LANG_GF93['readonly']);
            $boards_addforum->set_var ('LANG_readonlydscp', $LANG_GF93['readonlydscp']);
            $boards_addforum->set_var ('LANG_hidden', $LANG_GF93['hidden']);
            $boards_addforum->set_var ('LANG_hiddendscp', $LANG_GF93['hiddendscp']);
            $boards_addforum->set_var ('LANG_hideposts', $LANG_GF93['hideposts']);
            $boards_addforum->set_var ('LANG_hidepostsdscp', $LANG_GF93['hidepostsdscp']);

            $boards_addforum->set_var ('grouplist', $grouplist);
            $boards_addforum->set_var ('attachmentgrouplist', $ugrouplist);

            $boards_addforum->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
            $boards_addforum->set_var ('LANG_SAVE', $LANG_GF01['SAVE']);
            $boards_addforum->parse ('output', 'boards_addforum');
            $display .= $boards_addforum->finish ($boards_addforum->get_var('output'));
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        }

    } elseif ($mode == $LANG_GF01['DELETE']) {
        if ($confirm == 1) {
            ff_deleteForum($id);
            $display .= FF_statusMessage($LANG_GF93['forumdeleted'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['forumdeleted']);
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        } else {
            $boards_delforum = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
            $boards_delforum->set_file (array ('boards_delforum'=>'boards_delete.thtml'));
            $boards_delforum->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
            $boards_delforum->set_var ('deletenote1', sprintf($LANG_GF93['deleteforumnote1'], COM_applyFilter($_POST['forumname'])));
            $boards_delforum->set_var ('deletenote2', $LANG_GF93['deleteforumnote2']);
            $boards_delforum->set_var ('id', $id);
            $boards_delforum->set_var ('type', 'forum');
            $boards_delforum->set_var ('LANG_DELETE', $LANG_GF01['DELETE']);
            $boards_delforum->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
            $boards_delforum->parse ('output', 'boards_delforum');
            $display .= $boards_delforum->finish ($boards_delforum->get_var('output'));
            $display .= COM_endBlock();
            $display .= FF_adminfooter();
            $display .= FF_siteFooter();
            echo $display;
            exit();
        }

    } elseif ($mode == $LANG_GF01['EDIT'] && (isset($_POST['what']) && COM_applyFilter($_POST['what']) == 'order')) {
        $order = COM_applyFilter($_POST['order'],true);
        DB_query("UPDATE {$_TABLES['ff_forums']} SET forum_order='$order' WHERE forum_id='$id'");
        $display .= FF_statusMessage($LANG_GF93['forumordered'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['forumordered']);
        $display .= COM_endBlock();
        $display .= FF_siteFooter();
        echo $display;
        exit();

    } elseif ($mode == 'save') {
        $name = _ff_preparefordb($_POST['name'],'text');
        $dscp = _ff_preparefordb($_POST['dscp'],'text');
        $privgroup = isset($_POST['privgroup']) ? COM_applyFilter($_POST['privgroup'],true) : 0;
        $is_readonly = isset($_POST['is_readonly']) ? COM_applyFilter($_POST['is_readonly'],true) : 0;
        $is_hidden = isset($_POST['is_hidden']) ? COM_applyFilter($_POST['is_hidden'],true) : 0;
        $no_newposts = isset($_POST['no_newposts']) ? COM_applyFilter($_POST['no_newposts'],true) : 0;
        $category    = isset($_POST['category']) ? COM_applyFilter($_POST['category'],true) : 0;
        if ($privgroup == 0) $privgroup = 2;
        $attachmentgroup = COM_applyFilter($_POST['attachmentgroup'],true);
        if ( $attachmentgroup == 0) $privgroup = 1;
        $sql = "UPDATE {$_TABLES['ff_forums']} SET forum_name='".DB_escapeString($name)."',forum_dscp='".DB_escapeString($dscp)."', grp_id=".(int) $privgroup.", ";
        $sql .= "is_hidden='".DB_escapeString($is_hidden)."', is_readonly='".DB_escapeString($is_readonly)."', no_newposts='".DB_escapeString($no_newposts)."',use_attachment_grpid=".(int) $attachmentgroup.",forum_cat=".(int) $category." ";
        $sql .= "WHERE forum_id=".(int) $id;
        DB_query($sql);
        $display .= FF_statusMessage($LANG_GF93['forumedited'],$_CONF['site_admin_url'] .'/plugins/forum/boards.php',$LANG_GF93['forumedited']);
        $display .= COM_endBlock();
        $display .= FF_siteFooter();
        echo $display;
        exit();

    } elseif ($mode == $LANG_GF01['RESYNC'])  {
        gf_resyncforum($id);

    } elseif ($mode == $LANG_GF01['EDIT']) {
        $sql  = "SELECT forum_name,forum_cat,forum_dscp,grp_id,use_attachment_grpid,forum_order,is_hidden,is_readonly,no_newposts ";
        $sql .= "FROM {$_TABLES['ff_forums']} WHERE forum_id=".(int) $id;
        $resForum  = DB_query($sql);
        list ($forum_name, $forum_category,$forum_dscp,$privgroup,$attachgroup,$forum_order,$is_hidden,$is_readonly,$no_newposts) = DB_fetchArray($resForum);
        $resGroups = DB_query("SELECT DISTINCT grp_id,grp_name FROM {$_TABLES['groups']} ORDER BY grp_name ASC ");
        $nrows     = DB_numRows($resGroups);
        $grouplist = '';
        $attachgrouplist = '';
        while ( list($grp, $name) = DB_fetchArray($resGroups)) {
            if ($grp == $privgroup) {
                $grouplist .= '<option value="' .$grp. '" selected="selected">' . $name. '</option>';
            } else {
                $grouplist .= '<option value="' .$grp. '">' . $name. '</option>';
            }
            if ($grp == $attachgroup) {
                $attachgrouplist .= '<option value="' .$grp. '" selected="selected">' . $name. '</option>';
            } else {
                $attachgrouplist .= '<option value="' .$grp. '">' . $name. '</option>';
            }
        }
        $catSelect = '<select name="category">';
        $catResult = DB_query("SELECT * FROM {$_TABLES['ff_categories']} ORDER BY cat_order ASC");
        while ( ($C = DB_fetchArray($catResult)) != FALSE ) {
            $catSelect .= '<option value="'.$C['id'].'" '.($C['id'] == $forum_category ? ' selected="selected"' : '').'>'.$C['cat_name'].'</option>';
        }
        $catSelect .= '</select>';

        $boards_edtforum = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
        $boards_edtforum->set_file (array ('boards_edtforum'=>'boards_edtforum.thtml'));
        $boards_edtforum->set_var ('phpself', $_CONF['site_admin_url'] .'/plugins/forum/boards.php');
        $boards_edtforum->set_var ('title', sprintf($LANG_GF93['editforumnote'], $forum_name));
        $boards_edtforum->set_var ('cat_select',$catSelect);
        $boards_edtforum->set_var ('lang_category',$LANG_GF01['category']);
        $boards_edtforum->set_var ('id', $id);
        $boards_edtforum->set_var ('mode', 'save');
        $boards_edtforum->set_var ('confirm', '0');
        $boards_edtforum->set_var ('category_id', $forum_category);
        $boards_edtforum->set_var ('forum_name', $forum_name);
        $boards_edtforum->set_var ('forum_dscp', $forum_dscp);
        $boards_edtforum->set_var ('forum_order', $forum_order);
        $boards_edtforum->set_var ('chk_hidden', ($is_hidden) ? 'checked="checked"' : '');
        $boards_edtforum->set_var ('chk_readonly', ($is_readonly) ? 'checked="checked"' : '');
        $boards_edtforum->set_var ('chk_newposts', ($no_newposts) ? 'checked="checked"' : '');
        $boards_edtforum->set_var ('LANG_DESCRIPTION', $LANG_GF01['DESCRIPTION']);
        $boards_edtforum->set_var ('LANG_NAME', $LANG_GF01['NAME']);
        $boards_edtforum->set_var ('LANG_GROUPACCESS', $LANG_GF93['groupaccess']);
        $boards_edtforum->set_var ('LANG_ATTACHACCESS', $LANG_GF93['attachaccess']);
        $boards_edtforum->set_var ('LANG_readonly', $LANG_GF93['readonly']);
        $boards_edtforum->set_var ('LANG_readonlydscp', $LANG_GF93['readonlydscp']);
        $boards_edtforum->set_var ('LANG_hidden', $LANG_GF93['hidden']);
        $boards_edtforum->set_var ('LANG_hiddendscp', $LANG_GF93['hiddendscp']);
        $boards_edtforum->set_var ('LANG_hideposts', $LANG_GF93['hideposts']);
        $boards_edtforum->set_var ('LANG_hidepostsdscp', $LANG_GF93['hidepostsdscp']);
        $boards_edtforum->set_var ('grouplist', $grouplist);
        $boards_edtforum->set_var ('attachmentgrouplist', $attachgrouplist);
        $boards_edtforum->set_var ('LANG_SAVE', $LANG_GF01['SAVE']);
        $boards_edtforum->set_var ('LANG_CANCEL', $LANG_GF01['CANCEL']);
        $boards_edtforum->parse ('output', 'boards_edtforum');
        $display .= $boards_edtforum->finish ($boards_edtforum->get_var('output'));
        $display .= COM_endBlock();
        $display .= FF_adminfooter();
        $display .= FF_siteFooter();
        echo $display;
        exit();
    }
}


// MAIN CODE

$boards = new Template($_CONF['path'] . 'plugins/forum/templates/admin/');
$boards->set_file ('boards','boards.thtml');

$boards->set_var (array(
        'phpself'       => $_CONF['site_admin_url'] .'/plugins/forum/boards.php',
        'siteurl'       => $_CONF['site_url'],
        'adminurl'      => $_CONF['site_admin_url'],
        'addcat'        => $LANG_GF93['addcat'],
        'phpself'       => $_CONF['site_admin_url'] .'/plugins/forum/boards.php',
        'cat'           => $LANG_GF01['category'],
        'edit'          => $LANG_GF01['EDIT'],
        'delete'        => $LANG_GF01['DELETE'],
        'topic'         => $LANG_GF01['TOPIC'],
        'LANG_posts'    => $LANG_GF93['posts'],
        'LANG_order'    => $LANG_GF93['ordertitle'],
        'catorder'      => $LANG_GF93['catorder'],
        'LANG_action'   => $LANG_GF93['action'],
        'LANG_forumdesc'=> $LANG_GF93['forumdescription'],
        'addforum'      => $LANG_GF93['addforum'],
        'addcat'        => $LANG_GF93['addcat'],
        'description'   => $LANG_GF01['DESCRIPTION'],
        'resync'        => $LANG_GF01['RESYNC'],
        'edit'          => $LANG_GF01['EDIT'],
        'resync_cat'    => $LANG_GF01['RESYNCCAT'],
        'delete'        => $LANG_GF01['DELETE'],
        'submit'        => $LANG_GF01['SUBMIT']
));

/* Display each Forum Category */
$asql = DB_query("SELECT * FROM {$_TABLES['ff_categories']} ORDER BY cat_order");
while ($A = DB_fetchArray($asql)) {
    $boards->set_var ('catid', $A['id']);
    $boards->set_var ('catname', $A['cat_name']);
    $boards->set_var ('order', $A['cat_order']);

    /* Display each forum within this category */
    $bsql = DB_query("SELECT * FROM {$_TABLES['ff_forums']} WHERE forum_cat=".(int) $A['id']." ORDER BY forum_order");
    $bnrows = DB_numRows($bsql);

    $boards->set_block('boards', 'catrows', 'crow');
    $boards->clear_var('frow');
    $boards->set_block('boards', 'forumrows', 'frow');

    for ($j = 1; $j <= $bnrows; $j++) {
        $B = DB_fetchArray($bsql);
        $boards->set_var (array(
                'forumname' => $B['forum_name'],
                'forumid'   => $B['forum_id'],
                'messagecount'  => $B['post_count']
        ));

        /* Check if this is a private forum */
        if ($B['grp_id'] != '2') {
            $grp_name = DB_getItem($_TABLES['groups'],'grp_name', "grp_id=".(int) $B['grp_id']);
            $boards->set_var ('forumdscp', "[{$LANG_GF93['private']}&nbsp;-&nbsp;{$grp_name}]<br/>{$B['forum_dscp']}");
        } else {
            $boards->set_var ('forumdscp', $B['forum_dscp']);
        }
        $boards->set_var ('forumorder', $B['forum_order']);
        $boards->parse('frow', 'forumrows',true);
    }
    $boards->parse('crow', 'catrows',true);
}

$boards->parse ('output', 'boards');
$display .= $boards->finish ($boards->get_var('output'));

$display .= COM_endBlock();
$display .= FF_adminfooter();
$display .= FF_siteFooter();
echo $display;
?>