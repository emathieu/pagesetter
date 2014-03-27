<?php

// Created by Eric Mathieu
// From Mymap module
// =======================================================================

require_once 'modules/MyMap/pnuser.php';
require_once("modules/pagesetter/common.php");

global $MapInfoTypes;
$MapInfoTypes = array("_PG_INFO_COUNTRY_", "_PG_INFO_COUNTRY_CODE", "_PG_INFO_STATE", "_PG_INFO_STATE_CODE", "_PG_INFO_CITY");

function pagesetter_mymap_decode($value) {
	$mkdata = explode("|", $value);
	if (is_numeric($mkdata[0])) {
		$mkinfo = array('mid' => $mkdata[0], 'mkid' => $mkdata[1]);
	} else {
		$mkinfo = array();
		foreach ($mkdata as $item) {
			list($param, $val) = explode(":", $item);
			if ($param !== '')
				$mkinfo[$param] = $val;
		}
	}
	return $mkinfo;
}

function pagesetter_mymap_reverse($coords, $infoType) {
	$returnValue = NULL;
	$ch = curl_init();
	$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$coords['lat']},{$coords['lng']}&sensor=false";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	$json = json_decode($result, TRUE);
	if (isset($json['results'])) {
		foreach ($json['results'] as $result) {
			foreach ($result['address_components'] as $address_component) {
				$component_type = $address_component['types'][0];
				if (($component_type == 'country') && ($infoType == 0)) {
					$returnValue = $address_component['long_name'];
				} elseif (($component_type == 'country') && ($infoType == 1)) {
					$returnValue = $address_component['short_name'];
				} elseif (($component_type == 'administrative_area_level_1') && ($infoType == 2)) {
					$returnValue = $address_component['long_name'];
				} elseif (($component_type == 'administrative_area_level_1') && ($infoType == 3)) {
					$returnValue = $address_component['short_name'];
				} elseif (($component_type == 'locality') && ($infoType == 4)) {
					$returnValue = $address_component['long_name'];
				} 
			}
		}
	}
	return $returnValue;
}

// function pagesetter_mymap_test($args) {
// echo "<pre>";
	// $mkinfo = pagesetter_mymap_decode("lat:36.935841872801696|lng:-101.07421875");
	// if (isset($mkinfo['lat'])) {
		// $coords = array('lat' => $mkinfo['lat'],'lng' => $mkinfo['lng']);
	// } else {
		// $marker = pnModAPIFunc('MyMap','user','getMarkers',array('id' => $mkinfo['mkid']));
		// $coords = array(
				// 'lat'	=> $marker[0]['lat'],
				// 'lng'	=> $marker[0]['lng']);
	// }
	// $info = pagesetter_mymap_reverse($coords, 0);
// print_r($info);
	// $info = pagesetter_mymap_reverse($coords, 1);
// print_r($info);
	// $info = pagesetter_mymap_reverse($coords, 2);
// print_r($info);
	// $info = pagesetter_mymap_reverse($coords, 3);
// print_r($info);
	// $info = pagesetter_mymap_reverse($coords, 4);
// print_r($info);
// echo "</pre>";
	// return true;
// }

