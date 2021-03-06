<?php
// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | users.php                                                                |
// |                                                                          |
// | User authentication module.                                              |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009-2012 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// | Mark A. Howard         mark AT usable-web DOT com                        |
// |                                                                          |
// | Based on the Geeklog CMS                                                 |
// | Copyright (C) 2000-2008 by the following authors:                        |
// |                                                                          |
// | Authors: Tony Bibbs        - tony AT tonybibbs DOT com                   |
// |          Mark Limburg      - mlimburg AT users DOT sourceforge DOT net   |
// |          Jason Whittenburg - jwhitten AT securitygeeks DOT com           |
// |          Dirk Haun         - dirk AT haun-online DOT de                  |
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

/**
* This file handles user authentication
*
* @author   Tony Bibbs <tony@tonybibbs.com>
* @author   Mark Limburg <mlimburg@users.sourceforge.net>
* @author   Jason Whittenburg
*
*/

/**
* glFusion common function library
*/
require_once 'lib-common.php';
USES_lib_user();

if ( !isset($_SYSTEM['verification_token_ttl']) ) {
    $_SYSTEM['verification_token_ttl'] = 86400;
}

/**
* Shows a profile for a user
*
* This grabs the user profile for a given user and displays it
*
* @param    int     $user   User ID of profile to get
* @param    int     $msg    Message to display (if != 0)
* @param    string  $plugin optional plugin name for message
* @return   string          HTML for user profile page
*
*/
function userprofile($user, $msg = 0, $plugin = '')
{
    global $_CONF, $_TABLES, $_USER, $LANG01, $LANG04, $LANG09, $LANG28, $LANG_LOGIN;

    $retval = '';
    if (COM_isAnonUser() &&
        (($_CONF['loginrequired'] == 1) || ($_CONF['profileloginrequired'] == 1))) {
        $retval .= COM_siteHeader ('menu', $LANG_LOGIN[1]);
        $retval .= SEC_loginRequiredForm();
        $retval .= COM_siteFooter ();

        return $retval;
    }

    $result = DB_query ("SELECT {$_TABLES['users']}.uid,username,fullname,regdate,lastlogin,homepage,about,location,pgpkey,photo,email,status,emailfromadmin,emailfromuser,showonline FROM {$_TABLES['userinfo']},{$_TABLES['userprefs']},{$_TABLES['users']} WHERE {$_TABLES['userinfo']}.uid = {$_TABLES['users']}.uid AND {$_TABLES['userinfo']}.uid = {$_TABLES['userprefs']}.uid AND {$_TABLES['users']}.uid = ".(int) $user);
    $nrows = DB_numRows ($result);
    if ($nrows == 0) { // no such user
        return COM_refresh ($_CONF['site_url'] . '/index.php');
    }
    $A = DB_fetchArray ($result);

    if ($A['status'] == USER_ACCOUNT_DISABLED && !SEC_hasRights ('user.edit')) {
        COM_displayMessageAndAbort (30, '', 403, 'Forbidden');
    }

    $display_name = htmlspecialchars(COM_getDisplayName($user, $A['username'],$A['fullname']),ENT_COMPAT,COM_getEncodingt());

    $retval .= COM_siteHeader ('menu', $LANG04[1] . ' ' . $display_name);
    if ($msg > 0) {
        $retval .= COM_showMessage($msg, $plugin,'',0,'info');
    }

    // format date/time to user preference
    $curtime = COM_getUserDateTimeFormat ($A['regdate']);
    $A['regdate'] = $curtime[0];

    $user_templates = new Template ($_CONF['path_layout'] . 'users');
    $user_templates->set_file (array ('profile' => 'profile.thtml',
                                      'email'   => 'email.thtml',
                                      'row'     => 'commentrow.thtml',
                                      'strow'   => 'storyrow.thtml'));
    $user_templates->set_var ('layout_url', $_CONF['layout_url']);
    $user_templates->set_var ('start_block_userprofile',
            COM_startBlock ($LANG04[1] . ' ' . $display_name));
    $user_templates->set_var ('end_block', COM_endBlock ());
    $user_templates->set_var ('lang_username', $LANG04[2]);

    if ($_CONF['show_fullname'] == 1) {
        if (empty ($A['fullname'])) {
            $username = $A['username'];
            $fullname = '';
        } else {
            $username = $A['fullname'];
            $fullname = $A['username'];
        }
    } else {
        $username = $A['username'];
        $fullname = '';
    }
    $username = @htmlspecialchars($username,ENT_COMPAT,COM_getEncodingt());
    $fullname = @htmlspecialchars($fullname,ENT_COMPAT,COM_getEncodingt());

    if ($A['status'] == USER_ACCOUNT_DISABLED) {
        $username = sprintf ('%s - %s', $username, $LANG28[42]);
        if (!empty ($fullname)) {
            $fullname = sprintf ('% - %s', $fullname, $LANG28[42]);
        }
    }

    $user_templates->set_var ('username', $username);
    $user_templates->set_var ('user_fullname', $fullname);

    if (SEC_hasRights('user.edit') || (isset($_USER['uid']) && $_USER['uid'] == $A['uid'])) {
        global $_IMAGE_TYPE, $LANG_ADMIN;

        $edit_icon = '<img src="' . $_CONF['layout_url'] . '/images/edit.'
                   . $_IMAGE_TYPE . '" alt="' . $LANG_ADMIN['edit']
                   . '" title="' . $LANG_ADMIN['edit'] . '" />';
        if ($_USER['uid'] == $A['uid']) {
            $edit_url = "{$_CONF['site_url']}/usersettings.php";
        } else {
            $edit_url = "{$_CONF['site_admin_url']}/user.php?edit=x&amp;uid={$A['uid']}";
        }

        $edit_link_url = COM_createLink($edit_icon, $edit_url);
        $user_templates->set_var('edit_icon', $edit_icon);
        $user_templates->set_var('edit_link', $edit_link_url);
        $user_templates->set_var('user_edit', $edit_url);
    } else {
        $user_templates->set_var('user_edit', '');
    }

    if (isset ($A['photo']) && empty ($A['photo'])) {
        $A['photo'] = '(none)'; // user does not have a photo
    }

    $lastlogin = $A['lastlogin'];
    $lasttime = COM_getUserDateTimeFormat ($lastlogin);

    $photo = USER_getPhoto ($user, $A['photo'], $A['email'], -1,0);
    $user_templates->set_var ('user_photo', $photo);

    $user_templates->set_var ('lang_membersince', $LANG04[67]);
    $user_templates->set_var ('user_regdate', $A['regdate']);

    if ($_CONF['lastlogin'] && $A['showonline']) {
        $user_templates->set_var('lang_lastlogin', $LANG28[35]);
        if ( !empty($lastlogin) ) {
            $user_templates->set_var('user_lastlogin', $lasttime[0]);
        } else {
            $user_templates->set_var('user_lastlogin', $LANG28[36]);
        }
    }

    if ($A['showonline']) {
        if ( DB_count($_TABLES['sessions'],'uid',(int) $user)) {
            $user_templates->set_var ('online', 'online');
        }
    }

    $user_templates->set_var ('lang_email', $LANG04[5]);
    $user_templates->set_var ('user_id', $user);

    if ( $A['email'] == '' || $A['emailfromuser'] == 0 ) {
        $user_templates->set_var ('email_option', '');
    } else {
        $user_templates->set_var ('lang_sendemail', $LANG04[81]);
        $user_templates->parse ('email_option', 'email', true);
    }

    $user_templates->set_var ('lang_homepage', $LANG04[6]);
    $user_templates->set_var ('user_homepage', COM_killJS ($A['homepage']));
    $user_templates->set_var ('lang_location', $LANG04[106]);
    $user_templates->set_var ('user_location', strip_tags ($A['location']));
    $user_templates->set_var ('lang_online', $LANG04[160]);
    $user_templates->set_var ('lang_bio', $LANG04[7]);
    $user_templates->set_var ('user_bio', nl2br ($A['about']));
    $user_templates->set_var ('lang_pgpkey', $LANG04[8]);
    $user_templates->set_var ('user_pgp', nl2br ($A['pgpkey']));
    $user_templates->set_var ('start_block_last10stories',
            COM_startBlock ($LANG04[82] . ' ' . $display_name));
    $user_templates->set_var ('start_block_last10comments',
            COM_startBlock($LANG04[10] . ' ' . $display_name));
    $user_templates->set_var ('start_block_postingstats',
            COM_startBlock ($LANG04[83] . ' ' . $display_name));
    $user_templates->set_var ('lang_title', $LANG09[16]);
    $user_templates->set_var ('lang_date', $LANG09[17]);

    // for alternative layouts: use these as headlines instead of block titles
    $user_templates->set_var ('headline_last10stories', $LANG04[82] . ' ' . $display_name);
    $user_templates->set_var ('headline_last10comments', $LANG04[10] . ' ' . $display_name);
    $user_templates->set_var ('headline_postingstats', $LANG04[83] . ' ' . $display_name);

    $result = DB_query ("SELECT tid FROM {$_TABLES['topics']}" . COM_getPermSQL ());
    $nrows = DB_numRows ($result);
    $tids = array ();
    for ($i = 0; $i < $nrows; $i++) {
        $T = DB_fetchArray ($result);
        $tids[] = $T['tid'];
    }
    $topics = "'" . implode ("','", $tids) . "'";

    // list of last 10 stories by this user
    if (sizeof ($tids) > 0) {
        $sql = "SELECT sid,title,UNIX_TIMESTAMP(date) AS unixdate FROM {$_TABLES['stories']} WHERE (uid = '".(int) $user."') AND (draft_flag = 0) AND (date <= NOW()) AND (tid IN ($topics))" . COM_getPermSQL ('AND');
        $sql .= " ORDER BY unixdate DESC LIMIT 10";
        $result = DB_query ($sql);
        $nrows = DB_numRows ($result);
    } else {
        $nrows = 0;
    }
    if ($nrows > 0) {
        for ($i = 0; $i < $nrows; $i++) {
            $C = DB_fetchArray ($result);
            $user_templates->set_var ('cssid', ($i % 2) + 1);
            $user_templates->set_var ('row_number', ($i + 1) . '.');
            $articleUrl = COM_buildUrl ($_CONF['site_url']
                                        . '/article.php?story=' . $C['sid']);
            $user_templates->set_var ('article_url', $articleUrl);
            $C['title'] = str_replace ('$', '&#36;', $C['title']);
            $user_templates->set_var ('story_title',
                COM_createLink(
                    $C['title'],
                    $articleUrl,
                    array ('class'=>''))
            );
            $storytime = COM_getUserDateTimeFormat ($C['unixdate']);
            $user_templates->set_var ('story_date', $storytime[0]);
            $user_templates->parse ('story_row', 'strow', true);
        }
    } else {
        $user_templates->set_var ('story_row',
                                  '<tr><td>' . $LANG01[37] . '</td></tr>');
    }

    // list of last 10 comments by this user
    $sidArray = array();
    if (sizeof ($tids) > 0) {
        // first, get a list of all stories the current visitor has access to
        $sql = "SELECT sid FROM {$_TABLES['stories']} WHERE (draft_flag = 0) AND (date <= NOW()) AND (tid IN ($topics))" . COM_getPermSQL ('AND');
        $result = DB_query($sql);
        $numsids = DB_numRows($result);
        for ($i = 1; $i <= $numsids; $i++) {
            $S = DB_fetchArray ($result);
            $sidArray[] = $S['sid'];
        }
    }

    $sidList = implode("', '",$sidArray);
    $sidList = "'$sidList'";

    // then, find all comments by the user in those stories
    $sql = "SELECT sid,title,cid,UNIX_TIMESTAMP(date) AS unixdate FROM {$_TABLES['comments']} WHERE (uid = '".(int) $user."') GROUP BY sid,title,cid,UNIX_TIMESTAMP(date)";

    // SQL NOTE:  Using a HAVING clause is usually faster than a where if the
    // field is part of the select
    // if (!empty ($sidList)) {
    //     $sql .= " AND (sid in ($sidList))";
    // }
    if (!empty ($sidList)) {
        $sql .= " HAVING sid in ($sidList)";
    }
    $sql .= " ORDER BY unixdate DESC LIMIT 10";

    $result = DB_query($sql);
    $nrows = DB_numRows($result);
    if ($nrows > 0) {
        for ($i = 0; $i < $nrows; $i++) {
            $C = DB_fetchArray ($result);
            $user_templates->set_var ('cssid', ($i % 2) + 1);
            $user_templates->set_var ('row_number', ($i + 1) . '.');
            $C['title'] = str_replace ('$', '&#36;', $C['title']);
            $comment_url = $_CONF['site_url'] .
                    '/comment.php?mode=view&amp;cid=' . $C['cid'];
            $user_templates->set_var ('comment_title',
                COM_createLink(
                    $C['title'],
                    $comment_url,
                    array ('class'=>''))
            );
            $commenttime = COM_getUserDateTimeFormat ($C['unixdate']);
            $user_templates->set_var ('comment_date', $commenttime[0]);
            $user_templates->parse ('comment_row', 'row', true);
        }
    } else {
        $user_templates->set_var('comment_row','<tr><td>' . $LANG01[29] . '</td></tr>');
    }

    // posting stats for this user
    $user_templates->set_var ('lang_number_stories', $LANG04[84]);
    $sql = "SELECT COUNT(*) AS count FROM {$_TABLES['stories']} WHERE (uid = ".(int) $user.") AND (draft_flag = 0) AND (date <= NOW())" . COM_getPermSQL ('AND');
    $result = DB_query($sql);
    $N = DB_fetchArray ($result);
    $user_templates->set_var ('number_stories', COM_numberFormat ($N['count']));
    $user_templates->set_var ('lang_number_comments', $LANG04[85]);
    $sql = "SELECT COUNT(*) AS count FROM {$_TABLES['comments']} WHERE (uid = ".(int) $user.")";
    if (!empty ($sidList)) {
        $sql .= " AND (sid in ($sidList))";
    }
    $result = DB_query ($sql);
    $N = DB_fetchArray ($result);
    $user_templates->set_var ('number_comments', COM_numberFormat($N['count']));
    $user_templates->set_var ('lang_all_postings_by',
                              $LANG04[86] . ' ' . $display_name);

    // hook to the profile icon display

    $profileIcons = PLG_profileIconDisplay($user);
    if ( is_array($profileIcons) && count($profileIcons) > 0 ) {
	    $user_templates->set_block('profile', 'profileicon', 'pi');
        for ($x=0;$x<count($profileIcons);$x++) {
            if ( isset($profileIcons[$x]['url']) && $profileIcons[$x]['url'] != '' && isset($profileIcons[$x]['icon']) && $profileIcons[$x]['icon'] != '' ) {
                $user_templates->set_var('profile_icon_url',$profileIcons[$x]['url']);
                $user_templates->set_var('profile_icon_icon',$profileIcons[$x]['icon']);
                $user_templates->set_var('profile_icon_text',$profileIcons[$x]['text']);
                $user_templates->parse('pi', 'profileicon',true);
            }
        }
    }

    // Call custom registration function if enabled and exists
    if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userDisplay') ) {
        $user_templates->set_var ('customfields', CUSTOM_userDisplay ($user));
    }
    PLG_profileVariablesDisplay ($user, $user_templates);

    $user_templates->parse ('output', 'profile');
    $retval .= $user_templates->finish ($user_templates->get_var ('output'));

    $retval .= PLG_profileBlocksDisplay ($user);
    $retval .= COM_siteFooter ();

    return $retval;
}

