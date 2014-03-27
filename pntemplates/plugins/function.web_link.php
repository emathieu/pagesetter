<?php
/** 
 * Function:
 *
 * display the thumbnail of the main item of a mediashare album.
 * 
 *
*/
 
function smarty_function_web_link($args, &$smarty)
{
	if (!isset ($args['lid'])) {
		$smarty->trigger_error("*** web_links: missing parameter 'lid'", E_ERROR);
		return false;
	}

	$link = pnModAPIFunc('Web_Links', 'user', 'link', array ('lid' => $args['lid']));
	if ($link == false) {
		$smarty->trigger_error("*** web_links: 'lid'".$args['lid']." is not valid", E_ERROR);
		return false;
	}
	$return_url = "<a href=\"".$link['url']."\" target=\"_blank\">".$link['title']."</a>";

  	if (isset($args['assign']))
    	$smarty->assign($args['assign'], $return_url);
  	else
    	return $return_url;
}
?>
