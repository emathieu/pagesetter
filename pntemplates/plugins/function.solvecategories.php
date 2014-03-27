<?php
function smarty_function_solvecategories ($params, &$smarty) {
    if (!isset($params['value']) || empty($params['value']))
    return '';

    $delimiter = !empty($params['delimiter'])?$params['delimiter']:', ';
    $cats = explode(":", substr($params['value'], 1, -1));
    Loader::LoadClass('CategoryUtil');
    $cattitle = array();
    foreach ($cats as $id) {
        $cat = CategoryUtil::getCategoryById($id);
        $cattitle[] = $cat['name'];
    }

    $out = implode($delimiter, $cattitle);

    if (!isset($params['assign']) || empty($params['assign']))
    return $out;
    $smarty->assign($params['assign'], $out);
}