/**
* Emails password to a user
*
* This will email the given user their password.
*
* @param    string      $username       Username for which to get and email password
* @param    string      $passwd         Unencrypted password (optional)
* @param    int         $msg            Message number of message to show when done
* @return   string      Optionally returns the HTML for the default form if the user info can't be found
*
*/
function emailpassword ($username, $passwd = '', $msg = 0)
{
    global $_CONF, $_TABLES, $LANG04;

    $retval = '';

    $username = DB_escapeString ($username);
    // don't retrieve any remote users!
//    $result = DB_query ("SELECT uid,email,status FROM {$_TABLES['users']} WHERE username = '".$username."' AND ((remoteservice is null) OR (remoteservice = ''))");
    $result = DB_query ("SELECT uid,email,status FROM {$_TABLES['users']} WHERE username = '".$username."' AND (account_type & ".LOCAL_USER.")");
    $nrows = DB_numRows ($result);
    if ($nrows == 1) {
        $A = DB_fetchArray ($result);
        if (($_CONF['usersubmission'] == 1) && ($A['status'] == USER_ACCOUNT_AWAITING_APPROVAL)) {
            return COM_refresh ($_CONF['site_url'] . '/index.php?msg=48');
        }

        $mailresult = USER_createAndSendPassword ($username, $A['email'], $A['uid'],$passwd);

        if ($mailresult == false) {
            $retval = COM_refresh ("{$_CONF['site_url']}/index.php?msg=85");
        } else if ($msg) {
            $retval = COM_refresh ("{$_CONF['site_url']}/index.php?msg=$msg");
        } else {
            if ($_CONF['registration_type'] == 1 ) {
                $retval = COM_refresh ("{$_CONF['site_url']}/index.php?msg=3");
            } else {
                $retval = COM_refresh ("{$_CONF['site_url']}/index.php?msg=1");
            }
        }
    } else {
        $retval = COM_siteHeader ('menu','')
                . defaultform ('')
                . COM_siteFooter ();
    }

    return $retval;
}

