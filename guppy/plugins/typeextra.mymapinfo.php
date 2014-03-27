<?php

require_once("modules/pagesetter/pnmymap.php");

/**
 * returns the HTML of extra type parameter configuration
 *
 * @author  Philipp Niethammer
 * @param   string      $args['typeData']   Configuration
 * @return  string      html
 */
function typeextra_mymapinfo_render ($args) {
	global $MapInfoTypes;

	// Fetch previous data
	list($fid, $info) = explode(':',$args['typeData'],2);
	$tid = $args['tid'];

	if (!pnModAPILoad('pagesetter', 'admin'))
		return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');

	// Fetch all publication types
	$pubTypes = pnModAPIFunc('pagesetter', 'admin', 'getPublicationTypes');

	if ($pubTypes === false) 
		return pagesetterErrorAPIGet();

	// Get all mymap fields (I get them directly from the database)
	list ($dbconn) = pnDBGetConn();
	$pntable = pnDBGetTables();

	$pubFieldsTable  = $pntable['pagesetter_pubfields'];
	$pubFieldsColumn = &$pntable['pagesetter_pubfields_column'];

	$fields = array('id','title');
	$where = "$pubFieldsColumn[type] = 'mymap' AND $pubFieldsColumn[tid] = $tid";
	$pubFields = DBUtil::selectObjectArray('pagesetter_pubfields', $where, '',-1,-1,'',null,null,$fields);

	if ('' == $fid)
		$readonly = ' readonly="1"';
	else
		$readonly = '';

	$html = "<label for=\"typeextra_mymapinfo_fid\">" . _PGREL_FIELD_SELECT . "</label>: <select id=\"typeextra_mymapinfo_fid\" name=\"typeextra_mymapinfo_fid\"$readonly>\n";

	if ('' == $fid)
		$selected = ' selected="1"';
	else
		$selected = '';

	foreach ($pubFields as $field)
	{
		if ($field['id'] == $fid)
			$selected = ' selected="1"';
		else
			$selected = '';

		$html .= "<option value=\"$field[id]\"$selected>" . pnVarPrepForDisplay($field[title]) . "</option>\n";
	}
	$html .= "</select><br />\n";

	// Select info of input field
	$html .= "<label for=\"typeextra_mymapinfo_info\">" . _PGREL_STYLE_SELECT . "</label>: <select id=\"typeextra_mymapinfo_info\" name=\"typeextra_mymapinfo_info\">\n";
	foreach ($MapInfoTypes as $key => $infoType) {
		if ($key == $info)
			$selected = ' selected="1"';
		else
			$selected = '';
		$html .= "<option value=\"$key\"$selected>$infoType</option>\n";
	}
	$html .= "</select>\n";

	// VERY IMPORTANT
	// Implement a JavaScript function that reads the selected publication type ID
	// and returns. The name of the function "typeextra_submit" is required by the
	// surrounding code.
	$html .= "
	<script>
	function typeextra_submit()
	{
		var fid = document.getElementById('typeextra_mymapinfo_fid');
		var info = document.getElementById('typeextra_mymapinfo_info');		
		return fid.value + ':' + info.value;
	}
	</script>
	";
	return $html;
}

