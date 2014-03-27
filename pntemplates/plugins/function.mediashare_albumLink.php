<?php
/** 
 * Function:
 *
 * display the thumbnail of the main item of a mediashare album.
 * 
 *
*/
 
function smarty_function_mediashare_albumLink($args, &$smarty)
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

	$albumId = $args['album']['id'];

    $albumUrl = pnModURL('mediashare', 'user', 'view', array(aid => $albumId));
	if (isset ($args['mode']))
		$mode = $args['mode'];
	else
		$mode = 'html';

    if ($mode == 'html') {
    	$title = $args['album']['title'];
        $albumUrl = '<a href="'.$albumUrl.'">'.$title.'</a>';
    }

  	if (isset($args['assign']))
    	$smarty->assign($args['assign'], $albumUrl);
  	else
    	return $albumUrl;
}
?>