/**
* User request for a new password - send email with a link and request id
*
* @param username string   name of user who requested the new password
* @param msg      int      index of message to display (if any)
* @return         string   form or meta redirect
*
*/
function requestpassword ($username, $msg = 0)
{
    global $_CONF, $_TABLES, $LANG04;

    $retval = '';

    // no remote users!
    $username = DB_escapeString($username);
//    $result = DB_query ("SELECT uid,email,passwd,status FROM {$_TABLES['users']} WHERE username = '".$username."' AND ((remoteservice IS NULL) OR (remoteservice=''))");
    $result = DB_query ("SELECT uid,email,passwd,status FROM {$_TABLES['users']} WHERE username = '".$username."' AND (account_type & ".LOCAL_USER.")");
    $nrows = DB_numRows ($result);
    if ($nrows == 1) {
        $A = DB_fetchArray ($result);
        if (($_CONF['usersubmission'] == 1) && ($A['status'] == USER_ACCOUNT_AWAITING_APPROVAL)) {
            return COM_refresh ($_CONF['site_url'] . '/index.php?msg=48');
        }
        $reqid = substr (md5 (uniqid (rand (), 1)), 1, 16);
        DB_change ($_TABLES['users'], 'pwrequestid', "$reqid",'uid', (int) $A['uid']);

        $mailtext = sprintf ($LANG04[88], $username);
        $mailtext .= $_CONF['site_url'] . '/users.php?mode=newpwd&uid=' . $A['uid'] . '&rid=' . $reqid . "\n\n";
        $mailtext .= $LANG04[89];
        $mailtext .= "{$_CONF['site_name']}\n";
        $mailtext .= "{$_CONF['site_url']}\n";

        $subject = $_CONF['site_name'] . ': ' . $LANG04[16];
        if ($_CONF['site_mail'] !== $_CONF['noreply_mail']) {
            $mailfrom = $_CONF['noreply_mail'];
            global $LANG_LOGIN;
            $mailtext .= LB . LB . $LANG04[159];
        } else {
            $mailfrom = $_CONF['site_mail'];
        }
        $to = array();
        $to = COM_formatEmailAddress('',$A['email']);
        $from = array();
        $from = COM_formatEmailAddress('',$mailfrom);
        COM_mail ($to, $subject, $mailtext, $from);

        if ($msg) {
            $retval .= COM_refresh ($_CONF['site_url'] . "/index.php?msg=$msg");
        } else {
            $retval .= COM_refresh ($_CONF['site_url'] . '/index.php');
        }
        COM_updateSpeedlimit ('password');
    } else {
        COM_updateSpeedlimit ('password');
        echo COM_refresh ($_CONF['site_url']
                                . '/users.php?mode=getpassword');
        exit;
    }

    return $retval;
}

/**
* Display a form where the user can enter a new password.
*
* @param uid       int      user id
* @param requestid string   request id for password change
* @return          string   new password form
*
*/
function newpasswordform ($uid, $requestid)
{
    global $_CONF, $_TABLES, $LANG04;

    $pwform = new Template ($_CONF['path_layout'] . 'users');
    $pwform->set_file ('newpw','newpassword.thtml');
    $pwform->set_var (array(
            'user_id'       => $uid,
            'user_name'     => DB_getItem ($_TABLES['users'], 'username',"uid = ".(int)$uid),
            'request_id'    => $requestid,
            'lang_explain'  => $LANG04[90],
            'lang_username' => $LANG04[2],
            'lang_newpassword'  => $LANG04[4],
            'lang_newpassword_conf' => $LANG04[108],
            'lang_setnewpwd'    => $LANG04[91]));

    $retval = COM_startBlock ($LANG04[92]);
    $retval .= $pwform->finish ($pwform->parse ('output', 'newpw'));
    $retval .= COM_endBlock ();

    return $retval;
}

/**
* User request for a verification token - send email with a link and request id
*
* @param uid      int      userid of user who requested the new token
* @param msg      int      index of message to display (if any)
* @return         string   form or meta redirect
*
*/
function requesttoken ($uid, $msg = 0)
{
    global $_CONF, $_SYSTEM, $_TABLES, $LANG04;

    if ( !isset($_SYSTEM['verification_token_ttl']) ) {
        $_SYSTEM['verification_token_ttl'] = 86400;
    }

    $retval = '';
    // no remote users!
    $uid = (int) $uid;
//    $result = DB_query ("SELECT uid,username,email,passwd,status FROM {$_TABLES['users']} WHERE uid = ".(int)$uid." AND ((remoteservice IS NULL) OR (remoteservice=''))");
    $result = DB_query ("SELECT uid,username,email,passwd,status FROM {$_TABLES['users']} WHERE uid = ".(int)$uid." AND (account_type & ".LOCAL_USER.")");
    $nrows = DB_numRows ($result);
    if ($nrows == 1) {
        $A = DB_fetchArray ($result);
        if (($_CONF['usersubmission'] == 1) && ($A['status'] == USER_ACCOUNT_AWAITING_APPROVAL)) {
            return COM_refresh ($_CONF['site_url'] . '/index.php?msg=48');
        }
        $verification_id = USER_createActivationToken($uid,$A['username']);
        $activation_link = $_CONF['site_url'].'/users.php?mode=verify&vid='.$verification_id.'&u='.$uid;
        $mailtext  = $LANG04[168] . $_CONF['site_name'] . ".\n\n";
        $mailtext .= $LANG04[170] . "\n\n";
        $mailtext .= "----------------------------\n";
        $mailtext .= $LANG04[2] . ': ' . $A['username'] ."\n";
        $mailtext .= $LANG04[171] .': ' . $_CONF['site_url'] ."\n";
        $mailtext .= "----------------------------\n\n";
        $mailtext .= sprintf($LANG04[172],($_SYSTEM['verification_token_ttl']/3600)) . "\n\n";
        $mailtext .= $activation_link . "\n\n";
        $mailtext .= $LANG04[173] . "\n\n";
        $mailtext .= $LANG04[174] . "\n\n";
        $mailtext .= "--\n";
        $mailtext .= $_CONF['site_name'] . "\n";
        $mailtext .= $_CONF['site_url'] . "\n";

        $subject = $_CONF['site_name'] . ': ' . $LANG04[16];
        if ($_CONF['site_mail'] !== $_CONF['noreply_mail']) {
            $mailfrom = $_CONF['noreply_mail'];
            global $LANG_LOGIN;
            $mailtext .= LB . LB . $LANG04[159];
        } else {
            $mailfrom = $_CONF['site_mail'];
        }
        $to = array();
        $to = COM_formatEmailAddress('',$A['email']);
        $from = array();
        $from = COM_formatEmailAddress('',$mailfrom);
        COM_mail ($to, $subject, $mailtext, $from);

        if ($msg) {
            $retval .= COM_refresh ($_CONF['site_url'] . "/index.php?msg=$msg");
        } else {
            $retval .= COM_refresh ($_CONF['site_url'] . '/index.php');
        }
        COM_updateSpeedlimit ('verifytoken');
    } else {
        COM_updateSpeedlimit ('verifytoken');
        echo COM_refresh ($_CONF['site_url'] .'/users.php?mode=getnewtoken' );
        exit;
    }
    return $retval;
}

/**
* Display a form where the user can request a new token.
*
* @param uid       int      user id
* @return          string   new token form
*
*/
function newtokenform ($uid)
{
    global $_CONF, $_TABLES, $LANG04;

    $tokenform = new Template ($_CONF['path_layout'] . 'users');
    $tokenform->set_file ('newtoken', 'newtoken.thtml');
    $tokenform->set_var (array(
            'user_id'       => $uid,
            'lang_explain'  => $LANG04[175],
            'lang_username' => $LANG04[2],
            'lang_password' => $LANG04[4],
            'lang_submit'   => $LANG04[169]));

    $retval = COM_startBlock ($LANG04[169]);
    $retval .= $tokenform->finish ($tokenform->parse ('output', 'newtoken'));
    $retval .= COM_endBlock ();

    return $retval;
}