function pagesetter_mymap_getMarkers($args) {

	if (!isset($args['tid'])  ||  $args['tid']=='')
		return pagesetterErrorApi(__FILE__, __LINE__, "Missing argument 'tid' in 'pagesetter_mymap_getMarkers'", false);

	$tid 	  = $args['tid'];
	$pnRender = pnRender::getInstance('pagesetter');    
	$pnRender->expose_template = false;
	$template = (isset($args['template']) ? $args['template'] :  pagesetterSmartyGetTemplateFilename($pnRender, $tid, 'mymap_texte', $expectedName));

	$pubTypeInfo = pnModAPIFunc('pagesetter','admin','getPubTypeInfo', array('tid' => $tid));
	$fieldname = '';
	$dataType = 1;
	foreach ($pubTypeInfo['fields'] as $field) {
		if ($field['type'] === 'mymap') {
			$fieldname = $field['name'];
			$dataType = $field['typeData'];
			break;
		}
	}

	$coords = array();
	$index = array();
	if ($fieldname !== '') {

		if (isset($args['filterSet']))
			$filterSet = array($args['filterSet']);
		else
			$filterSet = null;

		$pubs = pnModAPIFunc('pagesetter','user','getPubList', array(
					'tid' 		=> $tid,
					'filterSet' => $filterSet,
					'noOfItems' => 999));

		foreach ( $pubs['publications'] as $pub ) {
			$pubdata = pnModAPIFunc('pagesetter','user','getPub', array(
					'tid' 	=> $tid,
					'pid'	=> $pub['pid']));
			if ($pubdata[$fieldname] !== ''){
				$mkinfo = pagesetter_mymap_decode($pubdata[$fieldname]);
				if ($dataType === 1) {
					$mkid = $mkinfo['mkid'];
					$marker = pnModAPIFunc('MyMap','user','getMarkers',array('id' => $mkid));
					$mk = $marker[0];
					$pnRender->assign('pub', $pubdata);
					$texte = $pnRender->fetch($template);
					if (!isset($index[$mkid])) {
						$coords[] = array(
							'lat'	=> (float)$mk['lat'],
							'lng'	=> (float)$mk['lng'],
							'title'	=> $mk['title'],
							'text'	=> $texte
						);
						end($coords);
						$index[$mkid] = key($coords);
					} else {
						$coords[$index[$mkid]]['text'] .= "<br />".$texte;
					}
				} else {
					$pnRender->assign('pub', $pubdata);
					$texte = $pnRender->fetch($template);
					$coords[] = array(
						'lat'	=> (float)$mkinfo['lat'],
						'lng'	=> (float)$mkinfo['lng'],
						'title'	=> $pub['title'],
						'text'	=> $texte
					);
				}
			}
		}
	}
	return $coords;
}

function pagesetter_mymap_selectmap($args) {

	$rmid = Formutil::getPassedValue('mid');
	$targetID = Formutil::getPassedValue('targetID');
	$dataType = Formutil::getPassedValue('dataType');
	$tid = Formutil::getPassedValue('tid');
	
	if ($tid === '')
		return pagesetterErrorApi(__FILE__, __LINE__, "Missing argument 'tid' in 'pagesetter_mymap_selectmap'", false);
		
	$update = 0;

	if ($dataType == 1) {
		list($mid, $mkid) = explode("|", $rmid);
		// Get the map that should be displayed
		if (!isset ($mid) || ($mid <= 0)) {
			$mid = 1;
		}
		// Security check 
		if (!SecurityUtil :: checkPermission('MyMap::', '$mid::', ACCESS_READ))
			return LogUtil :: registerPermissionError();

		$map 		= pnModAPIFunc('MyMap', 'user', 'getMaps', array ('id' => $mid));
		$maps 		= pnModAPIFunc('MyMap', 'user', 'getMaps');
		$markers 	= pnModAPIFunc('MyMap', 'user', 'getMarkers', array ('mid' => $mid));
		$center 	= pnModAPIFunc('MyMap', 'user', 'getCenter', $markers);
	} else {
		$mkinfo = pagesetter_mymap_decode($rmid);
		$map = array(
				'centerlat'		=> 0.0,
				'centerlng'		=> 0.0,
				'id'			=> rand(1,999999999),
				'maptype'		=> 'HYBRID',	// HYBRID, SATELLITE or NORMAL
				'width'			=> 640,			// width in pixels
				'height'		=> 480,			// height in pixels
				'zoomfactor' 	=> 20			// zoomfactor - 1 is closest
			);
		$maps = array();
		$markers = pagesetter_mymap_getMarkers (array('tid' => $tid));
		if (isset($mkinfo['lat'])) {
			$center = array ('lat' => $mkinfo ['lat'], 'lng' => $mkinfo ['lng']);
			$update = 1;
		} else {
			$center = pnModAPIFunc('MyMap', 'user', 'getCenter', $markers);
		}
	}
	$map['centerlat'] = str_replace(',','.',$center['lat']);
	$map['centerlng'] = str_replace(',','.',$center['lng']);

	// // We need some javascript
	// pnModAPIFunc('MyMap', 'user', 'addMapJS');

	// Create output object
	$render = pnRender::getInstance('pagesetter');
	$render->assign('map', $map);
	$render->assign('lat', $map['centerlat']);
	$render->assign('lng', $map['centerlng']);
	$render->assign('maps', $maps);
	$uid = pnUserGetVar('uid');
	$render->assign('uid', $uid);
	$render->assign('targetID', $targetID);
	$render->assign('dataType', $dataType);
	//$render->assign('hook', '1');
	$render->assign('clickzoom', '1');
	// $render->assign('uname', pnUserGetVar('uname', $map['uid']));
	$render->assign('markers', $markers);
	$render->assign('provider', pnModGetVar('MyMap', 'provider'));
	$render->assign('googlemapApiKey', pnModGetVar('MyMap', 'key_google'));
	$render->assign('onthefly',1);
	$render->assign('update',$update);
	// $render->assign('themeCssURL', ThemeUtil :: getModuleStylesheet('MyMap'));
	if (pnModGetVar('MyMap', 'map_overview') == 1)
		$map_overview = 'true';
	else
		$map_overview = 'false';
	$render->assign('map_overview', $map_overview);

	$render->display('mymap_select.html');

	return true;
}


