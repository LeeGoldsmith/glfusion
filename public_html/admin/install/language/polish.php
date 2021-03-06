<?php
// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | polish.php                                                               |
// |                                                                          |
// | Polish language file for the glFusion installation script                |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2008-2009 by the following authors:                        |
// |                                                                          |
// | Marcin Kopij       - malach AT malach DOT org                            |
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

$LANG_CHARSET = 'iso-8859-2';

// +---------------------------------------------------------------------------+
// install.php

$LANG_INSTALL = array(
    'back_to_top' => 'Powr�t do g�ry',
    'calendar' => 'Za�adowa� plugin kalendarza?',
    'calendar_desc' => 'Kalendarz online / system wydarze�. Zawiera rozbudowany kalendarz na stronie oraz osobisty kalendarz dla ka�dego u�ytkownika.',
    'connection_settings' => 'Ustawienia po��czenia',
    'content_plugins' => 'Zawarto�� & Pluginy',
    'copyright' => '<a href="http://www.glfusion.org" target="_blank">glFusion</a> jest darmowym oprogramowaniem opartym na licencji <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">GNU/GPL v2.0 License.</a>',
    'core_upgrade_error' => 'Wyst�pi� b��d podczas uaktualnienia �r�d�a.',
    'correct_perms' => 'Popraw b��dy zidentyfikowane poni�ej. Kiedy ju� zostan� poprawione, u�yj przycisku <b>Sprawd� ponownie (Recheck)</b> by ponowi� sprawdzanie systemu.',
    'current' => 'Aktualne',
    'database_exists' => 'Baza danych zawiera ju� tablice glFusion. Przed now� instalacj� usu� te tablice.',
    'database_info' => 'Informacja Bazy Danych',
    'db_hostname' => 'Host Bazy Danych',
    'db_hostname_error' => 'Pole Host nie mo�e by� puste.',
    'db_name' => 'Nazwa Bazy Danych',
    'db_name_error' => 'Pole Nazwy nie mo�e by� puste.',
    'db_pass' => 'Has�o Bazy Danych',
    'db_table_prefix' => 'Prefix Tablic Bazy Danych',
    'db_type' => 'Typ Bazy Danych',
    'db_type_error' => 'Typ Bazy Danych musi by� wybrany',
    'db_user' => 'U�ytkownik Bazy Danych',
    'db_user_error' => 'Pole U�ytkownik nie mo�e by� puste.',
    'dbconfig_not_found' => 'Nie mo�na zlokalizowa� pliku db-config.php. Upewnij si�, �e istnieje.',
    'dbconfig_not_writable' => 'Plik db-config.php nie jest zapisywalny. Upewnij si�, �e serwer ma ustawione zazwolenia do zapisu tego pliku.',
    'directory_permissions' => 'Zezwolenia katalog�w',
    'enabled' => 'W��czone',
    'env_check' => 'Sprawdzanie �rodowiska',
    'error' => 'B��d',
    'file_permissions' => 'Zezwolenia plik�w',
    'file_uploads' => 'Wiele funkcji glFusion wymaga mo�liwo�ci wgrywania plik�w, ta opcja powinna by� w��czona.',
    'filemgmt' => 'Za�adowa� plugin FileMgmt (zarz�dzanie plikami)?',
    'filemgmt_desc' => 'Menad�er �ci�gania plik�w. Zarz�dzaj �atwo �ci�ganiem plik�w, organizuj je w kategorie.',
    'filesystem_check' => 'Sprawdzanie Plik�w Systemowych',
    'forum' => 'Za�adowa� plugin Forum?',
    'forum_desc' => 'System prowadzenia forum internetowego.',
    'hosting_env' => 'Sprawdzanie �rodowiska hostingu',
    'install' => 'Zainstaluj',
    'install_heading' => 'Instalacja glFusion',
    'install_steps' => 'KROKI INSTALACJI',
    'language' => 'J�zyk',
    'language_task' => 'J�zyk & Zadania',
    'libcustom_not_writable' => 'lib-custom.php nie jest zapisywalny.',
    'links' => 'Za�adowa� plugin Links?',
    'links_desc' => 'System zarz�dzania linkami. Zamie�� linki do innych ciekawych stron www, organizuj je w kategorie.',
    'load_sample_content' => 'Za�aduj Przyk�adow� Zawarto�� Strony?',
    'mediagallery' => 'Za�aduj pligin Media Gallery?',
    'mediagallery_desc' => 'System zarz�dzania plikami multimedialnymi. Mo�e by� u�yty jako prosta glaeria zdj�� lub jako rozbudowany system zarz�dzania mediami audio, video, oraz zdj��.',
    'memory_limit' => 'Zaleca si� aby mie� co najmniej 48M pami�ci, w��czonej dla twojej strony.',
    'missing_db_fields' => 'Wpisz wszystkie wymagane dane w pola.',
    'new_install' => 'Nowa Instalacja',
    'next' => 'Nast�pne',
    'no_db' => 'Mo�liwe, �e Baza Danych nie istnieje.',
    'no_db_connect' => 'Nie mo�na si� po��czy� z Baz� Danych',
    'no_innodb_support' => 'Wybra�e� MySQL z InnoDB jednak twoja Baza Danych nie obs�uguje indeks�w InnoDB.',
    'no_migrate_glfusion' => 'Nie mo�esz dokona� migracji z istniej�cej strony glFusion. Wybierz opcj� Aktualizacji.',
    'none' => '�aden',
    'not_writable' => 'Nie zapisywalne',
    'notes' => 'Notatki',
    'off' => 'Wy��cz',
    'ok' => 'OK',
    'on' => 'W��cz',
    'online_help_text' => 'Pomoc online w instalacji znajduje si� na stronie<br /> glFusion.org',
    'online_install_help' => 'Pomoc online w instlacji',
    'open_basedir' => 'Je�eli restrykcje <strong>open_basedir</strong> s� w��czone na twojej stronie, mo�e spowodowa� to problem z zezwoleniami podczas instalacji. System sprawdzania plik�w poni�ej powinien wskaza� ewentualne problemy.',
    'path_info' => 'Informacja �cie�ki',
    'path_prompt' => '�cie�ka do katalogu private/',
    'path_settings' => 'Ustawienia �cie�ki',
    'perform_upgrade' => 'Wykonaj Aktualizacj�',
    'php_req_version' => 'glFusion wymaga PHP w wersji 4.3.0 lub wy�szej.',
    'php_settings' => 'Ustawienia PHP',
    'php_version' => 'Wersja PHP',
    'php_warning' => 'Je�eli jaka� opcja na dole jest zaznaczona kolorem <span class="no">czerwonym</span>, mo�esz mie� problemy ze swoj� instalacj� glFusion. Skontaktuj si� ze swoim us�ugodawc� hostingu celem naniesienia niezb�dnych zmian w ustawieniach PHP.',
    'plugin_install' => 'Instalacja Pluginu',
    'plugin_upgrade_error' => 'Wyst�pi� b��d podczas aktualizacji pluginu %s ,sprawd� error.log by uzyska� wi�cej informacji.<br />',
    'plugin_upgrade_error_desc' => 'Nast�puj�ce pluginy nie zosta�y zaktualizowane. Sprawd� plik error.log by uzyska� wi�cej informacji.<br />',
    'polls' => 'Za�adowa� plugin Ankiet?',
    'polls_desc' => 'Interenetowy system ankiet. Prowad� ankiety na swojej stronie, niech u�ytkownicy mog� g�osowa� na konkretne pytania.',
    'post_max_size' => 'glFusion pozwala na wysy�anie plugin�w, zdj��/obrazk�w, oraz plik�w. Powiniene� mie� ustawione co najmniej 8M maksymalnej pami�ci dla wysy�ania.',
    'previous' => 'Powr�t',
    'proceed' => 'Post�p',
    'recommended' => 'Zalecenie',
    'register_globals' => 'Je�eli w PHP <strong>register_globals</strong> jest w��czone, mo�e to spowodowa� problemy bezpiecze�stwa.',
    'safe_mode' => 'Je�eli w PHP <strong>safe_mode</strong> jest w��czone, niekt�re funkcje glFusion mog� nie dzia�a� poprawnie. Szczeg�lnie plugin Media Gallery.',
    'samplecontent_desc' => 'Je�eli zaznaczone, zainstaluje przyk�adow� tre�� dla takich elelemnt�w jak: bloki, artyku�y oraz strony statyczne. <strong>Jest to zalecane dla nowych u�ytkownik�w glFusion.</strong>',
    'select_task' => 'Wybierz zadanie',
    'session_error' => 'Twoja sesja wygas�a. Ponownie uruchom proces instalacji.',
    'setting' => 'Ustawienia',
    'site_admin_url' => 'Adres URL Admina',
    'site_admin_url_error' => 'Adres URL Admina nie mo�e by� pusty.',
    'site_email' => 'Adres Email Strony',
    'site_email_error' => 'Adres Email Strony nie mo�e by� pusty.',
    'site_email_notvalid' => 'Adres Email Strony ma niepoprawn� sk�adni�.',
    'site_info' => 'Informacje strony',
    'site_name' => 'Nazwa Strony',
    'site_name_error' => 'Nazwa Strony nie mo�e by� pusta.',
    'site_noreply_email' => 'Bez zwrotny Adres Email',
    'site_noreply_email_error' => 'Bez zwrotny Adres Email nie mo�e by� pusty.',
    'site_noreply_notvalid' => 'Bez zwrotny Adres Email ma niepoprawn� sk�adni�.',
    'site_slogan' => 'Slogan strony',
    'site_upgrade' => 'Aktualizuj instniejac� stron� glFusion',
    'site_url' => 'Adres URL strony',
    'site_url_error' => 'Adres URL strony nie mo�e by� pusty.',
    'siteconfig_exists' => 'Znaleziono istniej�cy plik siteconfig.php. Usu� ten plik przed now� instalacj�.',
    'siteconfig_not_found' => 'Nie mo�na zlokalizowa� pliku siteconfig.php, czy jeste� pewny, �e dokonujesz aktualizacji?',
    'siteconfig_not_writable' => 'Plik siteconfig.php nie jest zapisywalny, lub katalog w kt�rym jest umieszczony plik siteconfig.php nie jest zapisywalny. Musisz poprawi� to przed kontynuacj�.',
    'sitedata_help' => 'Wybierz typ bazy danych z rozwijanej listy. To jest generalnie <strong>MySQL</strong>. Wybierz tak�e odpowiednie kodowanie. <strong>UTF-8</strong> - kodowanie powinno by� zaznaczone dla stron wieloj�zycznych.<br /><br /><br />Wpisz nazw� hosta sewera bazy danych. Nie koniecznie musi by� taki sam jak nazwa sewera strony, wi�c skontaktuj si� ze swoim us�ugodawc� je�eli nie jeste� pewny.<br /><br />Wpisz nazw� twojej bazy danych. <strong>Baza danych musi ju� istnie�.</strong> Je�eli nie znasz nazwy swojej bazy danych, skontaktuj si� ze swoim us�ugodawc�.<br /><br />Wpisz nazw� u�ytkownika by po��czy� si� z baz� danych. Je�eli nie znasz nazwy u�ytkownka bazy danych, skontaktuj si� ze swoim us�ugodawc�.<br /><br /><br />Wpisz has�o by po��czy� si� z baz� danych. Je�eli nie znasz has�a bazy danych, skontaktuj si� ze swoim us�ugodawc�.<br /><br />Wpisz prefiks jaki ma by� u�ywany w tabelach bazy danych. Jest to u�yteczne aby oddzieli� kilka stron zamieszczonych w systemie u�ywaj�cych tej samej bazy danych.<br /><br />Wpisz nazw� twojej strony. B�dzie si� ona wy�wietla� w nag��wku strony. Dla przyk�adu, glFusion lub Moja prywatna strona. Nie przejmuj si�, nazw� strony mo�na potem w ka�dej chwili zmieni�.<br /><br />Wpisz has�o sloganowe dla twojej strony. B�dzie si� wy�wietla� w nag��wku strony pod nazw� strony. Dla przyk�adu, zdj�cia - informacje - portfolio. Nie przejmuj si�, mo�na to potem w ka�dej chwili zmieni�.<br /><br />Wpisz g��wny adres email u�ywany przez stron�. Jest to adres dla domy�lnego konta Admina. Nie przejmuj si�, mo�na to potem w ka�dej chwili zmieni�.<br /><br />Wpisz bez zwrotny adres email. B�dzie u�ywany do automatycznego wysy�ania wiadomo�ci nowym u�ytkownikom, podczas resetowania has�a, oraz innych powiadomie�. Nie przejmuj si�, mo�na to potem w ka�dej chwili zmieni�.<br /><br />Potwierd�, �e jest to adres strony, lub URL u�ywany do dost�pu do strony g��wnej twojego serwisu.<br /><br /> Potwierd�, �e jest to adres strony lub URL u�ywany do dost�pu do sekcji administracyjnej twojego serwisu.',
    'sitedata_missing' => 'Nast�puj�ce problemy zosta�y wykryte z danymi jakie zosta�y przes�ane:',
    'system_path' => 'Ustawienia �cie�ki',
    'unable_mkdir' => 'Nie mo�na stworzy� katalogu',
    'unable_to_find_ver' => 'Nie mo�na zdefiniowa� wersji glFusion.',
    'upgrade_error' => 'B��d Aktualizacji',
    'upgrade_error_text' => 'B��d zosta� wykryty podczas aktualizacji glFusion.',
    'upgrade_steps' => 'KROKI AKTUALIZACJI',
    'upload_max_filesize' => 'glFusion daje ci mo�liwo�� wgrywania plugin�w, zdj��/obrazk�w, oraz plik�w. Powiniene� mie� co najmniej 8M pami�ci dla wgrywania plik�w.',
    'use_utf8' => 'U�yj kodowania UTF-8',
    'welcome_help' => 'Witaj w kreatorze instalacji CMS glFusion. Mo�esz zainstalowa� now� stron� opart� na glFusion, zaktualizowa� istniej�c� stron� opart� na glFusion, lub migrowa� ze strony opartej na Geeklog v1.4.1.<br /><br />Wybierz j�zyk kreatora instalacji, oraz zadanie do wykonania, nast�pnie przyci�nij <strong>Nast�pny</strong>.',
    'wizard_version' => 'v1.1.3.svn Kreator Instalacji',
    'system_path_prompt' => 'Wpisz pe�n�, absolutn� �cie�k� do serwera glFusion wskazuj�c katalog <strong>private/</strong>.<br /><br />Ten katalog zawiera plik <strong>db-config.php.dist</strong> lub <strong>db-config.php</strong>.<br /><br />Przyk�ady: /home/www/glfusion/private lub c:/www/glfusion/private.<br /><br /><strong>Wskaz�wka:</strong> Abslotutna �cie�ka do twojego katalogu <strong>public_html/</strong> <i>(nie <strong>private/</strong>)</i> to prawdopodobnie:<br /><br />%s<br /><br /><strong>Zaawansowane ustawienia</strong> pozwalaj� ci omin�� niekt�re domy�lne �cie�ki. Generalnie nie powinna by� potrzeba modyfikowania tych specificznych �cie�ek, system powinien je wykry� i ustawi� automatycznie.',
    'advanced_settings' => 'Zaawanasowane ustawienia',
    'log_path' => '�cie�ka log�w',
    'lang_path' => '�cie�ka j�zyk�w',
    'backup_path' => '�cie�ka kopi zapasowej',
    'data_path' => '�cie�ka plik�w',
    'language_support' => 'Pliki j�zykowe',
    'language_pack' => 'glFusion oparty jest o j�zyk Angielski, po instalacji mo�esz pobra� i zainstalowa� <a href="http://www.glfusion.org/filemgmt/viewcat.php?cid=18" target="_blank">Paczk� J�zykow� (Language Pack)</a> kt�ry zawiera obs�ugiwane pliki j�zykowe.',
    'libcustom_not_found' => 'Unable to located lib-custom.php.dist.',
    'no_db_driver' => 'You must have the MySQL extension loaded in PHP to install glFusion',
    'version_check' => 'Check For Updates',
    'check_for_updates' => "Goto the <a href=\"{$_CONF['site_admin_url']}/vercheck.php\">Upgrade Checker</a> to see if there are any glFusion CMS or Plugin updates available."
);