/**
* Creates a user
*
* Creates a user with the give username and email address
*
* @param    string      $username       username to create user for
* @param    string      $email          email address to assign to user
* @param    string      $email_conf     confirmation email address check
* @param    string      $passwd         password
* @param    string      $passwd_conf    confirmation password check
* @return   string      HTML for the form again if error occurs, otherwise nothing.
*
*/
function createuser ($username, $email, $email_conf, $passwd='', $passwd_conf='')
{
    global $_CONF, $_TABLES, $LANG01, $LANG04, $MESSAGE;

    $retval = '';

    $username   = COM_truncate(trim ($username),48);

    if ( !USER_validateUsername($username)) {
        $retval .= COM_siteHeader ('menu', $LANG04[22]);
        if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userForm')) {
            $retval .= CUSTOM_userForm ($msg);
        } else {
            $retval .= newuserform ($LANG04[162]);
        }
        $retval .= COM_siteFooter();

        return $retval;
    }

    $email      = COM_truncate(trim ($email),96);
    $email_conf = trim ($email_conf);

    if ( $_CONF['registration_type'] == 1 ) {
        if ( empty($passwd) || ($passwd != $passwd_conf) ) {
            $retval .= COM_siteHeader('menu',$LANG04[22]);
            if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userForm')) {
                $retval .= CUSTOM_userForm($MESSAGE[67]);
            } else {
                $retval .= newuserform($MESSAGE[67]);
            }
            $retval .= COM_siteFooter();
            return $retval;
        }
    }

    $fullname = '';
    if (!empty ($_POST['fullname'])) {
        $fullname   = COM_truncate(trim(USER_sanitizeName($_POST['fullname'])),80);
    }

    if (!isset ($_CONF['disallow_domains'])) {
        $_CONF['disallow_domains'] = '';
    }

    if (COM_isEmail ($email) && !empty ($username) && ($email === $email_conf)
            && !USER_emailMatches ($email, $_CONF['disallow_domains'])
            && (strlen ($username) <= 48)) {

        $ucount = DB_count ($_TABLES['users'], 'username',
                            DB_escapeString ($username));
        $ecount = DB_count ($_TABLES['users'], 'email', DB_escapeString ($email));

        if ($ucount == 0 AND $ecount == 0) {

            // For glFusion, it would be okay to create this user now. But check
            // with a custom userform first, if one exists.
            if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userCheck')) {
                $msg = CUSTOM_userCheck ($username, $email);
                if (!empty ($msg)) {
                    // no, it's not okay with the custom userform
                    $retval = COM_siteHeader ('menu')
                            . CUSTOM_userForm ($msg)
                            . COM_siteFooter ();

                    return $retval;
                }
            }

            // Let plugins have a chance to decide what to do before creating the user, return errors.
            $msg = PLG_itemPreSave ('registration', $username);
            if (!empty ($msg)) {
                $retval .= COM_siteHeader ('menu', $LANG04[22]);
                if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userForm')) {
                    $retval .= CUSTOM_userForm ($msg);
                } else {
                    $retval .= newuserform ($msg);
                }
                $retval .= COM_siteFooter();

                return $retval;
            }
            if ( $_CONF['registration_type'] == 1 && !empty($passwd) ) {
                $encryptedPasswd = SEC_encryptPassword($passwd);
            } else {
                $encryptedPasswd = '';
            }

            $uid = USER_createAccount ($username, $email, $encryptedPasswd, $fullname);

            if ($_CONF['usersubmission'] == 1) {
                if (DB_getItem ($_TABLES['users'], 'status', "uid = ".(int) $uid)
                        == USER_ACCOUNT_AWAITING_APPROVAL) {
                    $retval = COM_refresh ($_CONF['site_url']
                                           . '/index.php?msg=48');
                } else {
                    $retval = emailpassword ($username, $passwd, 1);
                }
            } else {
                $retval = emailpassword ($username,$passwd);
            }

            return $retval;
        } else {
            $retval .= COM_siteHeader ('menu', $LANG04[22]);
            if ($_CONF['custom_registration'] &&
                    function_exists ('CUSTOM_userForm')) {
                $retval .= CUSTOM_userForm ($LANG04[19]);
            } else {
                $retval .= newuserform ($LANG04[19]);
            }
            $retval .= COM_siteFooter ();
        }
    } else if ($email !== $email_conf) {
        $msg = $LANG04[125];
        $retval .= COM_siteHeader ('menu', $LANG04[22]);
        if ($_CONF['custom_registration'] && function_exists('CUSTOM_userForm')) {
            $retval .= CUSTOM_userForm ($msg);
        } else {
            $retval .= newuserform ($msg);
        }
        $retval .= COM_siteFooter();
    } else { // invalid username or email address

        if ((empty ($username)) || (strlen($username) > 48)) {
            $msg = $LANG01[32]; // invalid username
        } else {
            $msg = $LANG04[18]; // invalid email address
        }
        $retval .= COM_siteHeader ('menu', $LANG04[22]);
        if ($_CONF['custom_registration'] && function_exists('CUSTOM_userForm')) {
            $retval .= CUSTOM_userForm ($msg);
        } else {
            $retval .= newuserform ($msg);
        }
        $retval .= COM_siteFooter();
    }

    return $retval;
}

/**
* Shows the user login form after failed attempts to either login or access a page
* requiring login.
*
* @return   string      HTML for login form
*
*/
function loginform ($hide_forgotpw_link = false, $statusmode = -1)
{
    global $_CONF, $LANG04;

    $options = array(
        'hide_forgotpw_link' => $hide_forgotpw_link,
        'form_action'        => $_CONF['site_url'].'/users.php',
    );

    if ($statusmode == USER_ACCOUNT_DISABLED) {
        $options['title']   = $LANG04[114]; // account disabled
        $options['message'] = $LANG04[115]; // your account has been disabled, you may not login
        $options['forgotpw_link']      = false;
        $options['newreg_link']        = false;
        $options['verification_link']  = false;
    } elseif ($statusmode == USER_ACCOUNT_AWAITING_APPROVAL) {
        $options['title']   = $LANG04[116]; // account awaiting activation
        $options['message'] = $LANG04[117]; // your account is currently awaiting activation by an admin
        $options['forgotpw_link']      = false;
        $options['newreg_link']        = false;
        $options['verification_link']  = false;
    } elseif ($statusmode == USER_ACCOUNT_AWAITING_VERIFICATION ) {
        $options['title']   = $LANG04[116]; // account awaiting activation
        $options['message'] = $LANG04[177]; // your account is currently awaiting verification
        $options['forgotpw_link']      = false;
        $options['newreg_link']        = false;
        $options['verification_link']  = true;
    } elseif ($statusmode == -1) { // invalid credentials
        $options['title']   = $LANG04[65]; // log in to {site_name}
        $options['message'] = $LANG04[113]; // login attempt failed
    } else {
        $options['title']   = $LANG04[65]; // log in to {site_name}
        $options['message'] = $LANG04[66]; // please enter your user name and password below
    }

    return SEC_loginForm($options);
}