function pagesetterDisplayMap($args) {

	if (!isset($args['tid'])  ||  $args['tid']=='')
		return pagesetterErrorApi(__FILE__, __LINE__, "Missing argument 'tid' in 'pagesetterDisplayMap'", false);

	$tid        = $args['tid'];
	$filter     = isset($args['filter']) ? $args['filter'] : null; // Field names in API notation!
	$filterSet  = isset($args['filterSet']) ? $args['filterSet'] : null; // Field names in user notation
	$zoomfactor = isset($args['zoom']) ? $args['zoom'] : 13;
	$width 		= isset($args['width']) ? $args['width'] : 640;
	$height 	= isset($args['height']) ? $args['height'] : 480;

	$markers = pagesetter_mymap_getMarkers (array(
				'tid' 		=> $tid,
				'filter' 	=> $filter,
				'filterSet' => $filterSet));

	// set some data for the non existent map
	$center = pnModAPIFunc('MyMap','user','getCenter',$markers);
	$map = array(
			'centerlat'		=> $center['lat'],
			'centerlng'		=> $center['lng'],
			'id'			=> rand(1,999999999),
			'optionaltable'	=> 0,
			'width'			=> $width,
			'height'		=> $height,
			'zoomfactor'	=> $zoomfactor,
			'maptype'		=> HYBRID);

	// show the whole world if there is no marker
	if ($center['error'] == 1) $map['zoomfactor'] = 13;
	
	// We need some javascript and language files
	pnModAPIFunc('MyMap','user','addMapJS');
	pnModLangLoad('MyMap');
	// Create output object
	$render = pnRender::getInstance('pagesetter');
	$render->assign('map',$map);
	$render->assign('clickzoom','1');
	$render->assign('provider',	pnModGetVar('MyMap','provider'));
	if (!($center['error'] == 1)) $render->assign('markers', $markers);
	if (pnModGetVar('MyMap','map_overview') == 1) $map_overview = 'true';
	else $map_overview = 'false';
	$render->assign('map_overview',$map_overview);

  	if (isset($lat) && isset($lng) && (strlen($lat) > 0) && (strlen($lng) > 0)) {
  	  	// this part is needed to let mymap know how the input fields are 
		// named where the value should be inserted into
	    $render->assign('lat',	$lat);
	    $render->assign('lng',	$lng);
	}

	// we should add our scripts and stylesheets..
	pnModAPIFunc('MyMap','user','addMapJS');

	$render->assign('onthefly',1);

	return $render->fetch('mymap_user_display_map.htm');

}


function pagesetter_mymap_display() {

	$tid = Formutil::getPassedValue('tid');
    $filter = Formutil::getPassedValue('filter');
    $zoom = Formutil::getPassedValue('zoom');
    $width = Formutil::getPassedValue('width');
    $height = Formutil::getPassedValue('height');

	if ($tid === '')
		return pagesetterErrorApi(__FILE__, __LINE__, "Missing argument 'tid' in 'pagesetter_mymap_getMarkers'", false);

	return pagesetterDisplayMap(array(
				'tid' 		=> $tid,
				'filterSet' => $filter,
				'width'		=> $width,
				'height'	=> $height,
				'zoom' 		=> $zoom));
}

