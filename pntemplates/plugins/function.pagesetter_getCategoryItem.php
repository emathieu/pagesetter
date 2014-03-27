<?php
/**
 * function smarty_function_pagesetter_getCategoryItem($params, &$smarty)
 * Gets details about a category item from an item id
 *
 * @param string $assign [optional] The name of the variable you would like the return value to be assigned to.
 * @param string $id The itemId of the required item.
 * @return array The item details
 *
 */
function smarty_function_pagesetter_getCategoryItem($params, &$smarty)
{
    extract($params);

    if (empty($id)) $o = array();

    $pntable = pnDBGetTables();

    $listItemsTable = $pntable['pagesetter_listitems'];
    $listItemsColumn = &$pntable['pagesetter_listitems_column'];

    $q = "select * from $listItemsTable where $listItemsColumn[id] = '".$id."'";

    //out ($q);

    //$o = dbqArray($q); // will only work if aspedia extensions are installed.
    // expand this in case
    list($dbconn) = pnDBGetConn();
    $priorFetchMode = $ADODB_FETCH_MODE;
    $dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
    $result = & $dbconn->Execute($q);
    //out ("test");
    //out ($result);
    //out ("test2");
    $dbconn->SetFetchMode($priorFetchMode);
    if ($dbconn->ErrorNo() != 0) {
        //if ($showErrors) {
        //out ("<strong>SQL Error ".$dbconn->ErrorNo().":</strong><br />".$dbconn->ErrorMsg()."<p>".pre_print_r_str($sql)."</p> ");
        //}
        $o = array(); // return empty
    } else {
        if ($result->EOF) return false;
        $retArray=array();
        while (!$result->EOF && $endlessCheck < 10000) { // limit 10000 results
            $retArray[] = $result->fields;
            $result->MoveNext();
            $endlessCheck++;
        }
        $temp = & $retArray[0];
        $o = array();
        foreach ($temp as $k=>$v) {
            $o[substr($k, 3)] = $v;
        }
    }
    $result->Close();



    if (isset($assign)) {
        $smarty->assign($assign, $o);
    } else {
        return $o;
    }
}

