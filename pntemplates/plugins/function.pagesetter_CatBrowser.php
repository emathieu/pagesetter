<?php


/**
 * Smarty function to create a formatted list of categories within a publication type for easy browsing of it's publications
 *
 * Parameters:
 *   - tid: Pagesetter type ID used to specify which kind of publications to show. [Default: TID of current item]
 *   - field: the name of the field that contains the subject list. [Required]
 *   - listClass: CSS Class name for the formatting of the list. [Optional]
 *   - tpl: Pagesetter Template for the target page. [Optional]
 *   - showcount: Set to "yes" to have it show how many total publications are in the current category as well as
 *        it's sub categories.[Default: No]
 *   - trim: Set to "yes" to have it trim empty categories from the list. [Default: No]
 *   - numform: the format of how to lay out the count of items.  Use the placeholder %%NOM%% for where the number
 *        should be.  [Default: " (%%NOM%%)"]
 *   - oneform: the format of how to lay out the count of items when there is only one publication in the category.
 *        Use the placeholder %%NOM%% for where the number should be.  [Default: The value of numform]
 *   - noneform: the format of how to lay out the count of items when there are no publications in the category.
 *        Use the placeholder %%NOM%% for where the number should be.  [Default: The value of numform]
 *
 * Examples:
 *   <!--[pagesetter_CatBrowser field="cat" listClass="kb1" showcount="yes" trim="yes"]-->
 *      This will use the category fields named "cat" show the number in each category and trim the empty categories.
 *
 *   <!--[pagesetter_CatBrowser field="cat" listClass="kb1" showcount="yes" trim="yes" numform=" There are %%NOM%% items." oneform=" There is %%NOM%% item"]-->
 *      Same as before, but with the format of both single and multiple item listings declared.
 *
 * This Plugin requires two files.  The createFilter file that is distributed with Pagesetter as well as the CountPubs
 *      filter that is distributed along with this plugin.
 *
 */

function smarty_function_pagesetter_CatBrowser($args, & $smarty) {
    $smarty->caching = 0; // Nice for debugging ...
    if (!isset ($args['field']))
    return "Missing 'field' argument in Smarty plugin 'pagesetter_CatBrowser'";

    $field = $args['field'];
    $listClass = $args['listClass'];
    $level = (empty ($args['level']) ? 1000 : $args['level']);
    $topValue = $args['topValue'];
    $template = $args['tpl'];

    $found = true;
    $index = 1;

    //new filter
    $filter = pnVarCleanFromInput('filter');

    if (isset ($args['tid'])) {
        $tid = $args['tid'];
    } else {
        $core = $smarty->get_template_vars('core');
        $tid = $core['tid'];
    }
    if (isset ($args['showcount'])) {
        $showcount = $args['showcount'];
    } else {
        $showcount = "no";
    }
    if (isset ($args['trim'])) {
        $trim = $args['trim'];
    } else {
        $trim = "no";
    }
    if (isset ($args['numform'])) {
        $numform = $args['numform'];
    } else {
        $numform = " (%%NOM%%)";
    }
    if (isset ($args['oneform'])) {
        $oneform = $args['oneform'];
    } else {
        $oneform = $numform;
    }
    if (isset ($args['noneform'])) {
        $noneform = $args['noneform'];
    } else {
        $noneform = $numform;
    }

    if (!pnModAPILoad('pagesetter', 'admin'))
    return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');

    $pubInfo = pnModAPIFunc('pagesetter',
                            'admin',
                            'getPubTypeInfo',
    compact('tid'));
    $fieldIndex = isset($pubInfo['fieldIndex'][$field]) ? $pubInfo['fieldIndex'][$field] : false;
    if ($fieldIndex === FALSE)
    $smarty->trigger_error("Fieldname '$fieldname' does not exists");

    $fieldInfo = $pubInfo['fields'][$fieldIndex];

    if ($fieldInfo['type'] == 'multilist')
    {
        $Ltype = 'multilist';
        $listID = $fieldInfo['typeData'];
    }
    else
    {
        $Ltype = 'list';
        $listID = pnModAPIFunc('pagesetter', 'admin', 'getListIDByFieldName', array ('tid' => $tid, 'field' => $field));
    }

    $listInfo = pnModAPIFunc('pagesetter', 'admin', 'getList', array ('lid' => $listID, 'topListValueID' => $topValue));


    // build the parameters for the URL.
    // these are the same for every item.
    $url_parameters = array ();
    $url_parameters['tid'] = $tid;
    if (!empty ($template)) {
        $url_parameters['tpl'] = $template;
    }

    $items = $listInfo['items'];
    $indent = $items[0]['indent'];

    $i = 0;
    $setup = array ('Ltype' => $Ltype, 'listClass' => $listClass, 'trim' => $trim, 'showcount' => $showcount, 'tid' => $tid, 'numform' => $numform, 'oneform' => $oneform, 'noneform' => $noneform, 'field' => $field);
    $html = pagesetter_CatBrowser_rec($items, $i, $indent, count($items), $url_parameters, $setup, $filter);

    return $html;
}

