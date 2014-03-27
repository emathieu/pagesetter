<?php

/**
 * Smarty function to create a templated list of publications, related to other publications.
 *
 * Parameters:
 *   - tid: Pagesetter type ID used to specify which kind of publications to show.
 *   - filter: array of filter strings with the same syntax as used on a pagesetter URL. The array can be build
 *             using the pagesetter_createFilter plugin.
 *   - zoom, width and height map parameters
 *
 * The list template is applied to each and every selected publication, but this plugin does <em>not</em> use a
 * header/footer template.
 */
require_once("modules/pagesetter/pnmymap.php");

function smarty_function_pagesetter_relatedMapLink($args, &$smarty)
{
	if (!isset($args['tid']))
		return "Missing 'tid' argument in Smarty plugin 'pagesetter_relatedMap'";

	$tid 	= $args['tid'];
	$filter = isset($args['filter']) ? $args['filter'] : null;
	$zoom	= isset($args['zoom']) ? $args['zoom'] : 13;
	$width 	= isset($args['width']) ? $args['width'] : 640;
	$height = isset($args['height']) ? $args['height'] : 480;

	$output = pnModUrl ('pagesetter','mymap','display', array(
				'tid' 	 => $tid,
				'filter' => $filter,
				'width'	 => $width,
				'height' => $height,
				'zoom' 	 => $zoom));
	
	if (isset($args['assign']))
		$smarty->assign($args['assign'], $output);
	else
		return $output;
}
?>
