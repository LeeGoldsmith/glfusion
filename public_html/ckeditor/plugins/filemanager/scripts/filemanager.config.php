<?php
require_once '../../../../lib-common.php';
$inRoot = SEC_inGroup('Root');

$urlparts = parse_url($_CONF['site_url']);
if ( isset($urlparts['path']) ) {
    $fileroot = $urlparts['path'];
} else {
    $fileroot = '';
}
if ( COM_isAnonUser() ) {
    $uid = 1;
} else {
    $uid = $_USER['uid'];
}
if ( $inRoot ) {
    $filePath = $fileroot . '/images/library/';
} else {
    $filePath = $fileroot . $_CK_CONF['filemanager_fileroot'];
    if ( $_CK_CONF['filemanager_per_user_dir'] ) {
        $filePath = $fileroot . $_CK_CONF['filemanager_fileroot'] . $uid . '/';
        if ( !is_dir($_CONF['path_html'].$filePath) ) {
            $rc = @mkdir($_CONF['path_html'].$filePath, 0777, true);
            if ( $rc === false ) {
                $filePath = $fileroot . $_CK_CONF['filemanager_fileroot'];
            }
        }
    }
}
if ( $inRoot ) {
    $capabilities = array("select", "download", "rename", "move", "delete", "replace");
} else if ($_CK_CONF['filemanager_per_user_dir'] ) {
    $capabilities = array("select", "rename", "move", "delete", "replace");
} else {
    $capabilities = array("select");
}
$fmconfiguration = array(
	"_comment" =>  "IMPORTANT  =>  go to the wiki page to know about options configuration https => //github.com/simogeo/Filemanager/wiki/Filemanager-configuration-file",
    "options" =>  array(
        "culture" =>  $_CONF['iso_lang'],
        "lang" =>  "php",
        "defaultViewMode" =>  $_CK_CONF['filemanager_default_view_mode'],
        "autoload" =>  true,
        "showFullPath" =>  false,
        "showTitleAttr" =>  false,
        "browseOnly" =>  (COM_isAnonUser() ? true : $_CK_CONF['filemanager_browse_only']),
        "showConfirmation" =>  $_CK_CONF['filemanager_show_confirmation'],
        "showThumbs" =>  $_CK_CONF['filemanager_show_thumbs'],
        "generateThumbnails" =>  $_CK_CONF['filemanager_generate_thumbnails'],
        "searchBox" =>  $_CK_CONF['filemanager_search_box'],
        "listFiles" =>  true,
        "fileSorting" =>  $_CK_CONF['filemanager_file_sorting'],
        "chars_only_latin" =>  $_CK_CONF['filemanager_chars_only_latin'],
        "dateFormat" =>  $_CK_CONF['filemanager_date_format'],
        "serverRoot" =>  true,
        "fileRoot" =>  $filePath,
        "relPath" =>  false,
        "logger" =>  false,
        "capabilities" =>  $capabilities,
        "plugins" =>  array()
    ),
    "security" =>  array(
        "uploadPolicy" =>  "DISALLOW_ALL",
        "uploadRestrictions" =>  explode(',',$_CK_CONF['filemanager_upload_restrictions'])
    ),
    "upload" =>  array(
        "overwrite" =>  $_CK_CONF['filemanager_upload_overwrite'],
        "imagesOnly" =>  $_CK_CONF['filemanager_upload_images_only'],
        "fileSizeLimit" =>  $_CK_CONF['filemanager_upload_file_size_limit']
    ),
    "exclude" =>  array(
        "unallowed_files" =>  explode(',',$_CK_CONF['filemanager_unallowed_files']),
        "unallowed_dirs" =>  explode(',',$_CK_CONF['filemanager_unallowed_dirs']),
        "unallowed_files_REGEXP" =>  $_CK_CONF['filemanager_unallowed_files_regexp'],
        "unallowed_dirs_REGEXP" =>  $_CK_CONF['filemanager_unallowed_dirs_regexp']
    ),
    "images" =>  array(
        "imagesExt" =>  explode(',',$_CK_CONF['filemanager_images_ext']),
        "resize" =>  array(
        	"enabled" => true,
        	"maxWidth" =>  1280,
            "maxHeight" =>  1024
        )
    ),
    "videos" =>  array(
        "showVideoPlayer" =>  $_CK_CONF['filemanager_show_video_player'],
        "videosExt" =>  explode(',',$_CK_CONF['filemanager_videos_ext']),
        "videosPlayerWidth" =>  $_CK_CONF['filemanager_videos_player_width'],
        "videosPlayerHeight" =>  $_CK_CONF['filemanager_videos_player_height']
    ),
    "audios" =>  array (
        "showAudioPlayer" =>  $_CK_CONF['filemanager_show_audio_player'],
        "audiosExt" =>  explode(',',$_CK_CONF['filemanager_audios_ext'])
    ),
    "edit" =>  array(
        "enabled" =>  $_CK_CONF['filemanager_edit_enabled'],
        "lineNumbers" =>  $_CK_CONF['filemanager_editor_linenumbers'],
        "lineWrapping" =>  $_CK_CONF['filemanager_editor_linewrapping'],
        "codeHighlight" =>  $_CK_CONF['filemanager_editor_codehighlight'],
        "theme" =>  "elegant",
        "editExt" =>  explode(',',$_CK_CONF['filemanager_edit_editext'])
    ),
    "extras" =>  array(
        "extra_js" =>  array(),
        "extra_js_async" =>  true
    ),
    "icons" =>  array(
        "path" =>  "images/fileicons/",
        "directory" =>  "_Open.png",
        "default" =>  "default.png"
    )
);

echo json_encode($fmconfiguration);
?>