function pagesetter_CatBrowser_rec(& $items, & $i, $indent, $size, & $url_parameters, & $setup, & $filter_old) {
    if (isset ($setup['listClass']) && $i == 0)
    $html = "<ul class=\"$setup[listClass]\">\n";
    else
    $html = "<ul>\n";

    while ($i < $size) {

        $item = $items[$i];
        if ($item['indent'] < $indent)
        break;

        if ($setup['Ltype'] == 'list')
        $filter_value = "$setup[field]^sub^$item[id]";
        elseif ($setup['Ltype'] == 'multilist')
        $filter_value = "$setup[field]:in:$item[id]";

        //delete if double (deselection)
        $filter_arr = explode(',', $filter_old);
        $filter_new = $filter_value;
        $url_parameters['filter'] = '';
        if ($filter_arr[0] <> '') {
            foreach ($filter_arr as $key => $value) {
                //if a filter exists allready do not add and reset filter_new
                if ($value == $filter_value)
                $filter_new = '';
                else
                $url_parameters['filter'] = $url_parameters['filter'].$value.',';
            }
        }
        if ($filter_new <> '')
        $url_parameters['filter'] = $url_parameters['filter'].$filter_new;

        //clean the ',' at the end
        if (substr($url_parameters['filter'], -1, 1) == ',')
        $url_parameters['filter'] = substr($url_parameters['filter'], 0, strlen($url_parameters['filter']) - 1);

        //just for count;
        $PubList = pnModAPIFunc( 'pagesetter', 'user', 'getPubList', array( 'tid' => $setup['tid'], 'noOfItems' => 999, 'filterSet'  => array(0 =>  $filter_value) ) );
        $thiscount = count( $PubList ['publications'] );
        if ($thiscount > 0 || $setup['trim'] == "no") {
            if ($setup['showcount'] != "no") {
                if ($thiscount == 1) {
                    $numstr = str_replace("%%NOM%%", $thiscount, $setup[oneform]);
                    $item[title] = $item[title].$numstr;
                }
                elseif (thiscount == 0) {
                    $numstr = str_replace("%%NOM%%", $thiscount, $setup[noneform]);
                    $item[title] = $item[title].$numstr;
                } else {
                    $numstr = str_replace("%%NOM%%", $thiscount, $setup[numform]);
                    $item[title] = $item[title].$numstr;
                }
            }

            //empty filter leads to an error
            if ($url_parameters['filter'] == '')
            unset ($url_parameters['filter']);

            $url = pnModUrl('pagesetter', 'user', '', $url_parameters);

            if (strpos($url_parameters['filter'], $filter_value) === false)
            $html .= "<li><b><a href=\"$url\">".pnVarPrepForDisplay($item[title])."</a></b>\n";
            else
            $html .= "<li$className><a href=\"$url\">".pnVarPrepForDisplay($item[title])."</a>\n";
        }

        ++ $i;

        if ($items[$i]['indent'] > $indent) {
            $html .= pagesetter_CatBrowser_rec($items, $i, $indent +1, $size, $url_parameters, $setup);
        }

        $html .= "</li>\n";
    }

    $html .= "</ul>\n";

    return $html;
}






