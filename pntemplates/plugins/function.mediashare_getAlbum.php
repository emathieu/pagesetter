<?php
/** 
 * Function:
 *
 * reads and returns a mediashare album.
 * 
 *
*/
 
function smarty_function_mediashare_getAlbum($args, &$smarty)
{
	if (!isset ($args['albumId'])) {
		$smarty->trigger_error("*** getAlbum: missing parameter 'albumId'", E_ERROR);
		return false;
	}
	$album = pnModAPIFunc( 'mediashare', 'user', 'getAlbum', $args );
	if ($album == false)
		return false;
	
  	if (isset($args['assign']))
    	$smarty->assign($args['assign'], $album);
  	else
    	return $album;
}
?>
