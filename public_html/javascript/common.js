// +--------------------------------------------------------------------------+
// | glFusion CMS                                                             |
// +--------------------------------------------------------------------------+
// | common.js                                                                |
// |                                                                          |
// | Commmon javascript functions                                             |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// |                                                                          |
// | Based on the Geeklog CMS                                                 |
// | Copyright (C) 2005-2008 by the following authors:                        |
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

// -------------------------------------------------------------------
// caItems(form object)
// Check All Items - generic function that can be used to check and un-check all items in a list
// Used in the Admin Lists - like on the moderation page
// -------------------------------------------------------------------
   function caItems(f, name) {
       var n=f.elements.length;
       for (i=0;i<n; i++) {
           var field=f.elements[i];
           if (field.type == 'checkbox' && field.name.match(name)) {
                if (f.chk_selectall.checked) {
                    field.checked=true;
                } else {
                    field.checked=false;
                }
           }

       }
   }

// Basic function to show/hide (toggle) an element - pass in the elment id
    function elementToggle(id) {
        var obj = document.getElementById(id);
        if (obj.style.display == 'none') {
            obj.style.display = '';
        } else {
            obj.style.display = 'none';
        }
    }

// Basic function to show/hide an element - pass in the elment id and option.
// Where option can be: show or hide or toggle
    function elementShowHide(id,option) {
        var obj = document.getElementById(id);
        if (option == 'hide') {
            obj.style.display = 'none';
        } else if (option == 'show') {
            obj.style.display = '';
        } else if (option == 'toggle') {
            elementToggle(id);
        }
    }

    /**
    * Pops up a new window in the middle of the screen
    */
    function popupWindow(mypage, myname, w, h, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
        win = window.open(mypage, myname, winprops)
        if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
    }

    /**
    * Event handler
    */
    function adddwEvent(element, type, handler) {
        // assign each event handler a unique ID
        if (!handler.$$guid) handler.$$guid = adddwEvent.guid++;
        // create a hash table of event types for the element
        if (!element.events) element.events = {};
        // create a hash table of event handlers for each element/event pair
        var handlers = element.events[type];
        if (!handlers) {
            handlers = element.events[type] = {};
            // store the existing event handler (if there is one)
            if (element["on" + type]) {
                handlers[0] = element["on" + type];
            }
        }
        // store the event handler in the hash table
        handlers[handler.$$guid] = handler;
        // assign a global event handler to do all the work
        element["on" + type] = handleEvent;
    };
    // a counter used to create unique IDs
    adddwEvent.guid = 1;

    // double confirmation (are you really sure?)
    function doubleconfirm( msg1, msg2 ) {
        return confirm(msg1) && confirm(msg2);
    }
    
    //widget wrapper iframe buster (load links to parent site in parent window)
    if (top.location != location) {
        top.location.href = document.location.href;
    }
