<?php

/**
 * returns the HTML of extra type parameter configuration
 *
 * @author  Philipp Niethammer
 * @param   string      $args['typeData']   Configuration
 * @return  string      html
 */
function typeextra_mymap_render ($args) {

    // Fetch previous data
    $dataType = $args['typeData'];

    // Load MultiList-Langfile
    pnModLangLoad('pagesetter', 'mymap');

    $types = array(
		1 	=> "Map | Marker",
		2	=> "Lat | Lng");

    // Generate HTML for a <select> element
    $html = '<label for="typeextra_mymap_list">'. _PGmymap_LIST_SELECT . '</label>:
             <select id="typeextra_mymap_list" name="typeextra_mymap_list">'."\n";

    if ($dataType == '' && count($types) > 0)
		$dataType = 1;

    foreach ($types as $key => $cat) {
        $selected = '';
        if ($key == $dataType)
        $selected = ' selected="selected"';
        $html .= "<option value=\"$key\"$selected>" . DataUtil::formatForDisplay($cat) . "</option>\n";
    }
    $html .= "</select>\n\n";

    // VERY IMPORTANT
    // Implement a JavaScript function that reads the selected publication type ID
    // and returns. The name of the function "typeextra_submit" is required by the
    // surrounding code.
    $html .= "<script>\n
                function typeextra_submit()\n
                {\n
                  var dataType = document.getElementById('typeextra_mymap_list');\n
                  return dataType.value;\n
                }\n
            </script>\n";
    return $html;
}