function pagesetter_mymap_migrate($args) {
echo "Desactivé par sécurité";
exit;
	pnModAPILoad ('pagesetter', 'relations');
	echo "<pre>";
	$markers = pnModAPIFunc('MyMap','user','getMarkers',array());
    foreach ($markers as $marker) {
	print_r($marker);
		$newpub =  pnModAPIFunc( 'pagesetter',	'edit',	'createPub', Array(
						'tid' => 10,
						'pubData' => Array(
								'core_author' 			=> "Eric Mathieu",
								'core_creatorID' 		=> 5,
								'core_creator' 			=> "eric",
								'core_approvalState' 	=> "approved",
								'core_approvalTitle' 	=> null,
								'core_topic' 			=> -1,
								'core_showInList' 		=> 1,
								'core_showInMenu' 		=> 1,
								'core_online' 			=> 1,
								'core_revision' 		=> 0,
								'core_language' 		=> x_all,
								'core_publishDate' 		=> date("Y-m-d H:i:s"),
								'core_expireDate' 		=> null,
								'title' 				=> $marker['title'],
								'mymap' 				=> "lat:".$marker['lat']."|lng:".$marker['lng'],
								'articles' 				=> null,
								'map' 					=> null)));

		$result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
						'tid' 			=> 12,
                        'pid' 			=> $newpub['pid'], 
                        'fieldId' 		=> 115, 
                        'targetTid' 	=> 11, 
                        'targetPids' 	=> array($marker['mid']), 
                        'targetField'	=> 114));
		// Pour supprimer la relation
		// $result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
						// 'tid' 			=> 11,
                        // 'pid' 			=> $marker['mid'], 
                        // 'fieldId' 		=> 114, 
                        // 'targetTid' 	=> 12, 
                        // 'targetPids' 	=> array(), 
                        // 'targetField'	=> 115));

		$filter[0] = "mymap:eq:".$marker['mid']."|".$marker['id'];
		$pubList =  pnModAPIFunc( 'pagesetter',	'user',	'getPubList', array(
						'tid'        => 1,
						'noOfItems'  => 1000,
						'filterSet'  => $filter));
		$targetPids = array();
		foreach ($pubList['publications'] as $pub) {
			print_r($pub);
			$targetPids[] = $pub['pid'];
			// Pour supprimer la relation
			// $result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
								// 'tid' 			=> 1,
								// 'pid' 			=> $pub['pid'], 
								// 'fieldId' 		=> 112, 
								// 'targetTid' 	=> 10, 
								// 'targetPids' 	=> array(), 
								// 'targetField'	=> 111));
		}
		$result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
							'tid' 			=> 10,
							'pid' 			=> $newpub['pid'], 
							'fieldId' 		=> 111, 
							'targetTid' 	=> 1, 
							'targetPids' 	=> $targetPids, 
							'targetField'	=> 112));

		$pubList =  pnModAPIFunc( 'pagesetter',	'user',	'getPubList', array(
						'tid'        => 4,
						'noOfItems'  => 1000,
						'filterSet'  => $filter));

		$targetPids = array();
		foreach ($pubList['publications'] as $pub) {
			print_r($pub);
			$targetPids[] = $pub['pid'];
			// Pour supprimer la relation
			// $result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
								// 'tid' 			=> 4,
								// 'pid' 			=> $pub['pid'], 
								// 'fieldId' 		=> 116, 
								// 'targetTid' 	=> 10, 
								// 'targetPids' 	=> array(), 
								// 'targetField'	=> 117));
		}
		$result = pnModAPIFunc ('pagesetter', 'relations', 'setRelations', array (
							'tid' 			=> 10,
							'pid' 			=> $newpub['pid'], 
							'fieldId' 		=> 117, 
							'targetTid' 	=> 4, 
							'targetPids' 	=> $targetPids, 
							'targetField'	=> 116));
	}
	echo "</pre>";
	return true;
}
?>