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

require_once 'modules/MyMap/pnuser.php';

function pagesetter_mymap_selectmap($args) {

	$mid = Formutil::getPassedValue('mid');
	$targetID = Formutil::getPassedValue('targetID');

	// Security check 
	if (!SecurityUtil :: checkPermission('MyMap::', '$mid::', ACCESS_READ))
		return LogUtil :: registerPermissionError();

	// Get the map that should be displayed
	if (isset ($mid) && ($mid > 0))
		$map = pnModAPIFunc('MyMap', 'user', 'getMaps', array (
			'id' => $mid
		));
	if (!isset ($mid) || (!($mid > 0)) || (empty ($map))) {
		logUtil :: registerError(_MYMAPMAPNOTFOUND);
		return pnRedirect(pnModURL('MyMap', 'user', 'main'));
	}

	// get the map's center point
	$markers = pnModAPIFunc('MyMap', 'user', 'getMarkers', array (
		'mid' => $mid
	));
	$center = pnModAPIFunc('MyMap', 'user', 'getCenter', $markers);
	$waypoints = pnModAPIFunc('MyMap', 'user', 'getWayPoints', array (
		'mid' => $mid
	));
	$map['centerlat'] = str_replace(',','.',$center['lat']);
	$map['centerlng'] = str_replace(',','.',$center['lng']);

	// we need this info for ajax waypoints loading
	$map['url_wps'] = pnmodurl('MyMap', 'ajax', 'loadWaypoints', array (
		'mid' => $map['id']
	));

	// We need some javascript
	pnModAPIFunc('MyMap', 'user', 'addMapJS');

	// Create output object
	$render = new pnRender('pagesetter');
	$render->assign('map', $map);
	$uid = pnUserGetVar('uid');
	$render->assign('uid', $uid);
	$render->assign('targetID', $targetID);
	$render->assign('hook', '1');
	$render->assign('clickzoom', '1');
	$render->assign('provider', pnModGetVar('MyMap', 'provider'));
	$render->assign('uname', pnUserGetVar('uname', $map['uid']));
	$render->assign('markers', $markers);
	$render->assign('waypoints', $waypoints);
	$render->assign('provider', pnModGetVar('MyMap', 'provider'));
	$render->assign('googlemapApiKey', pnModGetVar('MyMap', 'key_google'));
	$render->assign('themeCssURL', ThemeUtil :: getModuleStylesheet('MyMap'));
	if (pnModGetVar('MyMap', 'map_overview') == 1)
		$map_overview = 'true';
	else
		$map_overview = 'false';
	$render->assign('map_overview', $map_overview);
	$render->display('mymap_select.html');

	return true;
}


/* ****************************** handler for FormUtil ********************************* */
class pagesetter_mymap_editPointHandler
{
	var $id;
	var $mid;
	function initialize(&$render)
	{
		$this->id = (int)FormUtil::getPassedValue('id', 0);
		$this->mid = (int)FormUtil::getPassedValue('mid', 0);
		if ($this->id > 0) {
			$data = DBUtil::selectObjectByID('mymap_markers', $this->id);
			$render->assign($data);
			PageUtil::AddVar('body','onload="javascript:toggledisplay(\'mymap_hiddendiv_addmarkers\',\'indicator_addmarkers\'); return false;"');
		}
		return true;
	}
	function handleCommand(&$render, &$args)
	{
		if ($args['commandName']=='update') {
			// Security check 
			if (!SecurityUtil::checkPermission('MyMap::', '$mid::', ACCESS_COMMENT)) return LogUtil::registerPermissionError();
			$map = pnModAPIFunc('MyMap','user','getMaps',array('id'=>$this->mid));
			$uid = pnUserGetVar('uid');
			if (($map['uid'] != $uid) && ($map['wiki']==0)) return LogUtil::registerPermissionError();

			// We need to check if there is lat and lng submitted or if we have to get the coordinates via webservice
			PageUtil::AddVar('body','onload="javascript:toggledisplay(\'mymap_hiddendiv_addmarkers\',\'indicator_addmarkers\'); return false;"');
			$obj	= $render->pnFormGetValues();
			$id 	= $this->id;
			$uid 	= pnUserGetVar('uid');
			if ($this->id > 0) $obj['id']=$id;
			if (!isset($obj['lng']) || (!isset($obj['lat']))) {
				$coord=pnModAPIFunc('MyMap','user','getCoord',$obj);
				if (!$coord) {
				  	LogUtil::registerError(_MYMAPCOORDNOTRETRIEVABLE);
				  	return false;
				}
				srand(microtime()*1000000);
				if (count($coord)==1) {
					$c=$coord[0];
					$obj['postalcode'] 		= $c['postalcode'];
					$obj['placename'] 		= $c['placename'];
					$obj['countrycode'] 	= $c['countrycode'];
					$obj['lat'] 			= (float)$c['lat'];
					$obj['lng'] 			= (float)$c['lng'];
					// shuffle a little bit... lat +- 0.004 lng +- 0.004
					$obj['lat']+=0.00001*(rand(1,800)-400);
					$obj['lng']+=0.00001*(rand(1,800)-400);
					LogUtil::registerStatus(_MYPROFILECOORDRETRIEVED.': LAT ['.$obj['lat'].'] - LNG ['.$obj['lng'].']');
				}
				else {
					foreach ($coord as $c) $coords.=$c['postalcode'].' '.$c['placename'].' ('.$c['countrycode'].', '.$c['adminname1'].'), ';
					$coords.=_MYMAPCHOOSEONE.'!';
					LogUtil::registerStatus(_MYMAPMULTIFOUND.' '.$coords);
					return true;
				}
			}
			if (!$render->pnFormIsValid()) return false;
			$obj['uid']=$uid;
			$obj['mid']=$this->mid;
			// we need to check if a user wants to add a point to an own map or 
			// if another user has the permission to add something to the actual map.
			if (!isset($obj['lng']) || (!isset($obj['lat']))) {
				LogUtil::registerError(_MYMAPCOORDSMISSING);
				return false;
			}
			if ($id>0) {
				$result = DBUtil::updateObject($obj, 'mymap_markers');
				if ($result) LogUtil::registerStatus(_MYMAPPOINTUPDATED);
			}
			else {
				$obj['id'] = $this->id;
				DBUtil::insertObject($obj, 'mymap_markers');
				LogUtil::registerStatus(_MYMAPPOINTADDED);
				if (pnModGetVar('MyMap','notify') == 1) {
					if (pnUserGetVar('uid')!=$map['uid']) {
						pnModAPIFunc('MyMap','user','notify',array('map'=>$map,'marker'=>$obj));
						LogUtil::registerStatus(_MYMAPNOTIFYSENT);
					}
				}
				return pnRedirect(pnGetBaseURL().pnModURL('MyMap','user','display',array('mid'=>$obj['mid'])));
			}
		}
		return true;
    }
}

