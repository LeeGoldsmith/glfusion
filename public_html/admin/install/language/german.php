<?php
// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | german.php                                                               |
// |                                                                          |
// | German language file for the glFusion installation script                |
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

// +---------------------------------------------------------------------------+

$LANG_CHARSET = 'iso-8859-1';

// +---------------------------------------------------------------------------+
// install.php

$LANG_INSTALL = array(
    'back_to_top' => 'zur�ck nach oben',
    'calendar' => 'Kalender-Plugin installieren?',
    'calendar_desc' => 'Kalender-, Termin-System. Es gibt einen �bergeordneten Kalender sowie pers�nliche Kalender f�r einzelne Benutzer.',
    'connection_settings' => 'Verbindungseinstellungen',
    'content_plugins' => 'Inhalt & Plugins',
    'copyright' => '<a href="http://www.glfusion.org" target="_blank">glFusion</a> ist kostenlose Software ver�ffentlicht unter der <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">GNU/GPL v2.0 Lizenz.</a>',
    'core_upgrade_error' => 'Beim Kernupgrade ist ein Fehler aufgetreten.',
    'correct_perms' => 'Bitte die unten genannten Fehler beseitigen. Danach den <b>Recheck</b>-Knopf klicken, um die Umgebung erneut zu �berpr�fen.',
    'current' => 'Derzeit',
    'database_exists' => 'Die Datenbank enth�lt schon glFusion-Tabellen. Bitte vor einer Neuinstallation die glFusion-Tabellen entfernen.',
    'database_info' => 'Datenbankinformation',
    'db_hostname' => 'Name Datenbank-Server',
    'db_hostname_error' => 'Name Datenbank-Server kann nicht leer sein.',
    'db_name' => 'Name der Datenbank',
    'db_name_error' => 'Datenbankname kann nicht leer sein.',
    'db_pass' => 'Datenbankkennwort',
    'db_table_prefix' => 'Pr�fix f�r Tabellen',
    'db_type' => 'Datenbanktyp',
    'db_type_error' => 'Datenbanktyp muss ausgew�hlt werden',
    'db_user' => 'Benutzername der Datenbank',
    'db_user_error' => 'Benutzername der Datenbank kann nicht leer sein.',
    'dbconfig_not_found' => 'Kann die Datei db-config.php oder db-config.php.dist nicht finden. Bitte sicherstellen, dass sie im richtigen private Pfad sind .',
    'dbconfig_not_writable' => 'Die Datei db-config.php l��t sich nicht schreiben. Bitte sicherstellen, dass der Webserver Schreibrechte f�r die Datei hat.',
    'directory_permissions' => 'Verzeichnisrechte',
    'enabled' => 'eingeschaltet',
    'env_check' => 'Umgebung pr�fen',
    'error' => 'Fehler',
    'file_permissions' => 'Dateirechte',
    'file_uploads' => 'Viele Funktionen von glFusion brauchen die Erlaubnis, Dateien hochladen zu d�rfen. Das sollte erlaubt sein.',
    'filemgmt' => 'Datei-Management-Plugin installieren?',
    'filemgmt_desc' => 'Hilfe um Dateien runterzuladen. Eine einfache Art, Dateien zum Download anzubieten, organisiert nach Kategorien.',
    'filesystem_check' => '�berpr�fe Dateisystem',
    'forum' => 'Forum-Plugin installieren?',
    'forum_desc' => 'Ein Community-Forum-System f�r gemeinschaftliche Zusammenarbeit und Austausch.',
    'hosting_env' => 'Hosting-Umgebung pr�fen',
    'install' => 'Installieren',
    'install_heading' => 'glFusion Installation',
    'install_steps' => 'INSTALLATIONS-Schritte',
    'language' => 'Sprache',
    'language_task' => 'Sprache & Aufgabe',
    'libcustom_not_writable' => 'lib-custom.php nicht schreibbar.',
    'links' => 'Links-Plugin installieren?',
    'links_desc' => 'System f�r das Verwalten von Links. Bietet Links zu anderen interessanten Seiten organisiert nach Kategorien.',
    'load_sample_content' => 'Inhalt f�r Beispielseite installieren ?',
    'mediagallery' => 'Medien-Galerie-Plugin installieren?',
    'mediagallery_desc' => 'Ein Multimedia-Management-System. Kann als einfache Fotogalerie oder als robustes Medien-Management-System benutzt werden f�r Audio, Video und Bilder.',
    'memory_limit' => 'Mindestens 48MB RAM Speicher werden empfohlen.',
    'missing_db_fields' => 'Bitte alle erforderlichen Datenbankfelder eingeben.',
    'new_install' => 'Neue Installation',
    'next' => 'n�chstes',
    'no_db' => 'Datenbank scheint nicht zu existieren.',
    'no_db_connect' => 'Kann mit der Datenbank nicht verbinden',
    'no_innodb_support' => 'Eine MySQL mit InnoDB wurde ausgew�hlt, aber die Datenbank unterst�tzt keinen Import von InnoDB.',
    'no_migrate_glfusion' => 'Eine existierender glFusion-Auftritt kann nicht verschoben werden. Bitte die Upgrade-Option benutzen!',
    'none' => 'aus',
    'not_writable' => 'NICHT SCHREIBBAR',
    'notes' => 'Hinweise',
    'off' => 'aus',
    'ok' => 'o.k.',
    'on' => 'an',
    'online_help_text' => 'Online Installationshilfe<br /> auf glFusion.org',
    'online_install_help' => 'Online Installationshilfe',
    'open_basedir' => 'Wenn <strong>open_basedir</strong>-Beschr�nkungen f�r den Webspace eingeschaltet sind, kann das ggf. w�hrend der Installation zu Rechteproblemen f�hren. Das Dateipr�fsystem unten sollte m�gliche Probleme aufzeigen.',
    'path_info' => 'Pfad-Information',
    'path_prompt' => 'Pfad zum private/ -Verzeichnis',
    'path_settings' => 'Pfad-Einstellungen',
    'perform_upgrade' => 'Upgrade durchf�hren',
    'php_req_version' => 'glFusion braucht PHP Version 4.3.0 oder h�her.',
    'php_settings' => 'PHP-Einstellungen',
    'php_version' => 'PHP-Version',
    'php_warning' => 'Wenn eine der Anzeigen unten <span class="no">rot</span> markiert ist, mag es Probleme mit dem  glFusion-Auftritt geben.  Bitte mit dem Hoster R�cksprache nehmen, wie man ggf. die PHP-Einstellungen �ndert.',
    'plugin_install' => 'Plugin-Installation',
    'plugin_upgrade_error' => 'Es gab ein Problem das %s Plugin auf den neusten Stand zu bringen. Bitte im error.log nachsehen f�r mehr Details.<br />',
    'plugin_upgrade_error_desc' => 'Die folgenden Plugins wurden nicht auf den neusten Stand gebracht. Bitte im error.log nachsehen f�r mehr Details.<br />',
    'polls' => 'Umfrage-Plugin installieren?',
    'polls_desc' => 'Ein online Umfragesystem. Bietet Umfragen f�r den Auftritt zu verschiedensten Themen.',
    'post_max_size' => 'glFusion erm�glicht das Hochladen von Plugins, Bildern und Dateien. Es sollten mindestens 8MB post_max_size eingestellt sein.',
    'previous' => 'zur�ck',
    'proceed' => 'weiter',
    'recommended' => 'empfohlen',
    'register_globals' => 'Falls PHP <strong>register_globals</strong> eingeschaltet ist, kann das ggf. Sicherheitsprobleme bereiten.',
    'safe_mode' => 'Falls PHP <strong>safe_mode</strong> eingeschaltet ist, k�nnen ggf. einige Funktionen von glFusion nicht richtig funktionieren. Vor allen Dingen das Medien-Galerie-Plugin.',
    'samplecontent_desc' => 'Es wird Beispielinhalt installiert f�r Bl�cke, Artikel und statische Seiten. <strong>Dies ist f�r neue Nutzer sinnvoll.</strong>',
    'select_task' => 'Aufgabe ausw�hlen',
    'session_error' => 'Die Sitzung ist abgelaufen. Bitte den Installationsprozess neu starten.',
    'setting' => 'Einstellungen',
    'site_admin_url' => 'URL f. "admin"-Verzeichnis',
    'site_admin_url_error' => 'URL f. "admin"-Verzeichnis darf nicht leer sein.',
    'site_email' => 'Emailadresse des Auftritts',
    'site_email_error' => 'Emailadresse des Auftritts darf nicht leer sein.',
    'site_email_notvalid' => 'Emailadresse des Auftritts ist keine g�ltige Emailadresse.',
    'site_info' => 'Site Information',
    'site_name' => 'Name des Auftritts',
    'site_name_error' => 'Name des Auftritts darf nicht leer sein.',
    'site_noreply_email' => '"No Reply"-Email-Adresse des Auftritts',
    'site_noreply_email_error' => '"No Reply"-Email-Adresse darf nicht leer sein.',
    'site_noreply_notvalid' => 'Die angegebene "No Reply"-Email-Adresse ist keine g�ltige Emailadresse.',
    'site_slogan' => 'Site Slogan',
    'site_upgrade' => 'Einen existierenden glFusion-Auftritt auf den neuesten Stand bringen',
    'site_url' => 'URL des Auftritts',
    'site_url_error' => 'URL des Auftritts darf nicht leer sein.',
    'siteconfig_exists' => 'Eine vorhandene siteconfig.php Datei wurde gefunden. Bitte diese Datei vor dem Weitermachen mit der Neuinstallation l�schen.',
    'siteconfig_not_found' => 'Kann die Datei siteconfig.php nicht finden, ist dies tats�chlich ein Upgrade?',
    'siteconfig_not_writable' => 'Die Datei siteconfig.php ist nicht schreibbar, oder das Verzeichnis in dem die Datei siteconfig.php liegt, ist nicht schreibbar. Bitte erst den Fehler beheben.',
    'sitedata_help' => 'Den Datenbanktyp aus dem Drop-down-Men� w�hlen. Das ist normalerweise <strong>MySQL</strong>. Auch ausw�hlen, ob der <strong>UTF-8</strong> Zeichensatz benutzt werden soll (das sollte man immer bei mehrsprachigen Auftritten w�hlen.)<br /><br /><br />Den Namen des Datenbankservers eingeben. Dies mag nicht der gleiche sein wie der Webserver. Am besten den Hoster fragen.<br /><br />Den Namen der Datenbank eingeben. <strong>Die Datenbank mu� bereits existieren.</strong> Wenn der Name nicht bekannt ist, dann den Hoster fragen.<br /><br />Den Benutzernamen f�r die Verbindung zur Datenbank eingeben. Wenn der Name nicht bekannt ist, den Hoster fragen.<br /><br /><br />Das Kennwort f�r die Verbindung zur Datenbank eingeben. Wenn es nicht bekannt ist, den Hoster fragen.<br /><br />Den Pr�fix f�r Datenbanktabellen eingeben. Dies ist n�tzlich, wenn man mehrere Auftritte oder noch andere Sachen in einer Datenbank gemeinsam nutzt.<br /><br />Einen willk�rlichen Namen f�r den Auftritt angeben. Dieser wird im Kopf des Auftritts angezeigt. Zum Beispiel glFusion or Mark\'s Marbles. Keine Angst, das kann sp�ter noch ge�ndert werden.<br /><br />Einen willk�rlichen Slogan f�r den Auftritt angeben. Dieser wird im Kopf des Auftritts unter dem Namen angezeigt. Zum Beispiel: synergy - stability - style. Keine Angst, das kann sp�ter noch ge�ndert werden.<br /><br />Die Email-Adresse des Auftritts eingeben. Das ist die Email-Adresse des Super-Admin-Accounts. Keine Angst, das kann sp�ter noch ge�ndert werden.<br /><br />Die "No Reply"-Email-Adresse des Auftritts eingeben. Die wird benutzt als Absender f�r automatisch versandte Emails f�r neue Benutzer, zur�ckgesetzte Kennworte und andere Benachrichtigungen. Auch das kann sp�ter ge�ndert werden.<br /><br />Bitte best�tigen, dass dies die Webadresse oder URL ist, die benutzt wird, um auf die Startseite des Auftritts zu gelangen.<br /><br />Bitte best�tigen, dass dies die Webadresse oder URL ist, die benutzt wird, um in den Verwaltungsbereich des Auftritts zu gelangen',
    'sitedata_missing' => 'Es gab die folgenden Probleme mit den gemachten Angaben:',
    'system_path' => 'Pfad-Einstellungen',
    'unable_mkdir' => 'Kann das Verzeichnis nicht erstellen',
    'unable_to_find_ver' => 'Kann die Version von glFusion nicht feststellen.',
    'upgrade_error' => 'Upgrade-Fehler',
    'upgrade_error_text' => 'Es gab einen Fehler bei der glFusion-Upgrade-Installation.',
    'upgrade_steps' => 'UPGRADE-SCHRITTE',
    'upload_max_filesize' => 'glFusion erm�glicht das Hochladen von Plugins, Bildern und Dateien. Es sollten mindestens 8MB f�r das Hochladen eingestellt sein.',
    'use_utf8' => '<br />Zeichensatz benutzen UTF-8 ',
    'welcome_help' => 'Willkommen beim glFusion-CMS Installations-Zauberer. Einen neuen glFusion-Auftritt installieren, auf den neusten Stand bringen, oder einen existierenden Geeklog v1.4.1 Auftritt importieren.<br /><br />Bitte die Sprache f�r den Zauberer ausw�hlen, die gestellte Aufgabe und dann <strong>n�chstes</strong> dr�cken.',
    'wizard_version' => 'v1.1.3.svn Installations-Zauberer',
    'system_path_prompt' => 'Den vollen, absoluten Pfad auf dem Server zum glFusion <strong>private/</strong>-Verzeichnis.<br /><br />Dies Verzeichnis enth�lt die <strong>db-config.php.dist</strong> oder <strong>db-config.php</strong>-Datei.<br /><br />Beispiel: /home/www/glfusion/private oder c:/www/glfusion/private.<br /><br /><strong>Hinweis:</strong> Der absolute Pfad zum <strong>public_html/</strong> <i>(nicht <strong>private/</strong>)</i> -Verzeichnis scheint:<br /><br />%s zu sein.<br /><br /><strong>Bei weitere Einstellungen</strong> kann man einige der Standardpfade ver�ndern.  Im allgemeinen muss man diese Pfade nicht angeben oder �ndern. Das System legt sie automatsich fest.',
    'advanced_settings' => 'Weitere Einstellungen',
    'log_path' => 'Pfad zu Logs',
    'lang_path' => 'Pfad zu Language',
    'backup_path' => 'Pfad zu Backups',
    'data_path' => 'Pfad zu Data',
    'language_support' => 'Sprachunterst�tzung',
    'language_pack' => 'glFusion kommt zun�chst mit englischer Sprachunterst�tzung. Nach der Installation kann man das <a href="http://www.glfusion.org/filemgmt/viewcat.php?cid=18" target="_blank">Sprachpaket</a> runterladen und installieren, dass alle unterst�tzten Sprachen enth�lt.',
    'libcustom_not_found' => 'Unable to located lib-custom.php.dist.',
    'no_db_driver' => 'You must have the MySQL extension loaded in PHP to install glFusion',
    'version_check' => 'Check For Updates',
    'check_for_updates' => "Goto the <a href=\"{$_CONF['site_admin_url']}/vercheck.php\">Upgrade Checker</a> to see if there are any glFusion CMS or Plugin updates available."
);

