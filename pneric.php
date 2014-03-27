<?php
// $Id: pnuser.php,v 1.119 2007/02/08 21:30:41 jornlind Exp $
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

require_once ("modules/pagesetter/common.php");

function pagesetter_eric_view($args) {
	return pagesetter_eric_main($args);
}

function pagesetter_eric_main($args) {
	$tid = pagesetterGetTID($args);
	if ($tid === false)
		$tid = 1;

	// Check access
	if (!pnSecAuthAction(0, 'pagesetter::', "$tid::", ACCESS_READ))
		return pagesetterErrorPage(__FILE__, __LINE__, _PGNOAUTH);

	// $topic = isset ($args['topic']) ? $args['topic'] : pnVarCleanFromInput('topic');
	// $language = isset ($args['lang']) ? $args['lang'] : pnVarCleanFromInput('lang');
	// //  $urlTemplate  = isset($args['tpl']) ? $args['tpl'] : pnVarCleanFromInput('tpl');
	// $page = isset ($args['page']) ? $args['page'] : pnVarCleanFromInput('page'); // Offset 1 (to avoid page=0 on the URL)
	// $noOfItems = isset ($args['pubcnt']) ? $args['pubcnt'] : pnVarCleanFromInput('pubcnt');
	// //  $orderBy      = isset($args['orderby']) ? $args['orderby'] : pnVarCleanFromInput('orderby');
	// $orderBy = "core.pid";
	// //  $enableHooks  = isset($args['enableHooks']) ? $args['enableHooks'] : true;

	// //  $filterStrSet = pagesetterGetFilters($args, $baseURLArgs);

	// $page = (isset ($page) ? $page -1 : 0);
	// $noOfItems = (isset ($noOfItems) ? $noOfItems : 1000);

	// if (!isset ($language))
		// $language = pnUserGetLang();

	// //  if (isset($urlTemplate))
	// //    $format = $urlTemplate;
	// //  else
	// //    $format = 'list';

	// if (!pnModAPILoad('pagesetter', 'user'))
		// return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter user API');

	// if (!pnModAPILoad('pagesetter', 'admin'))
		// return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');

    // if (!pnModAPILoad('mediashare', 'import'))
		// return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Mediashare import API');

	// // fetch the list of publications
	$pubList = pnModAPIFunc('pagesetter', 'user', 'getPubList', array (
		'tid' => $tid
		// 'topic' => $topic,
		// 'noOfItems' => $noOfItems,
		// 'language' => $language,
		// 'offsetPage' => $page,
		// 'filterSet' => "",
		// 'allowDefaultFilter' => true,
		// 'orderByStr' => $orderBy
	));

	if ($pubList === false)
		return pagesetterErrorAPIGet();
//print_r($pubList);
//exit;
	$output = "OK";

	// $string_mediashare = "/index.php?module=Mediashare&amp;func=view&amp;aid=";
	// $string_mediashare_sans_slash = "index.php?module=Mediashare&amp;func=view&amp;aid=";
	// $string_photoshare = "index.php?module=photoshare&func=showimages&fid=";
	// $string_oldphotoshare = "<a href=\"index.php?module=photoshare&func=showimages&fid=";
	// $string_oldphotoshare_avec_slash = "<a href=\"/index.php?module=photoshare&func=showimages&fid=";
	// $string_image = "index.php?module=photoshare&type=show&func=viewimage&iid=";
	// $string_image_media_localhost_8080 = "<a href=\"javascript:popupMediaViewer('http://localhost:8080/index.php?module=Mediashare&amp;func=display&amp;mid=";
	// $string_image_media_192 = "<a href=\"javascript:popupMediaViewer('http://192.168.0.100:8080/index.php?module=Mediashare&amp;func=display&amp;mid=";
	// $string_image_media = "<a href=\"javascript:popupMediaViewer('/index.php?module=Mediashare&amp;func=display&amp;mid=";
	// foreach ($pubList['publications'] as $pub) {
		// $pubdata = pnModAPIFunc('pagesetter', 'user', 'getPub', array (
			// 'tid' => $tid,
			// 'pid' => $pub['pid'],
			// 'getApprovalState' => true
		// ));

		// // Ignore non-existing publications
		// if (!($pubdata === false)) {
			// if ($pubdata['core_lastUpdated'] != null)
				// $pubdata['core_lastUpdated'] = date("Y-m-d H:i:s", $pubdata['core_lastUpdated']);
			// if ($pubdata['core_publishDate'] != null)
				// $pubdata['core_publishDate'] = date("Y-m-d H:i:s", $pubdata['core_publishDate']);
			// if ($pubdata['core_expireDate'] != null)
				// $pubdata['core_expireDate'] = date("Y-m-d H:i:s", $pubdata['core_expireDate']);
			// if ($pubdata['albumId'] === null) {
// //				if ($pubdata['photoshare'] != null) {
// //					if (strncmp($pubdata['photoshare'], $string_mediashare, 20) == 0) {
// //						$pubdata['albumId'] = substr($pubdata['photoshare'], strlen($string_mediashare));
// //						$result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //							'tid' => $tid,
// //							'id' => $pub['id'],
// //							'pubData' => $pubdata
// //						));
// //					}
// //					elseif (strncmp($pubdata['photoshare'], $string_mediashare_sans_slash, 20) == 0) {
// //						$pubdata['albumId'] = substr($pubdata['photoshare'], strlen($string_mediashare_sans_slash));
// //						$result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //							'tid' => $tid,
// //							'id' => $pub['id'],
// //							'pubData' => $pubdata
// //						));
// //					}
// //					elseif (strncmp($pubdata['photoshare'], $string_photoshare, 30) == 0) {
// //						$tempo = substr($pubdata['photoshare'], strlen($string_photoshare));
// //						$pubdata['albumId'] =
// //						    pnModAPIFunc('mediashare', 'import', 'GetPhotoshareAlbumRef', $tempo);
// //						$result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //							'tid' => $tid,
// //							'id' => $pub['id'],
// //							'pubData' => $pubdata
// //						));
// //					} else {
// //						$output .= $pub['pid'] . " | " . $pub['id']. " | photoshare:[" . $pubdata['photoshare'] . "]<br />\n";
// //					}
// //				} elseif ($pubdata['oldphotoshare'] != null) {
// //					if (strncmp($pubdata['oldphotoshare'], $string_oldphotoshare, 20) == 0) {
// //						$tempo = substr($pubdata['oldphotoshare'], strlen($string_oldphotoshare));
// //						$tempo = substr($tempo, 0, strpos($tempo,"\""));
// //						$pubdata['albumId'] = pnModAPIFunc('mediashare', 'import', 'GetPhotoshareAlbumRef', $tempo);
// //						$result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //							'tid' => $tid,
// //							'id' => $pub['id'],
// //							'pubData' => $pubdata
// //						));
// ////						$output .= $pub['pid'] . " | " . $pub['id'] . " | " . $pubdata['image'] . " | " . $pubdata['image_media'] . " | " . $pubdata['oldphotoshare'] . " | " . $pubdata['photoshare'] . " | " . $pubdata['albumId'] . " | " . $tempo . "<br />\n";
// //					} elseif (strncmp($pubdata['oldphotoshare'], $string_oldphotoshare_avec_slash, 20) == 0) {
// //						$tempo = substr($pubdata['oldphotoshare'], strlen($string_oldphotoshare_avec_slash));
// //						$tempo = substr($tempo, 0, strpos($tempo,"\""));
// //						$pubdata['albumId'] = pnModAPIFunc('mediashare', 'import', 'GetPhotoshareAlbumRef', $tempo);
// //						$result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //							'tid' => $tid,
// //							'id' => $pub['id'],
// //							'pubData' => $pubdata
// //						));
// ////						$output .= $pub['pid'] . " | " . $pub['id'] . " | " . $pubdata['image'] . " | " . $pubdata['image_media'] . " | " . $pubdata['oldphotoshare'] . " | " . $pubdata['photoshare'] . " | " . $pubdata['albumId'] . " | " . $tempo . "<br />\n";
// //					} else {
// //						$output .= $pub['pid'] . " | " . $pub['id'] . " | oldphotoshare:[" . $pubdata['oldphotoshare'] . "]<br />\n";
// //					}
// //				} elseif ($pubdata['image'] != null) {
// //					if (strncmp($pubdata['image'], $string_image, 20) == 0) {
// //						$tempo = substr($pubdata['image'], strlen($string_image));
// //						$converted = pnModAPIFunc('mediashare', 'import', 'GetPhotoshareImageRef', $tempo);
// //						$mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
// //						if (isset($mediaalbum['parentAlbumId'])) {
// //  							$pubdata['albumId'] = $mediaalbum['parentAlbumId'];
// //						    $result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //								'tid' => $tid,
// //								'id' => $pub['id'],
// //								'pubData' => $pubdata
// //							));
// //						} else {
// //   				        	$output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | albumId:[" . $pubdata['albumId'] . "]<br />";
// //						}
// //					} else {
// //   				        $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "]<br />";
// //					}
// //				} elseif ($pubdata['image_media'] != null) {
// //					if (strncmp($pubdata['image_media'], $string_image_media_localhost_8080, 40) == 0) {
// //						$tempo = substr($pubdata['image_media'], strlen($string_image_media_localhost_8080));
// //						$converted = substr($tempo, 0, strpos($tempo,"'"));
// //						$mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
// //						if (isset($mediaalbum['parentAlbumId'])) {
// //  							$pubdata['albumId'] = $mediaalbum['parentAlbumId'];
// //						    $result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //								'tid' => $tid,
// //								'id' => $pub['id'],
// //								'pubData' => $pubdata
// //							));
// //						} else {
// //   				        	$output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
// //						}
// //					} elseif (strncmp($pubdata['image_media'], $string_image_media, 20) == 0) {
// //						$tempo = substr($pubdata['image_media'], strlen($string_image_media));
// //						$converted = substr($tempo, 0, strpos($tempo,"'"));
// //						$mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
// //						if (isset($mediaalbum['parentAlbumId'])) {
// //  							$pubdata['albumId'] = $mediaalbum['parentAlbumId'];
// //						    $result = pnModAPIFunc('pagesetter', 'edit', 'updatePub', array (
// //								'tid' => $tid,
// //								'id' => $pub['id'],
// //								'pubData' => $pubdata
// //							));
// //						} else {
// //   				        	$output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
// //						}
// //					} else {
// //   				        $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "]<br />";
// //					}
// //				} else {
// //					$output .= $pub['pid'] . " | " . $pub['id'] . " | image_media:[" . $pubdata['image_media'] . "]<br />";
// //			    }
			// } // ($pubdata['albumId'] === null)
			// if ($pubdata['image'] != null) {
				// if (strncmp($pubdata['image'], $string_image, 20) == 0) {
					// $tempo = substr($pubdata['image'], strlen($string_image));
 				  	// $converted = pnModAPIFunc('mediashare', 'import', 'GetPhotoshareImageRef', $tempo);
					// $mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
					// if (isset($mediaalbum['parentAlbumId'])) {
// //						$result = pnModAPIFunc('mediashare', 'edit', 'setMainItem', array (
// //									'albumId' => $mediaalbum['parentAlbumId'],
// //									'mediaId' => $converted,
// //							));
// //   				        $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted. "]<br />";
					// } else {
   				        // $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | albumId:[" . $pubdata['albumId'] . "]<br />";
					// }
				// } else {
   					// $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "]<br />";
				// }
			// } elseif ($pubdata['image_media'] != null) {
					// if (strncmp($pubdata['image_media'], $string_image_media_localhost_8080, 50) == 0) {
						// $tempo = substr($pubdata['image_media'], strlen($string_image_media_localhost_8080));
						// $converted = substr($tempo, 0, strpos($tempo,"'"));
						// $mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
						// if (isset($mediaalbum['parentAlbumId'])) {
// //							$result = pnModAPIFunc('mediashare', 'edit', 'setMainItem', array (
// //									'albumId' => $mediaalbum['parentAlbumId'],
// //									'mediaId' => $converted,
// //							));
// //  				        	$output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// } else {
   				        	// $output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// }
					// } elseif (strncmp($pubdata['image_media'], $string_image_media_192, 50) == 0) {
						// $tempo = substr($pubdata['image_media'], strlen($string_image_media_192));
						// $converted = substr($tempo, 0, strpos($tempo,"'"));
						// $mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
						// if (isset($mediaalbum['parentAlbumId'])) {
// //							$result = pnModAPIFunc('mediashare', 'edit', 'setMainItem', array (
// //									'albumId' => $mediaalbum['parentAlbumId'],
// //									'mediaId' => $converted,
// //							));
// //   				        	$output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// } else {
  				        	// $output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// }
					// } elseif (strncmp($pubdata['image_media'], $string_image_media, 20) == 0) {
						// $tempo = substr($pubdata['image_media'], strlen($string_image_media));
						// $converted = substr($tempo, 0, strpos($tempo,"'"));
						// $mediaalbum = pnModAPIFunc('mediashare', 'user', 'getMediaItem', array ('mediaId' => $converted));
						// if (isset($mediaalbum['parentAlbumId'])) {
// //							$result = pnModAPIFunc('mediashare', 'edit', 'setMainItem', array (
// //									'albumId' => $mediaalbum['parentAlbumId'],
// //									'mediaId' => $converted,
// //							));
// //   				        	$output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// } else {
   				        	// $output .= $pub['pid'] . " | " . $pub['id'] . "] | image_media:[" . $pubdata['image_media'] . "] | tempo:[" . $tempo . "] | converted:[" . $converted."] | mediaalbum:[" . $mediaalbum['parentAlbumId'] . "]<br />";
						// }
					// } else {
   				        // $output .= $pub['pid'] . " | " . $pub['id'] . " | image:[" . $pubdata['image'] . "] | image_media:[" . $pubdata['image_media'] . "]<br />";
					// }
				
			// } else {
				// $output .= $pub['pid'] . " | " . $pub['id'] . " | " . $pubdata['image'] . " | " . $pubdata['image_media'] . " | " . $pubdata['albumId'] . "<br />";
			// }
		// }
	// }

	return $output;
}

?>