function pagesetter_mymap_addmarker($args) {
	$mid = Formutil::getPassedValue('mid');

			// Security check 
			if (!SecurityUtil::checkPermission('MyMap::', '$mid::', ACCESS_COMMENT)) return LogUtil::registerPermissionError();
			$map = pnModAPIFunc('MyMap','user','getMaps',array('id'=>$mid));
			$uid = pnUserGetVar('uid');
			if (($map['uid'] != $uid) && ($map['wiki']==0)) return LogUtil::registerPermissionError();

			// We need to check if there is lat and lng submitted or if we have to get the coordinates via webservice
//			PageUtil::AddVar('body','onload="javascript:toggledisplay(\'mymap_hiddendiv_addmarkers\',\'indicator_addmarkers\'); return false;"');
//			$obj	= $render->pnFormGetValues();
//			$id 	= $this->id;
//			$uid 	= pnUserGetVar('uid');
//			if ($this->id > 0) $obj['id']=$id;
//			if (!isset($obj['lng']) || (!isset($obj['lat']))) {
//				$coord=pnModAPIFunc('MyMap','user','getCoord',$obj);
//				srand(microtime()*1000000);
//				if (count($coord)==1) {
//					$c=$coord[0];
					$obj['postalcode'] 		= Formutil::getPassedValue('postalcode');
					$obj['placename'] 		= Formutil::getPassedValue('placename');
					$obj['countrycode'] 	= Formutil::getPassedValue('countrycode');
			$obj['lat']= (float) Formutil::getPassedValue('lat');
			$obj['lng']= (float) Formutil::getPassedValue('lng');
///					// shuffle a little bit... lat +- 0.004 lng +- 0.004
//					$obj['lat']+=0.00001*(rand(1,800)-400);
//					$obj['lng']+=0.00001*(rand(1,800)-400);
//					// hack to avoid troubles on some installations
					$obj['lat'] = str_replace(',','.',$obj['lat']);
					$obj['lng'] = str_replace(',','.',$obj['lng']);
//				}
//				else {
//					foreach ($coord as $c) $coords.=$c['postalcode'].' '.$c['placename'].' ('.$c['countrycode'].', '.$c['adminname1'].'), ';
//					$coords.=_MYMAPCHOOSEONE.'!';
//					LogUtil::registerStatus(_MYMAPMULTIFOUND.' '.$coords);
//					return true;
//				}
//			}
//			if (!$render->pnFormIsValid()) return false;
			$obj['uid']=$uid;
			$obj['mid']=$mid;
//			// we need to check if a user wants to add a point to an own map or 
//			// if another user has the permission to add something to the actual map.
//			if (!isset($obj['lng']) || (!isset($obj['lat']))) {
//				LogUtil::registerError(_MYMAPCOORDSMISSING);
//				return false;
//			}
//			if ($id>0) {
//				$result = DBUtil::updateObject($obj, 'mymap_markers');
//				if ($result) LogUtil::registerStatus(_MYMAPPOINTUPDATED);
//			}
//			else {
//				$obj['id'] = $this->id;
				DBUtil::insertObject($obj, 'mymap_markers');
				LogUtil::registerStatus(_MYMAPPOINTADDED);
				if (pnModGetVar('MyMap','notify') == 1) {
					if (pnUserGetVar('uid')!=$map['uid']) {
						pnModAPIFunc('MyMap','user','notify',array('map'=>$map,'marker'=>$obj));
						LogUtil::registerStatus(_MYMAPNOTIFYSENT);
					}
				}
//			}
//			return pnRedirect(pnModURL('pagesetter','googlemap','selectmap',array('mid'=>$obj['mid'])));
//		}
		return true;
}


function pagesetter_mymap_updatemarker($args) {
	$mkid = $args['mkid'];
	$marker = pnModAPIFunc('MyMap','user','getMarkers',array('id'=> $mkid));
	$text = $marker[0]['text'];
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
	$marker[0]['text'] = $text;
	$result = DBUtil::updateObject($marker[0], 'mymap_markers');
	return false;
}


?>