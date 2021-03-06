<?php
// +--------------------------------------------------------------------------+
// | FileMgmt Plugin - glFusion CMS                                           |
// +--------------------------------------------------------------------------+
// | xoopstree.php                                                            |
// |                                                                          |
// | Displays elements in tree format                                         |
// +--------------------------------------------------------------------------+
// | $Id:: xoopstree.php 3155 2008-09-16 02:13:18Z mevans0263                $|
// +--------------------------------------------------------------------------+
// | Copyright (C) 2002-2013 by the following authors:                        |
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

class XoopsTree{

    var $table;     //table with parent-child structure
    var $id;        //name of unique id for records in table $table
    var $pid;       // name of parent id used in table $table
    var $order;     //specifies the order of query results
    var $title;     // name of a field in table $table which will be used when  selection box and paths are generated
    var $db;
    var $filtersql = '';     // Selected list of groups allowed access

    //constructor of class XoopsTree
    //sets the names of table, unique id, and parend id
    function XoopsTree($db_name,$table_name, $id_name, $pid_name){
        $this->db = $db_name;
        $this->table = $table_name;
        $this->id = $id_name;
        $this->pid = $pid_name;
    }

    function setGroupAccessFilter($groups) {
        if (count($groups) == 1) {
            $this->filtersql = " AND grp_access = '" . current($groups) ."'";
        } else {
            $this->filtersql = " AND grp_access IN (" . implode(',',array_values($groups)) .")";
        }
    }

    function setGroupUploadAccessFilter($groups) {
        if (count($groups) == 1) {
            $this->filtersql = " AND grp_writeaccess = '" . current($groups) ."'";
        } else {
            $this->filtersql = " AND grp_writeaccess IN (" . implode(',',array_values($groups)) .")";
        }
    }