// +---------------------------------------------------------------------------+
// success.php

$LANG_SUCCESS = array(
    0 => 'Instalacja zako�czona',
    1 => 'Instalacja glFusion-a ',
    2 => ' zako�czona!',
    3 => 'Gratulacje, pomy�lnie ',
    4 => ' glFusion-a. Zapoznaj si� z informacjami zamieszczonymi poni�ej.',
    5 => 'Aby zalogowa� si� prosz� u�y� nast�puj�cego konta:',
    6 => 'U�ytkownik:',
    7 => 'Admin',
    8 => 'Has�o:',
    9 => 'password',
    10 => 'Powiadomienie bezpiecze�stwa',
    11 => 'Nie zapomnij zrobi�',
    12 => 'rzeczy',
    13 => 'Usu� lub zmie� nazw� katalogu z plikami instalacyjnymi,',
    14 => 'Zmie� has�o dla konta',
    15 => '.',
    16 => 'Ustaw zezwolenia',
    17 => 'i',
    18 => 'powr�t do',
    19 => '<strong>Informacja:</strong> Poniewa� model bezpiecze�stwa uleg� zmianie, stworzyli�my nowe konto z prawami jakie s� niezb�dne do zarz�dzania twoj� now� stron�. Nazwa u�ytkownika dla nowego konta to <b>NewAdmin</b> a has�o <b>password</b>.',
    20 => 'zainstalowano',
    21 => 'zaktualizowano'
);

?>