<?php

// $Id:$
// =======================================================================
// Pagesetter by Jorn Lind-Nielsen (C) 2003.
// ----------------------------------------------------------------------
// For POST-NUKE Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WithOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// =======================================================================

function pagesetter_weblinks_selectlink($args) {

	$cid = Formutil::getPassedValue('cid');
	$lid = Formutil::getPassedValue('lid');

	// Security check 
	if (!SecurityUtil :: checkPermission('Web_Links::', '::', ACCESS_READ))
		return LogUtil :: registerPermissionError();

    if ($cid != false) {
     	$lid = -1;
    } else {
	    $link = pnModAPIFunc('Web_Links', 'user', 'link', array ('lid' => $lid));
	    $cid = ( $link == false ? 1 : $link['cat_id']);
    }

	// Get all Web Links
	$orderbysql = pnModAPIFunc('Web_Links', 'user', 'orderbyin', array ('orderby' => "titleA"));
	$links = pnModAPIFunc('Web_Links', 'user', 'weblinks', array ('cid' => $cid, 'orderbysql'=> $orderbysql));
	
	$categories = pnModAPIFunc('Web_Links', 'user', 'categories', array ());

	$render = new pnRender('pagesetter');
	$render->assign('links', $links);
	$render->assign('lid', $lid);
	$render->assign('categories', $categories);
	$render->assign('cid', $cid);
	$render->display('weblinks_select.html');

	return true;
}

function pagesetter_weblinks_alllinks($args) {

	// Security check 
	if (!SecurityUtil :: checkPermission('Web_Links::', '::', ACCESS_READ))
		return LogUtil :: registerPermissionError();

	$categories = pnModAPIFunc('Web_Links', 'user', 'categories', array ());
	$orderbysql = pnModAPIFunc('Web_Links', 'user', 'orderbyin', array ('orderby' => "titleA"));
	$alllinks = array();
    foreach ($categories as $category) {
		$links = pnModAPIFunc('Web_Links', 'user', 'weblinks', array ('cid' => $category['cat_id'], 'orderbysql'=> $orderbysql));
		foreach ($links as $link) {
			$alllinks[$link['lid']] = $link['title'];
		}
    }
    asort($alllinks);
	return $alllinks;
}


function pagesetter_weblinks_removelink($args) {

	$tid = pnVarCleanFromInput('tid');
	$pid = pnVarCleanFromInput('pid');
    if (!isset($pid)) {
    	$id = pnVarCleanFromInput('id');
    	if (isset($id)) 
    		$pid = pnModAPIFunc('pagesetter','relations','id2Pid',array('tid' => $tid,'id' => $id));
    }

	$lid = $args['lid'];
	$link = pnModAPIFunc('Web_Links', 'admin', 'getmodlink', array ('lid' => $lid));
	if ($link == false)	return false;
	
	$text = $link['description'];
	$tag = '<a href="index.php?module=pagesetter&func=viewpub&tid='.$tid.'&pid='.$pid.'">';
	$pattern = '/<br \/>'.preg_quote($tag).'(.*)<\/a>/';
    if (preg_match($pattern, $text) == 1) {
		// Publication update, link already present: update title
		$text = preg_replace($pattern, '', $text);
	}
	$link['description'] = $text;
	$result = DBUtil::updateObject($link, 'links_links', '', 'lid');
	return false;
}

function pagesetter_weblinks_updatelink($args) {

	$lid = $args['lid'];
	$link = pnModAPIFunc('Web_Links', 'admin', 'getmodlink', array ('lid' => $lid));
	if ($link == false)	return false;
	
	$text = $link['description'];
	$tag = '<a href="index.php?module=pagesetter&func=viewpub&tid='.$args['tid'].'&pid='.$args['pid'].'">';
	$pattern = '/'.preg_quote($tag).'(.*)<\/a>/';
    if (preg_match($pattern, $text) == 1) {
		// Publication update, link already present: update title
		$text = preg_replace($pattern, $tag.$args['title'].'</a>', $text);
	} else {
		// Publication creation
		if ($text != '') {
			$text .= "<br />";
		}
		$text .= $tag.$args['title'].'</a>';
	}
	$link['description'] = $text;
	$result = DBUtil::updateObject($link, 'links_links', '', 'lid');
	return false;
}


?>