    // returns an array of first child objects for a given id($sel_id)
    function getFirstChild($sel_id, $order=""){
        $arr = array();

        if ( $order != "" ) {
            $result = DB_query("SELECT * FROM {$this->table} WHERE {$this->pid} = {$sel_id} $this->filtersql ORDER BY $order");
        } else {
            $result = DB_query("SELECT * FROM {$this->table} WHERE {$this->pid} = {$sel_id} $this->filtersql");
        }
        $count  = DB_numRows($result);
        if ( $count==0 ) {
            return $arr;
        }
        while ( $myrow=DB_fetchArray($result,false) ) {
            array_push($arr, $myrow);
        }
        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)
    function getFirstChildId($sel_id){
        $idarray =array();
        $result = DB_query("SELECT $this->id FROM $this->table WHERE $this->pid = '$sel_id'");
        $count  = DB_numRows($result);
        if ( $count == 0 ) {
            return $idarray;
        }
        while ( list($id) = DB_fetchArray($result) ) {
            array_push($idarray, $id);
        }
        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)
    function getAllChildId($sel_id,$order="",$idarray = array()){
        //$sql = "SELECT ".$this->id." FROM ".$this->table." WHERE ".$this->pid."=".$sel_id."";
        if ( $order != "" ) {
            $result = DB_query("SELECT $this->id FROM $this->table WHERE $this->pid = '$sel_id' ORDER BY $order");
        } else {
            $result = DB_query("SELECT $this->id FROM $this->table WHERE $this->pid = '$sel_id'");
        }

        //$result=$this->db->query($sql);
        //$count = $this->db->getRowsNum($result);

        if ( DB_numRows($result) == 0 ) {
            return $idarray;
        }
        while ( list($r_id) = DB_fetchArray($result) ) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id,$order,$idarray);
        }
        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)
    function getAllParentId($sel_id,$order="",$idarray = array()){
        $sql = "SELECT $this->pid FROM $this->table WHERE $this->id = '$sel_id'";
        if ( $order != "" ) {
            $sql .= " ORDER BY $order";
        }
        $result=$this->db->query($sql);
        list($r_id) = $this->db->fetchRow($result);
        if ( $r_id == 0 ) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id,$order,$idarray);
        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)
    // the path is delimetered with "/"
    function getPathFromId($sel_id, $title, $path=""){
        $result = DB_query("SELECT $this->pid, $title FROM $this->table WHERE $this->id = '$sel_id'");
           if ( DB_numRows($result) == 0 ) {
            return $path;
        }
        list($parentid,$name) = DB_fetchArray($result);
        $path = "/".$name.$path."";
        if ( $parentid == 0 ) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);
        return $path;
    }

    function makeMySelBoxNoHeading($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange=""){
    $retval = '';

    if ( $sel_name == "" ) {
        $sel_name = $this->id;
    }
    $myts = MyTextSanitizer::getInstance();

    $sql = "SELECT $this->id, $title FROM $this->table WHERE $this->pid = 0 $this->filtersql ";
    if ( $order != "" ) {
        $sql .= " ORDER BY $order";
    }
    $result = DB_query($sql);
    if ( $none ) {
        $retval .= "<option value='0'>----</option>\n";
    }
    while ( list($catid, $name) = DB_fetchARRAY($result) ) {
        if ( $catid == $preset_id ) {
            $sel = " selected='selected'";
        } else {
            $sel = '';
        }
        $retval .= "<option value='$catid'$sel>$name</option>\n";
        $sel = "";
        $arr = $this->getChildTreeArray($catid);
        foreach ( $arr as $option ) {
            $option['prefix'] = str_replace(".","--",$option['prefix']);
            $catpath = $option['prefix']."&nbsp;".$myts->makeTboxData4Show($option[$title]);
            if ( $option[$this->id] == $preset_id ) {
                $sel = " selected='selected'";
            }
            $retval .= "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
            $sel = "";
        }
    }

    return $retval;
    }
    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="",$exclude=''){
    if ( $sel_name == "" ) {
        $sel_name = $this->id;
    }
    $myts = MyTextSanitizer::getInstance();
    $retval = "<select name='".$sel_name."'";
    if ( $onchange != "" ) {
        echo " onchange='".$onchange."'";
    }
    $retval .= ">\n";
    $sql = "SELECT $this->id, $title FROM $this->table WHERE $this->pid = 0 $this->filtersql ";
    if ( $order != "" ) {
        $sql .= " ORDER BY $order";
    }
    $result = DB_query($sql);
    if ( $none ) {
        $retval .= "<option value='0'>----</option>\n";
    }

    while ( list($catid, $name) = DB_fetchARRAY($result) ) {
        if ( $catid == $exclude ) {
            continue;
        }
        if ( $catid == $preset_id ) {
            $sel = " selected='selected'";
        } else {
            $sel = '';
        }
        $retval .= "<option value='$catid'$sel>$name</option>\n";
        $sel = "";
        $arr = $this->getChildTreeArray($catid);
        foreach ( $arr as $option ) {
            if ( $option[$this->id] == $exclude ) {
                continue;
            }
            $option['prefix'] = str_replace(".","--",$option['prefix']);
            $catpath = $option['prefix']."&nbsp;".$myts->makeTboxData4Show($option[$title]);
            if ( $option[$this->id] == $preset_id ) {
                $sel = " selected='selected'";
            }
            $retval .= "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
            $sel = "";
        }
    }
    $retval .= "</select>\n";

    return $retval;
    }

    //generates nicely formatted linked path from the root id to a given id
    function getNicePathFromId($sel_id, $title, $funcURL, $path=""){
        $sql = "SELECT $this->pid, $title FROM $this->table WHERE $this->id = '$sel_id'";
        $result = DB_query($sql);
        if ( DB_numROWS($result) == 0 ) {
            return $path;
        }
        list($parentid,$name) = DB_fetchARRAY($result);
        $myts = MyTextSanitizer::getInstance();
        $name = $myts->makeTboxData4Show($name);
        if (strpos($funcURL,'?',0) === FALSE) {
            $path = "<a href=\"{$funcURL}?{$this->id}={$sel_id}\">{$name}</a>&nbsp;:&nbsp;{$path}";
        } else {
            $path = "<a href=\"{$funcURL}&{$this->id}={$sel_id}\">{$name}</a>&nbsp;:&nbsp;{$path}";
        }
        if ( $parentid == 0 ) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);
        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    function getIdPathFromId($sel_id, $path=""){
        $result = $this->db->query("SELECT $this->pid FROM $this->table WHERE $this->id = '$sel_id'");
        if ( $this->db->getRowsNum($result) == 0 ) {
            return $path;
        }
        list($parentid) = $this->db->fetchRow($result);
        $path = "/".$sel_id.$path."";
        if ( $parentid == 0 ) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);
        return $path;
    }


    function getAllChild($sel_id=0,$order="",$parray = array()){
        $sql = "SELECT * FROM $this->table WHERE $this->pid = '$sel_id'";
        if ( $order != "" ) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ( $count == 0 ) {
            return $parray;
        }
        while ( $row = DB_fetchARRAY($result) ) {
            array_push($parray, $row);
            $parray=$this->getAllChild($row[$this->id],$order,$parray);
        }
        return $parray;
    }

    function getChildTreeArray($sel_id=0,$order="",$parray = array(),$r_prefix="") {
        $sql = "SELECT * FROM $this->table WHERE $this->pid = '$sel_id' $this->filtersql ";
        if ( $order != "" ) {
            $sql .= " ORDER BY $order";
        }
        $result = DB_query($sql);
        $count = DB_numROWS($result);
        if ( $count == 0 ) {
            return $parray;
        }
        while ( $row = DB_fetchARRAY($result) ) {
            $row['prefix'] = $r_prefix.".";
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id],$order,$parray,$row['prefix']);
        }
        return $parray;
    }

}
?>