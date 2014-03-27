<?php
/** 
 * Function:
 *
 * display the thumbnail of the main item of a mediashare album.
 * 
 *
*/
 
function smarty_function_mediashare_mainItemThumbnail($args, &$smarty)
{
	if (!isset ($args['album'])) {
		if (!isset ($args['albumId'])) {
			$smarty->trigger_error("*** mainItemThumbnail: missing parameter 'albumId'", E_ERROR);
			return false;
		}
		$album = pnModAPIFunc( 'mediashare', 'user', 'getAlbum', $args );
	} else 
		$album = $args['album'];

	if ($album == false) {
		$smarty->trigger_error("*** mainItemThumbnail: invalid album", E_ERROR);
		return false;
	}
    if ($album['mainMediaItem']['id'] != 0) {
		$mainItem = pnModAPIFunc( 'mediashare', 'user', 'getMediaItem',
	                          	array ('mediaId' => $album['mainMediaItem']['id']) );
		$mainItemThumbnail = '<a href="javascript:popupMediaViewer(\'index.php?module=Mediashare&amp;func=display&amp;mid='.$album['mainMediaItem']['id'].'\')"><img src="mediashare/'.$mainItem['thumbnailRef'].'" class="thumbnail"/></a>';
    } else {
    	$mainItemThumbnail = '';
    }
  	if (isset($args['assign']))
    	$smarty->assign($args['assign'], $mainItemThumbnail);
  	else
    	return $mainItemThumbnail;
}
?>