/**
* Shows the user registration form
*
* @param    int     $msg        message number to show
* @param    string  $referrer   page to send user to after registration
* @return   string  HTML for user registration page
*/
function newuserform ($msg = '')
{
    global $_CONF, $LANG01, $LANG04;

    $retval = '';

    if (!empty ($msg)) {
        $retval .= COM_showMessageText($msg,$LANG04[21],false,'info');
    }
    $user_templates = new Template($_CONF['path_layout'] . 'users');
    $user_templates->set_file('regform', 'registrationform.thtml');
    $user_templates->set_var('start_block', COM_startBlock($LANG04[22]));
    $user_templates->set_var('lang_instructions', $LANG04[23]);
    $user_templates->set_var('lang_username', $LANG04[2]);
    $user_templates->set_var('lang_fullname', $LANG04[3]);
    $user_templates->set_var('lang_email', $LANG04[5]);
    $user_templates->set_var('lang_email_conf', $LANG04[124]);
//FIX ME
// we may want to check for submission queue and add some more info about once the admin approves...
    if ( $_CONF['registration_type'] == 1 ) { // verification link
        $user_templates->set_var('lang_passwd',$LANG01[57]);
        $user_templates->set_var('lang_passwd_conf',$LANG04[176]);
        $user_templates->set_var('lang_warning',$LANG04[167]);
    } else {
        $user_templates->set_var('lang_warning', $LANG04[24]);
    }

    $user_templates->set_var('lang_register', $LANG04[27]);
    PLG_templateSetVars ('registration', $user_templates);
    $user_templates->set_var('end_block', COM_endBlock());

    $username = '';
    if (!empty ($_POST['username'])) {
        $username = trim ( $_POST['username'] );
    }
    $user_templates->set_var ('username', @htmlentities($username,ENT_COMPAT,COM_getEncodingt()));

    $fullname = '';
    if (!empty ($_POST['fullname'])) {
        $fullname = $_POST['fullname'];
    }
    $fullname = USER_sanitizeName($fullname);

    $user_templates->set_var ('fullname', @htmlentities($fullname,ENT_COMPAT,COM_getEncodingt()));
    switch ($_CONF['user_reg_fullname']) {
    case 2:
        $user_templates->set_var('require_fullname', 'true');
    case 1:
        $user_templates->set_var('show_fullname', 'true');
    }

    $email = '';
    if (!empty ($_POST['email'])) {
        $email = COM_applyFilter ($_POST['email']);
    }
    $user_templates->set_var ('email', $email);

    $email_conf = '';
    if (!empty ($_POST['email_conf'])) {
        $email_conf = COM_applyFilter ($_POST['email_conf']);
    }
    $user_templates->set_var ('email_conf', $email_conf);


    $user_templates->parse('output', 'regform');
    $retval .= $user_templates->finish($user_templates->get_var('output'));

    return $retval;
}

/**
* Shows the password retrieval form
*
* @return   string  HTML for form used to retrieve user's password
*
*/
function getpasswordform()
{
    global $_CONF, $LANG04;

    $retval = '';

    $user_templates = new Template($_CONF['path_layout'] . 'users');
    $user_templates->set_file('form', 'getpasswordform.thtml');
    $user_templates->set_var('start_block_forgetpassword', COM_startBlock($LANG04[25]));
    $user_templates->set_var('lang_instructions', $LANG04[26]);
    $user_templates->set_var('lang_username', $LANG04[2]);
    $user_templates->set_var('lang_email', $LANG04[5]);
    $user_templates->set_var('lang_emailpassword', $LANG04[28]);
    $user_templates->set_var('end_block', COM_endBlock());
    $user_templates->parse('output', 'form');

    $retval .= $user_templates->finish($user_templates->get_var('output'));

    return $retval;
}

/**
* Account does not exist - show both the login and register forms
*
* @param    string  $msg        message to display if one is needed
* @return   string  HTML for form
*
*/
function defaultform ($msg)
{
    global $LANG04, $_CONF;

    $retval = '';

    if (!empty ($msg)) {
        $retval .= COM_showMessageText($msg,$LANG04[21],false,'info');
    }

    $retval .= loginform (true);

    if ( $_CONF['disable_new_user_registration'] == FALSE ) {
        $retval .= newuserform ();
    }

    $retval .= getpasswordform ();

    return $retval;
}

/**
* Display message after a login error
*
* @param    int     $msg            message number for custom handler
* @param    string  $message_title  title for the message box
* @param    string  $message_text   text of the message box
* @return   void                    function does not return!
*
*/
function displayLoginErrorAndAbort($msg, $message_title, $message_text)
{
    global $_CONF;

    if ($_CONF['custom_registration'] &&
            function_exists('CUSTOM_loginErrorHandler')) {
        // Typically this will be used if you have a custom main site page
        // and need to control the login process
        CUSTOM_loginErrorHandler($msg);
    } else {
        @header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        @header('Status: 403 Forbidden');
        $retval = COM_siteHeader('menu', $message_title)
                . COM_showMessageText($message_text,$message_title,true,'error')
                . COM_siteFooter();
        echo $retval;
    }

    // don't return
    exit();
}

function mergeAccountForm($msg='')
{
    global $_USER, $_CONF, $_TABLES;

    // we should have the user's email in $_USER['email'];
    $remoteEmail = $_USER['email'];
    $sql = "SELECT * FROM {$_TABLES['users']} WHERE (remoteservice='' OR remoteservice IS NULL) AND email='".DB_escapeString($remoteEmail)."' AND uid > 1";
    $result = DB_query($sql);
    $numRows = DB_numRows($result);
    if ( $numRows > 0 ) {
        // we have the potential to merge
        $row = DB_fetchArray($result);
        $T = new Template($_CONF['path_layout'].'/users/');
        $T->set_file('merge','mergeacct.thtml');
        $T->set_var(array(
            'localuid'       => $row['uid'],
            'local_username' => $row['username'],
        ));
        $T->parse( 'page', 'merge' );
        $page = $T->finish( $T->get_var( 'page' ));
        $display = COM_siteHeader();
        if ( $msg != '' ) {
            $display .= COM_showMessageText($msg,'',false,'info');
        }
        $display .= $page;
        $display .= COM_siteFooter();
        return $display;
    } else {
        echo COM_refresh($_CONF['site_url'].'/index.php');
        exit;
    }
}

/*
 *
 */
function mergeaccounts()
{
    global $_CONF, $_SYSTEM, $local_login, $_TABLES, $_USER, $LANG20;

/*
 To merge the user accounts we need all attributes from both accounts
 There are several tables:

    - users
    - userprefs
    - userindex
    - usercomment
    - userinfo

  We will also need for plugins to have either move user or merge
  user support. Basically, we would want to move everything from
  one user to another. We would need to change ownership, move
  preferences, etc.

  If we do it at the time the account is created, there is no
  reason to worry about plugins - we can focus on just
  populating the remoteusername and remoteservice

  We may want to look at a new field that denotes the user type
    - local = 1
    - remote = 2
    - merged = 3

  this might help with the group stuff.

*/
    // need to add error checks to ensure everything passed

    $remoteUID = $_USER['uid'];    // remote
    $localUID  = COM_applyFilter($_POST['localuid'],true);
    $localpwd  = $_POST['localpasswd'];

    $remoteResult = DB_query("SELECT * FROM {$_TABLES['users']} WHERE uid=".(int) $remoteUID);
    $remoteRow    = DB_fetchArray($remoteResult);
    $localResult  = DB_query("SELECT * FROM {$_TABLES['users']} WHERE uid=".(int) $localUID);
    $localRow     = DB_fetchArray($localResult);

    if ( SEC_check_hash($localpwd, $localRow['passwd']) ) {
        // password is good
        $sql = "UPDATE {$_TABLES['users']} SET remoteusername='".
               DB_escapeString($remoteRow['remoteusername']) . "'," .
               "remoteservice='".DB_escapeString($remoteRow['remoteservice'])."', ".
               "account_type=3 ".
               " WHERE uid=".(int)$localUID;
        DB_query($sql);

        $_USER['uid'] = $localRow['uid'];

        SESS_completeLogin($localUID);
        $_GROUPS = SEC_getUserGroups( $_USER['uid'] );
        $_RIGHTS = explode( ',', SEC_getUserPermissions() );
        if ($_SYSTEM['admin_session'] > 0 && $local_login ) {
            if (SEC_isModerator() || SEC_hasRights('story.edit,block.edit,topic.edit,user.edit,plugin.edit,user.mail,syndication.edit','OR')
                     || (count(PLG_getAdminOptions()) > 0)) {
                $admin_token = SEC_createTokenGeneral('administration',$_SYSTEM['admin_session']);
                SEC_setCookie('token',$admin_token,0,$_CONF['cookie_path'],$_CONF['cookiedomain'],$_CONF['cookiesecure'],true);
            }
        }
        COM_resetSpeedlimit('login');

        // log the user out
        SESS_endUserSession ($remoteUID);

        // Ok, now delete everything related to this user

        // let plugins update their data for this user
        PLG_deleteUser ($remoteUID);

        if ( function_exists('CUSTOM_userDeleteHook')) {
            CUSTOM_userDeleteHook($remoteUID);
        }

        // Call custom account profile delete function if enabled and exists
        if ($_CONF['custom_registration'] && function_exists ('CUSTOM_userDelete')) {
            CUSTOM_userDelete ($remoteUID);
        }

        // remove from all security groups
        DB_delete ($_TABLES['group_assignments'], 'ug_uid', $remoteUID);

        // remove user information and preferences
        DB_delete ($_TABLES['userprefs'], 'uid', $remoteUID);
        DB_delete ($_TABLES['userindex'], 'uid', $remoteUID);
        DB_delete ($_TABLES['usercomment'], 'uid', $remoteUID);
        DB_delete ($_TABLES['userinfo'], 'uid', $remoteUID);

        // avoid having orphand stories/comments by making them anonymous posts
        DB_query ("UPDATE {$_TABLES['comments']} SET uid = 1 WHERE uid = $remoteUID");
        DB_query ("UPDATE {$_TABLES['stories']} SET uid = 1 WHERE uid = $remoteUID");
        DB_query ("UPDATE {$_TABLES['stories']} SET owner_id = 1 WHERE owner_id = $remoteUID");

        // delete story submissions
        DB_delete ($_TABLES['storysubmission'], 'uid', $remoteUID);

        // delete user photo, if enabled & exists
        if ($_CONF['allow_user_photo'] == 1) {
            $photo = DB_getItem ($_TABLES['users'], 'photo', "uid = $remoteUID");
            USER_deletePhoto ($photo, false);
        }

        // delete subscriptions
        DB_delete($_TABLES['subscriptions'],'uid',$remoteUID);

        // in case the user owned any objects that require Admin access, assign
        // them to the Root user with the lowest uid
        $rootgroup = DB_getItem ($_TABLES['groups'], 'grp_id', "grp_name = 'Root'");
        $result = DB_query ("SELECT DISTINCT ug_uid FROM {$_TABLES['group_assignments']} WHERE ug_main_grp_id = '$rootgroup' ORDER BY ug_uid LIMIT 1");
        $A = DB_fetchArray ($result);
        $rootuser = $A['ug_uid'];
        if ( $rootuser == '' || $rootuser < 2 ) {
            $rootuser = 2;
        }
        DB_query ("UPDATE {$_TABLES['blocks']} SET owner_id = $rootuser WHERE owner_id = $remoteUID");
        DB_query ("UPDATE {$_TABLES['topics']} SET owner_id = $rootuser WHERE owner_id = $remoteUID");
        // now delete the user itself
        DB_delete ($_TABLES['users'], 'uid', $remoteUID);
    } else {
        // invalid password - let's try one more time
        // need to set speed limit and give them 3 tries
        COM_clearSpeedlimit($_CONF['login_speedlimit'], 'login');
        $last = COM_checkSpeedlimit ('login_speedlimit',4);
        if ($last > 0) {
            $display .= COM_showMessageText(sprintf ($LANG04[93], $last, $_CONF['passwordspeedlimit']),$LANG12[26],false,'error');
        } else {
            COM_updateSpeedlimit ('login');
            $display = mergeAccountForm($LANG20[3]);
            echo $display;
            exit;
        }
    }
    echo COM_refresh($_CONF['site_url'].'/index.php');
    exit;
}


