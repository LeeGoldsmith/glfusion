// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | storyeditor_fckeditor.js                                                 |
// |                                                                          |
// | Javascript functions for FCKEditor Integration into glFusion             |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2002-2008 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Geeklog CMS                                                 |
// | Copyright (C) 2000-2008 by the following authors:                        |
// |                                                                          |
// | Authors:   Blaine Lang - blaine@portalparts.com                          |
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

    var undefined;

    window.onload = function() {
        var oFCKeditor1 = new FCKeditor( 'introhtml' ) ;
        oFCKeditor1.BasePath = glfusionEditorBasePath;
        oFCKeditor1.Config['CustomConfigurationsPath'] = glfusionEditorBaseUrl + '/fckeditor/myconfig.js';
        oFCKeditor1.ToolbarSet = 'editor-toolbar3' ;
        if ( undefined != window.glfusionStyleBasePath ) {
            oFCKeditor1.Config['EditorAreaCSS'] = glfusionStyleBasePath + 'style.css';
            oFCKeditor1.Config['StylesXmlPath'] = glfusionStyleBasePath + 'fckstyles.xml';
        }
        oFCKeditor1.Height = 300 ;
        oFCKeditor1.ReplaceTextarea() ;

        var oFCKeditor2 = new FCKeditor( 'bodyhtml' ) ;
        oFCKeditor2.BasePath = glfusionEditorBasePath ;
        oFCKeditor2.Config['CustomConfigurationsPath'] = glfusionEditorBaseUrl + '/fckeditor/myconfig.js';
        oFCKeditor2.ToolbarSet = 'editor-toolbar3' ;
        if ( undefined != window.glfusionStyleBasePath ) {
            oFCKeditor2.Config['EditorAreaCSS'] = glfusionStyleBasePath + 'style.css';
            oFCKeditor2.Config['StylesXmlPath'] = glfusionStyleBasePath + 'fckstyles.xml';
        }
        oFCKeditor2.Height = 300 ;
        oFCKeditor2.ReplaceTextarea() ;
    }

    function change_editmode(obj) {
        if (obj.value == 'html') {
            document.getElementById('html_editor').style.display='none';
            document.getElementById('text_editor').style.display='';
            document.getElementById('editor_mode').style.display='';
            swapEditorContent('html','introhtml');
            swapEditorContent('html','bodyhtml');
        } else if (obj.value == 'adveditor') {
            document.getElementById('text_editor').style.display='none';
            document.getElementById('html_editor').style.display='';
            document.getElementById('editor_mode').style.display='';
            swapEditorContent('adveditor','introhtml');
            swapEditorContent('adveditor','bodyhtml');
        } else {
            document.getElementById('html_editor').style.display='none';
            document.getElementById('text_editor').style.display='';
            document.getElementById('editor_mode').style.display='none';
            swapEditorContent('text','introhtml');
            swapEditorContent('text','bodyhtml');
        }
    }

    function changeHTMLTextAreaSize(element, option) {
        var size = 0;
        var size = document.getElementById(element + '___Frame').height;
        if (option == 'larger') {
            document.getElementById(element + '___Frame').height = +(size) + 50;

        } else if (option == 'smaller') {
            document.getElementById(element + '___Frame').height = +(size) - 50;
        }
    }

    function changeTextAreaSize(element, option) {
        var size = 0;
        var size = document.getElementById(element).rows;
        if (option == 'larger') {
            document.getElementById(element).rows = +(size) + 3;
        } else if (option == 'smaller') {
            document.getElementById(element).rows = +(size) - 3;
        }
    }


    function getEditorContent(instanceName) {
        // Get the editor instance that we want to interact with.
        var oEditor = FCKeditorAPI.GetInstance(instanceName) ;
        // return the editor contents in XHTML.
        return oEditor.GetXHTML( true );
    }

    function swapEditorContent(curmode,instanceName) {
        var content = '';
        var oEditor = FCKeditorAPI.GetInstance(instanceName) ;
        //alert(curmode + ':' + instanceName);
        if (curmode == 'adveditor') { // Switching from Text to HTML mode
            // Get the content from the textarea 'text' content and copy it to the editor
            if (instanceName == 'introhtml' )  {
                content = document.getElementById('introtext').value;
                //alert('Intro :' + instanceName + '\n' + content);
            } else {
                content = document.getElementById('bodytext').value;
                //alert('HTML :' + instanceName + '\n' + content);
            }
            oEditor.SetHTML(content);
        } else {
               content = getEditorContent(instanceName);
              if (instanceName == 'introhtml' )  {
                  document.getElementById('introtext').value = content;
              } else {
                  document.getElementById('bodytext').value = content;
              }
          }
    }

    function set_postcontent() {
        if (document.getElementById('sel_editmode').value == 'adveditor') {
            document.getElementById('introtext').value = getEditorContent('introhtml');
            document.getElementById('bodytext').value = getEditorContent('bodyhtml');
        }
    }

   function changeToolbar(toolbar) {
        var oEditor1 = FCKeditorAPI.GetInstance('introhtml');
        oEditor1.ToolbarSet.Load( toolbar ) ;
        var oEditor2 = FCKeditorAPI.GetInstance('bodyhtml');
        oEditor2.ToolbarSet.Load( toolbar ) ;
   }