<?php

/**
 * returns the HTML of extra type parameter configuration
 *
 * @author  Philipp Niethammer
 * @param   string      $args['typeData']   Configuration
 * @return  string      html
 */
function typeextra_categorylist_render ($args) {
    Loader::LoadClass("CategoryUtil");
    // Fetch previous data
    $typeData = explode(':', $args['typeData']);
    list($pcat) = $typeData;

    // Load MultiList-Langfile
    pnModLangLoad('pagesetter', 'categorylist');

    // Fetch all categories
    $allcats = CategoryUtil::getCategories('', '', 'id');

    $cats = array();
    foreach ($allcats as $cat) {
        if ($cat['parent_id'] != 0 && !isset($cats[$cat['parent_id']])) {
            $cats[$cat['parent_id']] = $allcats[$cat['parent_id']];
        }
    }

    // Generate HTML for a <select> element
    $html = '<label for="typeextra_categorylist_list">'. _PGCATEGORYLIST_LIST_SELECT . '</label>:
             <select id="typeextra_categorylist_list" name="typeextra_categorylist_list">'."\n";

    if ($pcat == '' && count($cats) > 0)
    $pcat = $lists[0]['id'];

    foreach ($cats as $cat) {

        $selected = '';
        if ($cat['id'] == $pid)
        $selected = ' selected="selected"';

        $html .= "<option value=\"$cat[id]\"$selected>" . DataUtil::formatForDisplay($cat['name']) . "</option>\n";
    }

    $html .= "</select>\n\n";

    $html .= 'Type: <select id="typeextra_categorylist_type" name="typeextra_categorylist_type">'."\n";
    $html .= '<option value="1">Dropdown</option>
              <option value="2">Multiselect</option>
              <option value="3">Checkboxlist</option>
              </select>'."\n";

    // VERY IMPORTANT
    // Implement a JavaScript function that reads the selected publication type ID
    // and returns. The name of the function "typeextra_submit" is required by the
    // surrounding code.
    $html .= "<script>\n
                function typeextra_submit()\n
                {\n
                  var pcat = document.getElementById('typeextra_categorylist_list');\n
                  var type = document.getElementById('typeextra_categorylist_type');\n
                  return pcat.value + ':' + type.value;\n
                }\n
            </script>\n";
    return $html;
}