// MAIN
if ( isset($_POST['mode']) ) {
    $mode = $_POST['mode'];
} elseif (isset($_GET['mode']) ) {
    $mode = $_GET['mode'];
} else {
    $mode = '';
}

$display = '';

switch ($mode) {
case 'logout':
    if (!empty ($_USER['uid']) AND $_USER['uid'] > 1) {
        DB_query("UPDATE {$_TABLES['users']} set remote_ip='' WHERE uid=".$_USER['uid'],1);
        SESS_endUserSession ($_USER['uid']);
        PLG_logoutUser ($_USER['uid']);
    }
    SEC_setCookie ($_CONF['cookie_session'], '', time() - 10000,
                   $_CONF['cookie_path'], $_CONF['cookiedomain'],
                   $_CONF['cookiesecure'],true);
    SEC_setCookie ($_CONF['cookie_password'], '', time() - 10000,
                   $_CONF['cookie_path'], $_CONF['cookiedomain'],
                   $_CONF['cookiesecure'],true);
    SEC_setCookie ($_CONF['cookie_name'], '', time() - 10000,
                   $_CONF['cookie_path'], $_CONF['cookiedomain'],
                   $_CONF['cookiesecure'],true);
    if ( isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        DB_delete($_TABLES['tokens'],'token',DB_escapeString($token));
        SEC_setCookie ('token', '', time() - 10000,
                       $_CONF['cookie_path'], $_CONF['cookiedomain'],
                       $_CONF['cookiesecure'],true);

    }
    DB_delete($_TABLES['tokens'],'owner_id',(int) $_USER['uid']);
    $display = COM_refresh($_CONF['site_url'] . '/index.php?msg=8');
    break;

case 'profile':
    $uid = COM_applyFilter ($_GET['uid'], true);
    if (is_numeric ($uid) && ($uid > 1)) {
        $msg = 0;
        if (isset ($_GET['msg'])) {
            $msg = COM_applyFilter ($_GET['msg'], true);
        }
        $plugin = '';
        if (($msg > 0) && isset($_GET['plugin'])) {
            $plugin = COM_applyFilter($_GET['plugin']);
        }
        $display .= userprofile($uid, $msg, $plugin);
    } else {
        $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
    }
    break;

case 'user':
    $username = $_GET['username'];
    if ( !USER_validateUsername($username) ) {
        $username = '';
    }
    if (!empty ($username) && $username != '') {
        $username = DB_escapeString ($username);
        $uid = DB_getItem ($_TABLES['users'], 'uid', "username = '$username'");
        if ($uid > 1) {
            $display .= userprofile ($uid);
        } else {
            $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
        }
    } else {
        $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
    }
    break;

case 'create':
    if ($_CONF['disable_new_user_registration']) {
        $display .= COM_siteHeader ('menu', $LANG04[22]);
        $display .= COM_showMessageText($LANG04[122],$LANG04[22],true,'error');
        $display .= COM_siteFooter ();
    } else {
        $passwd = '';
        $passwd_conf = '';

        $email = COM_applyFilter ($_POST['email']);
        $email_conf = COM_applyFilter ($_POST['email_conf']);
        $newusername = $_POST['username'];
        if ( isset($_POST['passwd']) ) {
            $passwd = trim($_POST['passwd']);
        }
        if ( isset($_POST['passwd_conf']) ) {
            $passwd_conf = trim($_POST['passwd_conf']);
        }
        $display .= createuser($newusername, $email, $email_conf, $passwd, $passwd_conf);
    }
    break;

case 'getpassword':
    $display .= COM_siteHeader ('menu', $LANG04[25]);
    if ($_CONF['passwordspeedlimit'] == 0) {
        $_CONF['passwordspeedlimit'] = 300; // 5 minutes
    }
    COM_clearSpeedlimit ($_CONF['passwordspeedlimit'], 'password');
    $last = COM_checkSpeedlimit ('password',4);
    if ($last > 0) {
        $display .= COM_showMessageText(sprintf ($LANG04[93], $last, $_CONF['passwordspeedlimit']),$LANG12[26],false,'error');
    } else {
        $display .= getpasswordform ();
    }
    $display .= COM_siteFooter ();
    break;

case 'newpwd':
    $uid = COM_applyFilter ($_GET['uid'], true);
    $reqid = COM_applyFilter ($_GET['rid']);
    if (!empty ($uid) && is_numeric ($uid) && ($uid > 1) &&
            !empty ($reqid) && (strlen ($reqid) == 16)) {
        $uid = (int) $uid;
        $safereqid = DB_escapeString($reqid);
        $valid = DB_count ($_TABLES['users'], array ('uid', 'pwrequestid'),
                           array ($uid, $safereqid));
        if ($valid == 1) {
            $display .= COM_siteHeader ('menu', $LANG04[92]);
            $display .= newpasswordform ($uid, $reqid);
            $display .= COM_siteFooter ();
        } else { // request invalid or expired
            $display .= COM_siteHeader ('menu', $LANG04[25]);
            $display .= COM_showMessage (54,'','',1,'error');
            $display .= getpasswordform ();
            $display .= COM_siteFooter ();
        }
    } else {
        // this request doesn't make sense - ignore it
        $display = COM_refresh ($_CONF['site_url']);
    }
    break;

case 'setnewpwd':
    if ( (empty ($_POST['passwd']))
            or ($_POST['passwd'] != $_POST['passwd_conf']) ) {
        $display = COM_refresh ($_CONF['site_url']
                 . '/users.php?mode=newpwd&amp;uid=' . COM_applyFilter($_POST['uid'],true)
                 . '&amp;rid=' . COM_applyFilter($_POST['rid']));
    } else {
        $uid = COM_applyFilter ($_POST['uid'], true);
        $reqid = COM_applyFilter ($_POST['rid']);
        if (!empty ($uid) && is_numeric ($uid) && ($uid > 1) &&
                !empty ($reqid) && (strlen ($reqid) == 16)) {
            $uid = (int) $uid;
            $safereqid = DB_escapeString($reqid);
            $valid = DB_count ($_TABLES['users'], array ('uid', 'pwrequestid'),
                               array ($uid, $safereqid));
            if ($valid == 1) {
                $passwd = SEC_encryptPassword($_POST['passwd']);
                DB_change ($_TABLES['users'], 'passwd', DB_escapeString($passwd),"uid", $uid);
                DB_delete ($_TABLES['sessions'], 'uid', $uid);
                DB_change ($_TABLES['users'], 'pwrequestid', "NULL",'uid', $uid);
                $display = COM_refresh ($_CONF['site_url'] . '/users.php?msg=53');
            } else { // request invalid or expired
                $display .= COM_siteHeader ('menu', $LANG04[25]);
                $display .= COM_showMessage (54,'','',1,'error');
                $display .= getpasswordform ();
                $display .= COM_siteFooter ();
            }
        } else {
            // this request doesn't make sense - ignore it
            $display = COM_refresh ($_CONF['site_url']);
        }
    }
    break;

case 'emailpasswd':
    if ($_CONF['passwordspeedlimit'] == 0) {
        $_CONF['passwordspeedlimit'] = 300; // 5 minutes
    }
    COM_clearSpeedlimit ($_CONF['passwordspeedlimit'], 'password');
    $last = COM_checkSpeedlimit ('password');
    if ($last > 0) {
        $display .= COM_siteHeader ('menu', $LANG12[26])
                 . COM_showMessageText(sprintf ($LANG04[93], $last, $_CONF['passwordspeedlimit']),$LANG12[26],true,'error')
                 . COM_siteFooter ();
    } else {
        $username = $_POST['username'];
        $email = COM_applyFilter ($_POST['email']);
        if (empty ($username) && !empty ($email)) {
            $username = DB_getItem ($_TABLES['users'], 'username',
                                    "email = '".DB_escapeString($email)."' AND ((remoteservice IS NULL) OR (remoteservice = ''))");
        }
        if (!empty ($username)) {
            $display .= requestpassword ($username, 55);
        } else {

            echo COM_refresh ($_CONF['site_url'].'/users.php?mode=getpassword');
            exit;
        }
    }
    break;

case 'new':
    $display .= COM_siteHeader ('menu', $LANG04[22]);
    if ($_CONF['disable_new_user_registration']) {
        $display .= COM_showMessageText($LANG04[122],$LANG04[22],true,'error');
    } else {
        // Call custom registration and account record create function
        // if enabled and exists
        if ($_CONF['custom_registration'] AND (function_exists('CUSTOM_userForm'))) {
            $display .= CUSTOM_userForm();
        } else {
            $display .= newuserform();
        }
    }
    $display .= COM_siteFooter();
    break;

case 'verify':
    $uid    = (int) COM_applyFilter ($_GET['u'], true);
    $vid    = COM_applyFilter ($_GET['vid']);

    if (!empty ($uid) && is_numeric ($uid) && ($uid > 1) &&
            !empty ($vid) && (strlen ($vid) == 32)) {
        $uid = (int) $uid;
        $safevid = DB_escapeString($vid);
        $result = DB_query("SELECT UNIX_TIMESTAMP(act_time) AS act_time FROM {$_TABLES['users']} WHERE uid=".$uid." AND act_token='".$safevid."' AND status=".USER_ACCOUNT_AWAITING_VERIFICATION);
        if ( DB_numRows($result) != 1 ) {
            $valid = 0;
        } else {
            $U = DB_fetchArray($result);
            if ( $U['act_time'] != '' && $U['act_time'] > (time() - $_SYSTEM['verification_token_ttl']) ) {
                $valid = 1;
            } else {
                $valid = 0;
            }
        }
        if ($valid == 1) {
            DB_query("UPDATE {$_TABLES['users']} SET status=".USER_ACCOUNT_AWAITING_ACTIVATION.",act_time='0000-00-00 00:00:00' WHERE uid=".$uid);
            $display .= COM_siteHeader ('menu');
            $display .= COM_showMessage (515,'','',0,'success');
            $display .= SEC_loginForm();
            $display .= COM_siteFooter ();
        } else { // request invalid or expired
            $result = DB_query("SELECT * FROM {$_TABLES['users']} WHERE uid=".$uid);
            $display .= COM_siteHeader ('menu');
            if ( DB_numRows($result) == 1 ) {
                $U = DB_fetchArray($result);
                switch ($U['status']) {
                    case USER_ACCOUNT_AWAITING_ACTIVATION :
                    case USER_ACCOUNT_ACTIVE :
                        $display .= COM_showMessage(517,'','',0,'info');
                        $display .= SEC_loginForm();
                        break;
                    case USER_ACCOUNT_AWAITING_VERIFICATION :
                        $display .= COM_showMessage(516,'','',1,'error');
                        $display .= newtokenform($uid);
                        break;
                    default :
                        echo COM_refresh($_CONF['site_url']);
                        exit;
                }
                $display .= COM_siteFooter();
            } else {
                $display = COM_refresh ($_CONF['site_url']);
            }
        }
    } else {
        // this request doesn't make sense - ignore it
        $display = COM_refresh ($_CONF['site_url']);
    }

    break;

case 'getnewtoken':
    $uid = 0;
    if ($_CONF['passwordspeedlimit'] == 0) {
        $_CONF['passwordspeedlimit'] = 300; // 5 minutes
    }
    COM_clearSpeedlimit ($_CONF['passwordspeedlimit'], 'verifytoken');
    $last = COM_checkSpeedlimit ('verifytoken');
    if ($last > 0) {
        $display .= COM_siteHeader ('menu', $LANG12[26])
                 . COM_showMessageText(sprintf ($LANG04[93], $last, $_CONF['passwordspeedlimit']),$LANG12[26],true,'error')
                 . COM_siteFooter ();
    } else {
        $username = (isset($_POST['username']) ? $_POST['username'] : '');
        $passwd   = (isset($_POST['passwd']) ? $_POST['passwd'] : '');
        if (!empty ($username) && !empty ($passwd) && USER_validateUsername($username)) {
            $encryptedPassword = '';
            $uid = 0;
            $result = DB_query("SELECT uid,passwd FROM {$_TABLES['users']} WHERE username='".DB_escapeString($username)."'");
            if ( DB_numRows($result)  > 0 ) {
                $row = DB_fetchArray($result);
                $encryptedPassword = $row['passwd'];
                $uid = $row['uid'];
            }
            if ( $encryptedPassword != '' && SEC_check_hash($passwd, $encryptedPassword) ) {
                $display .= requesttoken ($uid, 3);
            } else {
                $display .= COM_siteHeader('menu');
                $display .= newtokenform($uid);
                $display .= COM_siteFooter();
            }
        } else {
            $display .= COM_siteHeader('menu');
            $display .= newtokenform($uid);
            $display .= COM_siteFooter();
        }
    }
    break;

default:
    $status = -2;
    $local_login = false;
    $checkMerge  = false;
    $newTwitter  = false;

    // prevent dictionary attacks on passwords
    COM_clearSpeedlimit($_CONF['login_speedlimit'], 'login');
    if (COM_checkSpeedlimit('login', $_CONF['login_attempts']) > 0) {
        displayLoginErrorAndAbort(82, $LANG12[26], $LANG04[112]);
    }

    $loginname = '';
    if (isset ($_POST['loginname'])) {
        $loginname = $_POST['loginname'];
        if ( !USER_validateUsername($loginname) ) {
            $loginname = '';
        }
    }
    $passwd = '';
    if (isset ($_POST['passwd'])) {
        $passwd = $_POST['passwd'];
    }

    $service = '';
    if (isset ($_POST['service'])) {
        $service = COM_applyFilter($_POST['service']);
    }

    $uid = '';
    if (!empty($loginname) && !empty($passwd) && empty($service)) {
        if (empty($service) && $_CONF['user_login_method']['standard']) {
            COM_updateSpeedlimit('login');
            $status = SEC_authenticate($loginname, $passwd, $uid);
            if ($status == USER_ACCOUNT_ACTIVE) {
                $local_login = true;
            }
        } else {
            $status = -2;
        }

    // begin distributed (3rd party) remote authentication method

    } elseif ($_CONF['user_login_method']['3rdparty'] &&
        ($_CONF['usersubmission'] == 0) &&
        ($service != '')) {

        COM_updateSpeedlimit('login');
        //pass $loginname by ref so we can change it ;-)
        $status = SEC_remoteAuthentication($loginname, $passwd, $service, $uid);

    // end distributed (3rd party) remote authentication method

    // begin OpenID remote authentication method

    } elseif ($_CONF['user_login_method']['openid'] &&
        ($_CONF['usersubmission'] == 0) &&
        !$_CONF['disable_new_user_registration'] &&
        (isset($_GET['openid_login']) && ($_GET['openid_login'] == '1'))) {

        $query = array_merge($_GET, $_POST);

        if (isset($query['identity_url']) &&
                ($query['identity_url'] != 'http://')) {
            $property = sprintf('%x', crc32($query['identity_url']));
            COM_clearSpeedlimit($_CONF['login_speedlimit'], 'openid');
            if (COM_checkSpeedlimit('openid', $_CONF['login_attempts'],
                                    $property) > 0) {
                displayLoginErrorAndAbort(82, $LANG12[26], $LANG04[112]);
            }
        }

        require_once $_CONF['path_system'] . 'classes/openidhelper.class.php';

        $consumer = new SimpleConsumer();
        $handler = new SimpleActionHandler($query, $consumer);

        if (isset($query['identity_url']) && $query['identity_url'] != 'http://') {
            $identity_url = $query['identity_url'];
            $ret = $consumer->find_identity_info($identity_url);
            if (!$ret) {
                COM_updateSpeedlimit('login');
                $property = sprintf('%x', crc32($query['identity_url']));
                COM_updateSpeedlimit('openid', $property);
                COM_errorLog('Unable to find an OpenID server for the identity URL ' . $identity_url);
                echo COM_refresh($_CONF['site_url'] . '/users.php?msg=89');
                exit;
            } else {
                // Found identity server info.
                list($identity_url, $server_id, $server_url) = $ret;

                // Redirect the user-agent to the OpenID server
                // which we are requesting information from.
                header('Location: ' . $consumer->handle_request(
                        $server_id, $server_url,
                        oidUtil::append_args($_CONF['site_url'] . '/users.php',
                            array('openid_login' => '1',
                                  'open_id' => $identity_url)), // Return to.
                        $_CONF['site_url'], // Trust root.
                        null,
                        "email,nickname,fullname")); // Required fields.
                exit;
            }
        } elseif (isset($query['openid.mode']) || isset($query['openid_mode'])) {
            $openid_mode = '';
            if (isset($query['openid.mode'])) {
                $openid_mode = $query['openid.mode'];
            } else if(isset($query['openid_mode'])) {
                $openid_mode = $query['openid_mode'];
            }
            if ($openid_mode == 'cancel') {
                COM_updateSpeedlimit('login');
                echo COM_refresh($_CONF['site_url'] . '/users.php?msg=90');
                exit;
            } else {
               $openid = $handler->getOpenID();
               $req = new ConsumerRequest($openid, $query, 'GET');
               $response = $consumer->handle_response($req);
               $response->doAction($handler);
            }
        } else {
            COM_updateSpeedlimit('login');
            echo COM_refresh($_CONF['site_url'] . '/users.php?msg=91');
            exit;
        }

    // end OpenID remote authentication method

    // begin OAuth authentication method(s)

    } elseif ($_CONF['user_login_method']['oauth'] &&
        ($_CONF['usersubmission'] == 0) &&
        !$_CONF['disable_new_user_registration'] &&
        isset($_GET['oauth_login'])) {

        $modules = SEC_collectRemoteOAuthModules();
        $active_service = (count($modules) == 0) ? false : in_array($_GET['oauth_login'], $modules);
        if (!$active_service) {
            $status = -1;
            COM_errorLog("OAuth login failed - there was no consumer available for the service:" . $_GET['oauth_login']);
        } else {
            $query = array_merge($_GET, $_POST);
            $service = $query['oauth_login'];

            COM_clearSpeedlimit($_CONF['login_speedlimit'], $service);
            if (COM_checkSpeedlimit($service, $_CONF['login_attempts']) > 0) {
                displayLoginErrorAndAbort(82, $LANG12[26], $LANG04[112]);
            }

            require_once $_CONF['path_system'] . 'classes/oauthhelper.class.php';

            $consumer = new OAuthConsumer($service);

            $callback_url = $_CONF['site_url'] . '/users.php?oauth_login=' . $service;

            $consumer->setRedirectURL($callback_url);
            $oauth_userinfo = $consumer->authenticate_user();
            if ( $oauth_userinfo === false ) {
                COM_updateSpeedlimit('login');
                COM_errorLog("OAuth Error: " . $consumer->error);
                echo COM_refresh($_CONF['site_url'] . '/users.php?msg=111'); // OAuth authentication error
                exit;
            }

            $consumer->doAction($oauth_userinfo);

        }

    //  end OAuth authentication method(s)

    } else {
        $status = -2;
    }

    if ($status == USER_ACCOUNT_ACTIVE || $status == USER_ACCOUNT_AWAITING_ACTIVATION ) { // logged in AOK.
        SESS_completeLogin($uid);
        $_GROUPS = SEC_getUserGroups( $_USER['uid'] );
        $_RIGHTS = explode( ',', SEC_getUserPermissions() );
        if ($_SYSTEM['admin_session'] > 0 && $local_login ) {
            if (SEC_isModerator() || SEC_hasRights('story.edit,block.edit,topic.edit,user.edit,plugin.edit,user.mail,syndication.edit','OR')
                     || (count(PLG_getAdminOptions()) > 0)) {
                $admin_token = SEC_createTokenGeneral('administration',$_SYSTEM['admin_session']);
                SEC_setCookie('token',$admin_token,0,$_CONF['cookie_path'],$_CONF['cookiedomain'],$_CONF['cookiesecure'],true);
            }
        }
        $_USER['theme'] = $_CONF['theme'];
        COM_resetSpeedlimit('login');

        // let's see if we need to merge accounts
        if ( $checkMerge ) {
            $display = mergeAccountForm();
        } else {
            // we are now fully logged in, let's see if there is someplace we need to go....
            if (!empty($_SERVER['HTTP_REFERER'])
                    && (strstr($_SERVER['HTTP_REFERER'], '/users.php') === false)
                    && (substr($_SERVER['HTTP_REFERER'], 0,
                            strlen($_CONF['site_url'])) == $_CONF['site_url'])) {
                $indexMsg = $_CONF['site_url'] . '/index.php?msg=';
                if (substr ($_SERVER['HTTP_REFERER'], 0, strlen ($indexMsg)) == $indexMsg) {
                    $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
                } else {
                    // If user is trying to login - force redirect to index.php
                    if (strstr ($_SERVER['HTTP_REFERER'], 'mode=login') === false) {
                        $display .= COM_refresh ($_SERVER['HTTP_REFERER']);
                    } else {
                        $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
                    }
                }
            } else {
                $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
            }
        }
    }
    else if ( isset($_POST['mode']) && $_POST['mode'] == 'mergeacct' && !isset($_POST['cancel']) ) {
        mergeaccounts();
    } elseif (isset($_POST['cancel']) ) {
        $display .= COM_refresh ($_CONF['site_url'] . '/index.php');
    } else {
        $display .= COM_siteHeader('menu');

        if ( isset($_POST['msg']) ) {
            $msg = COM_applyFilter($_POST['msg'],true);
        } elseif (isset($_GET['msg']) ) {
            $msg = COM_applyFilter($_GET['msg'],true);
        } else {
            $msg = 0;
        }
        if ($msg > 0) {
            $display .= COM_showMessage($msg,'','',0,'info');
        }

        switch ($mode) {
        case 'create':
            // Got bad account info from registration process, show error
            // message and display form again
            if ($_CONF['custom_registration'] AND (function_exists('CUSTOM_userForm'))) {
                $display .= CUSTOM_userForm ();
            } else {
                $display .= newuserform ();
            }
            break;
        default:
            // check to see if this was the last allowed attempt
            if (COM_checkSpeedlimit('login', $_CONF['login_attempts']) > 0) {
                displayLoginErrorAndAbort(82, $LANG04[113], $LANG04[112]);
            } else { // Show login form
                if(($msg != 69) && ($msg != 70)) {
                    if ($_CONF['custom_registration'] AND function_exists('CUSTOM_loginErrorHandler') && $msg != 0) {
                        // Typically this will be used if you have a custom main site page and need to control the login process
                        $display .= CUSTOM_loginErrorHandler($msg);
                    } else {
                        $display .= loginform(false, $status);
                    }
                }
            }
            break;
        }

        $display .= COM_siteFooter();
    }
    break;
}

echo $display;

?>