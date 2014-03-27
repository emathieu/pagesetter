<?php
// This plugin extracts the ID from a filter like: cat:eq:1 return 1
// use:
// <!--[pnvarcleanfrominput name="filter" assign="filter"]-->
// <!--[pagesetter_extractIDFromFilter filter=$filter assign="id"]-->
// Now you can use the ID of a list item to get its title
// if you use several filters adjust name="filter" to filter0, filter1 aso.



function smarty_function_pagesetter_extractIDFromFilter($args, &$smarty)
{
    if(!isset($args['filter']) || empty($args['filter'])) {
        $smarty->trigger_error(
 "smarty_function_pagesetter_extractIDFromFilter:
  missing parameter 'filter'" );
        return false;
    }
    $id = explode(':', $args['filter']);
    $assign = $args['assign'];
    $smarty->assign($assign, (int)$id[2]);
}
