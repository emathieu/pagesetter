<?php

function smarty_function_pagesetter_readListItem($args, &$smarty)
{
    //$smarty->caching = 0; // Nice for debugging ...

    if (!isset($args['itemID']))
    return "Missing 'itemID' argument in Smarty plugin 'pagesetter_CreateFilter'";

    if (!pnModAPILoad('pagesetter', 'admin'))
    return pagesetterErrorPage(__FILE__, __LINE__, 'Failed to load Pagesetter admin API');
    list($dbconn) = pnDBGetConn();
    $pntable = pnDBGetTables();
    $listitemsTable = $pntable['pagesetter_listitems'];
    $listitemsColumn = &$pntable['pagesetter_listitems_column'];

    $sql = "SELECT $listitemsColumn[fullTitle]
                                                FROM $listitemsTable
                                                WHERE $listitemsColumn[id] = ".$args['itemID'];

    $result = $dbconn->execute($sql);
    $teamFull = explode(":",$result->fields[0]);
    if (isset($args['assign'])) {
        $smarty->assign($args['assign'], $teamFull);
    } else {
        return $teamFull;
    }
}


