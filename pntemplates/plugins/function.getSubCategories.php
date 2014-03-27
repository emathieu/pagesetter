<?php

function smarty_function_getSubCategories($params, &$smarty)
{
    extract($params);
    unset($params);

    $list = pnModAPIFunc( 'pagesetter', 'admin', 'getList', array( 'lid' => $lid ) );
    $subCategories=array();

    foreach ($list[items] as $listItem) {
         
        if ($listItem[parentID]==$cid) {
            $subCategories[] = $listItem;
        }
         
    }

    $smarty->assign($assign, $subCategories);
    return;
}