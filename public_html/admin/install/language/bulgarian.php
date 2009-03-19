<?php
// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | english.php                                                              |
// |                                                                          |
// | English language file for the glFusion installation script               |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2009 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Geeklog CMS                                                 |
// | Copyright (C) 2000-2008 by the following authors:                        |
// |                                                                          |
// | Authors: Tony Bibbs        - tony AT tonybibbs DOT com                   |
// |          Mark Limburg      - mlimburg AT users DOT sourceforge DOT net   |
// |          Jason Whittenburg - jwhitten AT securitygeeks DOT com           |
// |          Dirk Haun         - dirk AT haun-online DOT de                  |
// |          Randy Kolenko     - randy AT nextide DOT ca                     |
// |          Matt West         - matt AT mattdanger DOT net                  |
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

// +---------------------------------------------------------------------------+

$LANG_CHARSET = 'windows-1251';

// +---------------------------------------------------------------------------+
// index.php

$LANG_INSTALL = array(
    'back_to_top'               => '��� ������',
    'calendar'                  => '�������� Plugin?',
    'calendar_desc'             => '����� ������������ �������� / ���� � �������� ��� ���������� �� �� ������� �������,��������� �� ����������.',
    'connection_settings'       => '��������� �� ��������',
    'content_plugins'           => '��������� �� ������������',
    'copyright'                 => '<a href="http://www.glfusion.org" target="_blank">glFusion</a> � ��������� ������� � � ������ ��� ������� �� <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">GNU/GPL v2.0 License.</a>',
    'core_upgrade_error'        => '������ ��� ���� �� ����������.',
    'correct_perms'             => '���� ��������� ���������� ���������� ������. ������ ����� ����������, ����������� <b>Recheck</b> ������ �� �� ����������.',
    'current'                   => '�������',
    'database_exists'           => '���������� ���� ��� glfusion �������. ���� ���������� ��������� ����� ���� ����������.',
    'database_info'             => 'Database ����������',
    'db_hostname'               => 'Database ���� ���',
    'db_hostname_error'         => 'Database ������ �� ����� �� ���� �� � ������.',
    'db_name'                   => 'Database ���',
    'db_name_error'             => 'Database ������ �� ����� �� ���� �� � ������.',
    'db_pass'                   => 'Database ������',
    'db_table_prefix'           => 'Database �������� �� ���������(������:glfusion_site)',
    'db_type'                   => 'Database ���',
    'db_type_error'             => 'Database ������ �� ��� ������ ���',
    'db_user'                   => 'Database ����������',
    'db_user_error'             => 'Database ������ �� ��������������� ��� �� ���� �� � ������.',
    'dbconfig_not_found'        => '�� ���� �� ������ db-config.php ��� db-config.php.dist �����. ���� ������� �� �� ��� ������ �������� ����� ��� ������������.',
    'dbconfig_not_writable'     => '�� ���� �� �� ���� ����� db-config.php. ���� ��������� ���� ������� ��� ����� �� ���� ����� ���� ����.',
    'directory_permissions'     => '����� �� ������������',
    'enabled'					=> '��������',
    'env_check'					=> '�������� �� �������',
    'error'                     => '������',
    'file_permissions'          => '����� �� ���������',
    'file_uploads'				=> '����� ����� �� glFusion �������� ����������� �� �� ������ �������, ���� �� �������� �� � ���������.',
    'filemgmt'                  => '������ FileMgmt Plugin?',
    'filemgmt_desc'             => 'File Download Manager. ����� ����� �� �� �������� ��� ������� �� ������,��������� �� ���������.',
    'filesystem_check'          => '�������� �� ��������� �������',
    'forum'                     => '������ Forum Plugin?',
    'forum_desc'                => '������ ������� �������. �������� �� ��������� �������������� � ��������������.',
    'geeklog_migrate'           => '�������� ��� Geeklog v1.5+ Site',
    'hosting_env'               => '�������� �� ����� �� �������',
    'install'                   => '����������',
    'install_heading'           => 'glFusion ����������',
    'install_steps'             => '������ �� ����������',
    'invalid_geeklog_version'   => '����������� �� ���� �� ������ siteconfig.php. ������� �� ��� �� ��������� �� Geeklog v1.5.0 ��� �� ����� ������?  ��� ����� �� ����� Geeklog ����������, ���� ��������� �� Geeklog v1.5.0 � ��������� ������.',
    'language'                  => '����',
    'language_task'             => '���� � ������',
    'libcustom_not_writable'    => 'lib-custom.php ���� ����� �� ������.',
    'links'                     => '������ Links Plugin?',
    'links_desc'                => '������� �� ���������� �� ����������. �������� ������� ��� ����� ��������� �������,��������� �� ���������.',
    'load_sample_content'       => '������ Sample Site Content?',
    'mediagallery'              => '������ Media Gallery Plugin?',
    'mediagallery_desc'         => '�����-������� �������. ���� �� ���� ���������� ���� ������ ���� ������� ��� ������� ����� �������� �����,����� � �������.',
    'memory_limit'				=> 'It is recommended that you have at least 48M of memory enabled on your site.',
    'missing_db_fields'         => '���� ��������� ������ ������ ����� �� ����������.',
    'new_install'               => '���� ����������',
    'next'                      => '��������',
    'no_db'                     => '�������� �� ������ �������� ����.',
    'no_db_connect'             => '�� ���� �� �� ������ ��� ����������',
    'no_innodb_support'         => '������� ��� MySQL ��� InnoDB �� ������ �������� �� �������� InnoDB.',
    'no_migrate_glfusion'       => '�� ������ �� ��������� glFusion ����. ���� �������� ����������� �����..',
    'none'                      => '����',
    'not_writable'              => '���� ����� �� ������',
    'notes'						=> 'Notes',
    'off'                       => '��������',
    'ok'                        => '�����',
    'on'                        => '�������',
    'online_help_text'          => '������ ������������� �����<br /> �� glFusion.org',
    'online_install_help'       => '������ ������������� �����',
    'open_basedir'				=> '��� <strong>open_basedir</strong> ������������� �� ��������� �� ����� ����, �� ������ ���� �� ������� �������� �� ����� �� ������������.���������� �� ���������� ���� ������ �� ������ ���� ��� ������.',
    'path_info'					=> '���������� �� ����',
    'path_prompt'               => '��� ��� private/ ����������',
    'path_settings'             => '��������� �� ��������',
    'perform_upgrade'			=> '������� ����������',
    'php_req_version'			=> 'glFusion ������� PHP ������ 4.3.0 ��� �� ����.',
    'php_settings'				=> 'PHP ���������',
    'php_version'				=> 'PHP ������',
    'php_warning'				=> '��� ����� �� ������ �� ���������� ��� <span class="no">�������</span>, �� ������ ���� �� �������� �������� ��� glFusion site.  ��������� ����� ���� ������������ ���� ��� ������� ��� ����� �� ���� PHP ���������.',
    'plugin_install'			=> '���������� �� �������',
    'plugin_upgrade_error'      => '����� ������� ��� ������������ ��  %s ���������, ���� ��������� error.log �� ������ �����������.<br />',
    'plugin_upgrade_error_desc' => '�������� ������� �� ���� ��������. ���� ����� error.log �� ������ ����������.<br />',
    'polls'                     => '������ Polls Plugin?',
    'polls_desc'                => '������ ������� �������. �������� ������ �� ����� ����,���������� ����� ����.',
    'post_max_size'				=> 'glFusion ��� ���������� �� ������� �� �������, �������, � ����� �������. ������ �� ��������� ���� 8MB �� ���������� ������.',
    'previous'                  => '��������',
    'proceed'                   => '��������',
    'recommended'               => '����������',
    'register_globals'			=> '��� PHP\'s <strong>register_globals</strong> � ��������, �� ������ ���� �� ������� �������� ��� ��������.',
    'safe_mode'					=> '��� PHP\'s <strong>safe_mode</strong> � ��������, ����translating
 ������� �� glFusion ���� �� �� ������� ��������. ��������� �� �����-���������.',
    'samplecontent_desc'        => '��� � ���������, ���������translating
�� ������ ����� ���� �������,�������,� �������� ��������.<strong>���� � ��������� �� ���� ����������� �� glFusion.</strong>',
    'select_task'               => '������ ������',
    'session_error'             => 'Session-� ���� ��������.  ���� �����������
�� ������������� ������.',
    'setting'                   => '���������',
    'site_admin_url'            => '���������������� ���� (URL)',
    'site_admin_url_error'      => '������ �� ����������������� ���� �� ���� �� � ������.',
    'site_email'                => 'Email �� �����',
    'site_email_error'          => '������ �� Email �� ����� �� ���� �� � ������.',
    'site_email_notvalid'       => 'Email �� ����� �� � ������� email �����.',
    'site_info'					=> '���������� �� �����',
    'site_name'                 => '��� �� �����',
    'site_name_error'           => '������ �� ����� �� ����� �� ���� �� ���� ������.',
    'site_noreply_email'        => 'Site No Reply Email',
    'site_noreply_email_error'  => '������ �� Site No Reply Email �� ���� �� ���� ������.',
    'site_noreply_notvalid'     => 'No Reply Email �� � ������� email �����.',
    'site_slogan'               => '���������� �� �����',
    'site_upgrade'              => '������ ���� ����������� glFusion ����',
    'site_url'                  => '����� �� �����',
    'site_url_error'            => '������ �� ������ �� ����� �� ���� �� ���� ������.',
    'siteconfig_exists'         => '���� ������� ����������� siteconfig.php ����. ���� ���������� ����� �� ������� ���� ����������.',
    'siteconfig_not_found'      => '�� ���� �� �� ������ siteconfig.php, ������������ �� ���� � upgrade?',
    'siteconfig_not_writable'   => '����� ���� siteconfig.php �� ���� �� �� ���� , ��� ������������ ������ � siteconfig.php ���� ����� �� ������. ���� ��������� �������� ����� �� ����������.',
    'sitedata_help'             => '�������� ���-� �� ���������� �� �����. ��� � ������� <strong>MySQL</strong>. ����� � �������� ���� �� ���� ��������� <strong>UTF-8</strong> character set (���� �� � ������ ����� �� ������� ��� �����-�����.)<br /><br /><br />�������� ����� �� ����� ������� ��� ����������. ���� ���� �� �� � ����� ��� ������, ������ ��������� ����� ��������� �� ������� ��� ����� �������.<br /><br />�������� ����� �� ������ ��������. <strong>��� �������� ��� ������ ���.</strong> ��� �� ������ ����� �� ���������� , �������� �� ��� ����� ������� ���������.<br /><br />�������� ����������� �� �� �� �������� ��� ����������. ��� �� ������ ��������������� ��� �� ����������, �������� �� ��� ����� ������� ���������.<br /><br /><br />�������� �������� ��� ������ ��������. ��� �� ������ �������� ��� ����������, �������� �� ��� ����� ������� ���������.<br /><br />�������� ���������� �� ��������� ��� ����������. ���� � ������� ������ ����� �����-������� ��� ����� ������ �� ���� ������� ��� ���� ��������.<br /><br />�������� ����� �� ����� ��. �� �� ���� �������� ���� ���������� �� �����. ������, glFusion ��� Mark\'s Marbles. �� �� ���������� ��� ��������, �� ������ ���� �� �� ����� �� �����.<br /><br />�������� ������������ �� ����� ����. �� �� ���� �������� ��� ���������� �� �����. ������, synergy - stability - style. �� �� ���������� ��� ��������, �� ������ ���� �� �� ����� �� �����.<br /><br />�������� ������� email ����� �� ����� ��. ���� email ����� �� � ������� �� ����������������� ������. �� �� ���������� ��� ��������, �� ������ ���� �� �� ����� �� �����.<br /><br />������� ����� no reply email �����. ��� �� ���� ��������� ����������� �� ������� �� �����������, ����� �� ��������, � ����� ������������ ���������. �� �� ���������� ��� ��������, �� ������ ���� �� �� ����� �� �����.<br /><br />Please confirm that this is the web address or URL used to access the homepage of your site.<br /><br />���� ���������� �� ���� � �������� ������  ��� URL ��������� �� ����� �������� �� �����.',
    'sitedata_missing'          => '�������� �������� ��������� ��� �� ���������� �� ��� ����������:',
    'system_path'               => '��������� �� ����',
    'unable_mkdir'              => '�� ���� �� ������ ����������',
    'unable_to_find_ver'        => '�� ���� �� �������� �������� �� glFusion .',
    'upgrade_error'             => '������ ��� ������������',
    'upgrade_error_text'        => '������� ������ ������ �� ��������� �� ����������.',
    'upgrade_steps'             => '������ �� ����������',
    'upload_max_filesize'		=> 'glFusion �� ��������� �� ������� �������,�������,� ����� �������. ������ �� ��� ��������� ���� 8MB ���� ��������� ����� �� �������.',
    'use_utf8'                  => '����������� UTF-8',
    'welcome_help'              => '����� ����� ��� ������������� ��������� �� glFusion CMS. ��� ������ �� ����������� ��� glFusion ����,�� �������� ���� ����������� ����, ��� �� ��������� �� ����������� Geekblog 1.5 ����.<br /><br />���� �������� ���� �� ����������� � ��������� <strong>������</strong>.',
    'wizard_version'            => 'v' . GVERSION . ' ������������� ���������',
/* --- Updated --- */
/*    'system_path_prompt'        => '�������� ������ ��� ��� ������ ����� ��� glFusion\'s <strong>private/</strong> directory.<br /><br />���� ���������� ������� <strong>db-config.php.dist</strong> ��� <strong>db-config.php</strong> �����.<br /><br />�������: /home/www/glfuison/private ��� c:/www/glfusion/private', */
    'system_path_prompt'        => 'Enter the full, absolute path on your server to glFusion\'s <strong>private/</strong> directory.<br /><br />This directory contains the <strong>db-config.php.dist</strong> or <strong>db-config.php</strong> file.<br /><br />Examples: /home/www/glfuison/private or c:/www/glfusion/private.<br /><br /><strong>Hint:</strong> The absolute path to your public_html/ directory appears to be:<br />%s<br /><br /><strong>Advanced Settings</strong> allow you to override some of the default paths.  Generally you do not need to modify or specify these paths, the system will determine them automatically.',
/* --- New --- */
    'advanced_settings'         => 'Advanced Settings',
    'log_path'                  => 'Logs Path',
    'lang_path'                 => 'Language Path',
    'backup_path'               => 'Backups Path',
    'data_path'                 => 'Data Path',
    'language_support'          => 'Language Support',
    'language_pack'             => 'glFusion ships in English, but after installation you can download and install the <a href="http://www.glfusion.org/filemgmt/viewcat.php?cid=18" target="_blank">Language Pack</a> which contains the language files for all supported languages.',
);

// +---------------------------------------------------------------------------+
// success.php

$LANG_SUCCESS = array(
    0 => '������������ ���������',
    1 => '���������� �� glFusion ',
    2 => ' ���������!',
    3 => '������������,��� ������� ',
    4 => ' glFusion. ���� �������� ������� � ��������� ������������ ����.',
    5 => '�� �� ������� ��� ����� ��� glFusion ����,���� ����������� ����:',
    6 => '����������:',
    7 => '�����',

    8 => '������:',
    9 => '������',
    10 => '�������������� ��� ��������',
    11 => '�� ���������� �� ���������',
    12 => '����',
    13 => '�������� ��� ���������� �������������� ����������,',
    14 => '�����',
    15 => '������������� ������.',
    16 => '����� ����� ��',
    17 => '�',
    18 => '����� �� ���',
    19 => '<strong>���������:</strong> ������ ��������� �� ���������� ����� � ������, ��� ���������� ��� ������ ��� ����� �� �� ������ �� ������������� ����� ����.  ������ ��� �� ���� ������ � <b>NewAdmin</b> � �������� � <b>password</b>',
    20 => '����������',
    21 => '�������'
);
?>