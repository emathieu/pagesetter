<?php
  // Thanks to Zippo-71 - who gives the right way :)
  //
  // Parameters:
  // @field : Name of Field to sort from - This is MANDANTORY
  //
  // @divclass (optional) : Class for the generated div, where the Links get in - to more flexibility ;)
  // @tpl (optional : Choose Template
  // @filter2 (optional): Extra filters (AND-Filter)
  // @topic (optional): Postnuke-Topic-ID
  //
  // Example
  // <!--[pagesetter_alphalist field=title divclass=alist tpl=full]-->
  // 
  // HiloPe 2005

function smarty_function_pagesetter_alphalist($args, &$smarty)
{

  $smarty->caching = 0;
  if (!isset($args['field']))
    return "Missing 'field' argument in Smarty plugin 'pagesetter_alphalist'";

  $field       = $args['field'];
  $divclass    = $args['divclass'];
  $template     = $args['tpl'];
  $filter2    = $args['filter2'];
  $topic    = $args['topic'];

  if (isset($args['tid']))
  {
    $tid = $args['tid'];
  }
  else
  {
    $core = $smarty->get_template_vars('core');
    $tid = $core['tid'];
  }

  $parameters = array();
  $parameters['tid'] = $tid;
  if (!empty($template)) {
    $parameters['tpl'] = $template;
  }
  if (!empty($topic)) {
    $parameters['topic'] = $topic;
  }

  $i = 0;
  $j = 1;
  // normal Array of 26 Letters
  $alpha = array ("A","B","C","D","E","F","G","H","I","J","K","L","M",
			      "N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

  // Start DIV
  $html = "<div" . (isset($divclass) ? " class=\"$divclass\">" : "") . "\n";

  if (!empty($filter2)) {
    $parameters['filter'] = $filter2;
  }

  $url = pnModUrl('pagesetter', 'user', '', $parameters);
  $html .= "<a href=\"$url\">"._ALL."</a> | \n";

  while ($j <= 25) {
  	    $parameters['filter'] = "$field^gt^$alpha[$i],$field^lt^$alpha[$j]";
		if (!empty($filter2)) {
			$parameters['filter'] .= ",".$filter2;
		}
        $url = pnModUrl('pagesetter', 'user', '', $parameters);
        $html .= "<a href=\"$url\">&nbsp;$alpha[$i]&nbsp;</a> | ";

        $i++;
        $j++;
  }

  // Last Letter in List without lt-filter
  $parameters['filter'] = "$field^gt^$alpha[25]";
  if (!empty($filter2)) {
	  $parameters['filter'] .= ",".$filter2;
  }

  $url = pnModUrl('pagesetter', 'user', '', $parameters);
  $html .= "<a href=\"$url\">&nbsp;$alpha[25]&nbsp;</a>\n";

  // End DIV
  $html .= "</div>\n";

  return $html;
}
?>