// +---------------------------------------------------------------------------+
// success.php

$LANG_SUCCESS = array(
    0 => 'Installation vollst�ndig',
    1 => 'Installation von glFusion ',
    2 => ' abgeschlossen!',
    3 => 'Gl�ckwunsch, glFusion wurde erfolgreich ',
    4 => ' . Bitte eine Minute Zeit nehmen, um die Information unten zu lesen.',
    5 => 'Um in den neue glFusion-Auftritt einzuloggen, bitte diesen Account benutzen:',
    6 => 'Benutzername:',
    7 => 'Admin',
    8 => 'Kennwort:',
    9 => 'password',
    10 => 'Sicherheitswarnung',
    11 => 'Bitte vergiss nicht, die folgenden ',
    12 => ' Dinge zu tun',
    13 => 'Das Installationsverzeichnis l�schen oder umbenennen:',
    14 => 'Das Kennwort f�r den Account ',
    15 => '�ndern.',
    16 => 'Die Zugriffsrechte f�r',
    17 => 'und',
    18 => 'zur�cksetzen auf',
    19 => '<strong>Hinweis:</strong> Weil sich das Sicherheitsmodell ge�ndert hat, haben wir einen neuen Account erstellt mit den Rechten, die zur Verwaltung des neuen Auftritts n�tig sind.  Der Benutzername f�r diesen neuen Account ist <b>NewAdmin</b> und das Kennwort ist <b>password</b>',
    20 => 'installiert',
    21 => 'aktualisiert'
);

?>