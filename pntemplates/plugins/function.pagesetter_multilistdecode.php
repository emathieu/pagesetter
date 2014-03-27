<?php
/*
 * Created on 18.02.2006
 *
 * Type: Function
 * Author: Philipp Niethammer <webmaster@nochwer.de>
 * Orginal author: Axel Guckelsberger (info@guite.de)
 * Aufruf:
 * <!--[pagesetter_multilistdecode field="listfield" assign="listitems"]-->
 * 
 *@param params['field']  String   	fieldname of list items
 *@param params['assign'] String 	var name to assign result to
 *@return array                         array with list item entries
 */
function smarty_function_pagesetter_multilistdecode($args, &$smarty)
{
	include_once('modules/pagesetter/common.php');

	$fieldname = $args['field'];
	$assign = $args['assign'];
    unset($args);

    if (!isset($fieldname))
    {
        $smarty->trigger_error("Missing parameter 'field'");
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (!pnModAPILoad('pagesetter','admin'))
      $smarty->trigger_error(_MODAPILOADFAILED);
      
    $tid = pagesetterGetTID();

	$pubInfo = pnModAPIFunc('pagesetter',
							'admin',
							'getPubTypeInfo',
							compact('tid'));
							
	$fieldIndex = isset($pubInfo['fieldIndex'][$fieldname]) ? $pubInfo['fieldIndex'][$fieldname] : false;
	if ($fieldIndex === FALSE)
	{
		$smarty->trigger_error("Fieldname '$fieldname' does not exists");
	}
	
  	$field = $pubInfo['fields'][$fieldIndex];
  	
  	if ($field['type'] != 'multilist')
  	{
  		$smarty->trigger_error("Field '$fieldname' is not of type multilist");
  	}
  	
  	$typeextra = explode(':', $type['typeData']);
  	$lid = $typeextra[0];
  		
    $list = pnModAPIFunc('pagesetter',
                         'admin',
                         'getList',
                         array('lid' => $lid));
                         
    if ($list === false) {
    	$smarty->trigger_error("List does not exists");
    }
    
    $list = $list['items'];
                         
    $ids = explode(':', $ids);
	unset($ids[0]);
	unset($ids[count($ids)-1]);
	
	$items = array();
	foreach ($list as $item) {
		if (in_array($item['id'], $ids))
			$items[] = $item;
	}
 
 	if (empty($assign))
 		return $items;
    
    $smarty->assign($assign, $items);
    return;
}

?>
