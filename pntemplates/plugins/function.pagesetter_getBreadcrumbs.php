<?php
/**
 * function smarty_function_pagesetter_getBreadcrumbs($params, &$smarty)
 * Gets breadcrumbs information for nested publications
 *
 * @param string $assign [optional] The name of the variable you would like the return value to be assigned to.
 * @param string $id category item id
 * @return array Array of items that make up the breadcrumb trail from the topmost parent to the bottom
 * (specified by id) child.
 *
 */
function smarty_function_pagesetter_getBreadcrumbs($params, &$smarty)
{
    if (empty($params['id'])) {
        //if (empty($params['tid']) || empty($params['id']) || empty($params['field'])) {
        $o = array();
    } else {
        //$seperator = (!empty($params['seperator']))?$params['seperator']:" &gt; "; // &raquo;


        $o = getParentItems($params['id']);
    }



    if (is_array($o)) $o = array_reverse($o);

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $o);
    } else {
        return $o;
    }
}

function getParentItems($id, $items = array())
{
    require_once ( 'function.pagesetter_getCategoryItem.php' );

    $item = smarty_function_pagesetter_getCategoryItem($params=array('id'=>$id), $smarty);

    if (empty($item)) {
        return $items;
    }

    $items[] = $item;

    if ($item['parentid'] != -1) {
        return getParentItems($item['parentid'], $items);
    } else {
        return $items;
    }
}
