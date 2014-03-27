<?php

// This function is used to render the admin interface of the extra type parameter
// for the "Web_Links" plugin.

function typeextra_web_links_render($args)
{
  	// Fetch previous data
	list($onlySelectedCategory, $selectedcategory, $singleDropDown) = explode(':',$args['typeData']);
  
  	if (!pnModAPILoad('pagesetter', 'admin'))
    	return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');

	$categories = pnModAPIFunc('Web_Links', 'user', 'categories', array ());

	$html .= "<label for=\"typeextra_weblinks_singledropdown\">" . _PG_WEBLINKS_SINGLE_DROPDOWN . "</label>: <input type=\"checkbox\" id=\"typeextra_weblinks_singledropdown\" name=\"typeextra_weblinks_singledropdown\" value=\"1\"" . ( $singleDropDown ? " checked=\"1\"" : "" ) . " /><br />\n";
  	$html .= "<label for=\"typeextra_weblinks_onlyselectedcat\">" . _PG_WEBLINKS_ONLY_SELECTED_CAT . "</label>: <input type=\"checkbox\" id=\"typeextra_weblinks_onlyselectedcat\" name=\"typeextra_weblinks_onlyselectedcat\" value=\"1\"" . ( $onlySelectedCategory ? " checked=\"1\"" : "" ) . " /><br />\n";
	$html .= "<label for=\"typeextra_weblinks_category\">" . _PG_WEBLINKS_SINGLE_DROPDOWN . "</label>: <select id=\"typeextra_weblinks_category\" typeextra_weblinks_category=\"category\">\n";
	foreach ($categories as $category) {	
		if ($category['cat_id'] == $selectedcategory ) {
			$html .= "<option value=\"".$category['cat_id']."\" selected=\"selected\">".$category['title']."</option>\n";
		} else {
			$html .= "<option value=\"".$category['cat_id']."\">".$category['title']."</option>\n";
		}
	}
	$html .= "</select>\n";

  	// VERY IMPORTANT
  	// Implement a JavaScript function that reads the selected publication type ID
  	// and returns. The name of the function "typeextra_submit" is required by the
  	// surrounding code.
  	$html .= "
<script type='text/javascript'>
function typeextra_submit()
{
	var flag = document.getElementById('typeextra_weblinks_onlyselectedcat');
    var onlySelectedCategory = (flag.checked) ? 1 : 0;
    var selectedcategory = document.getElementById('typeextra_weblinks_category').value;
    flag = document.getElementById('typeextra_weblinks_singledropdown');
    var singleDropDown = (flag.checked) ? 1 : 0;
	return onlySelectedCategory + ':' + selectedcategory + ':' + singleDropDown;
}
</script>
";
  return $html;
}

?>