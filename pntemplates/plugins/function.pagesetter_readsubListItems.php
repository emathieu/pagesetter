<?php

function smarty_function_pagesetter_readsubListItems($args, &$smarty)
{
    //$smarty->caching = 0; // Nice for debugging ...

    if (!isset($args['itemID']))
    return "Missing 'itemID' argument in Smarty plugin 'pagesetter_readsubListItems'";

    if (!pnModAPILoad('pagesetter', 'admin'))
    return pagesetterErrorPage(__FILE__, __LINE__,
                                  'Failed to load Pagesetter admin API');
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();
    $listitemsTable = $pntable['pagesetter_listitems'];
    $listitemsColumn = &$pntable['pagesetter_listitems_column'];

    $sql = "SELECT $listitemsColumn[title], $listitemsColumn[id]
            FROM $listitemsTable
            WHERE $listitemsColumn[parentID] = ".$args['itemID'];

    $result = $dbconn->execute($sql);
    $a = 0;
    for (; !$result->EOF; $result->MoveNext())
    {
        $subItems[$a]['title'] = $result->fields[0];
        $subItems[$a]['id'] = $result->fields[1];
        $a++;
    }
    if (isset($args['assign'])) {
        $smarty->assign($args['assign'], $subItems);
    } else {
        return $subItems;
    }
}


