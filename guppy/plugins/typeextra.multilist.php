<?php
// $Id: typeextra.multiList.php,v 1.0 2006/05/07 18:42:05 philipp Exp $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original author: Philipp Niethammer <webmaster@nochwer.de>
// ----------------------------------------------------------------------

/**
 * returns the HTML of extra type parameter configuration
 *
 * @author	Philipp Niethammer
 * @param	string		$args['typeData']	Configuration
 * @return	string		html
 */
function typeextra_multilist_render ($args) {
	
	// Fetch previous data
	$typeData = explode(':', $args['typeData']);
	list($lid) = $typeData;
	
	// Load MultiList-Langfile
	pnModLangLoad('pagesetter', 'multilist');
	
	if (!pnModAPILoad('pagesetter', 'admin'))
		 return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');
	
	// Fetch all lists	 
	$lists = pnModAPIFunc('pagesetter', 'admin', 'getLists');
	
	if ($pubTypes === false)
    	return pagesetterErrorAPIGet();
    	
    // Generate HTML for a <select> element
    
    $html = '<label for="typeextra_multilist_list">'. _PGMULTILIST_LIST_SELECT . '</label>:
    			<select id="typeextra_multilist_list" name="typeextra_multilist_list">'."\n";
    			
	if ($lid == '' && count($lists) > 0)
		$lid = $lists[0]['id'];
		
	foreach ($lists as $list) {
	
		$selected = '';
		if ($list['id'] == $lid)
			$selected = ' selected="selected"';
		
		$html .= "<option value=\"$list[id]\"$selected>" . pnVarPrepForDisplay($list['title']) . "</option>\n";
	}
	
	$html .= "</select>\n\n";
	
	// VERY IMPORTANT
  	// Implement a JavaScript function that reads the selected publication type ID
  	// and returns. The name of the function "typeextra_submit" is required by the
  	// surrounding code.
  	$html .= "<script>\n
				function typeextra_submit()\n
				{\n
				  var lid = document.getElementById('typeextra_multilist_list');\n
				  return lid.value\n
				}\n
			</script>\n";
	return $html;
}

?>
