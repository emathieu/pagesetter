<?php
/** 
 * Function:
 *
 * display a link to a marker on a MyMap map
 * 
 *
*/
 
function smarty_function_mymap_link($args, &$smarty)
{
	if (!isset ($args['mapdata'])) {
		$smarty->trigger_error("*** mymap link: missing parameter 'mapdata'", E_ERROR);
		return false;
	}

	list ($mid, $id) = explode("|", $args['mapdata'], 2);
	$param = array (mid => $mid);
	if (isset ($id))
		$param['pid'] = $id;
    $url = pnModURL('MyMap', 'user', 'display', $param);

  	if (isset($args['assign']))
    	$smarty->assign($args['assign'], $url);
  	else
    	return $url;
}
?>
