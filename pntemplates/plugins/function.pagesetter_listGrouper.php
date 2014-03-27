<?php
function smarty_function_pagesetter_listGrouper($args, &$smarty)
{	
	$smarty->caching = 0;
	
	extract( $args );
	
	static $pgListGroupValue = null;
	if( $clear )
	{
		$pgListGroupValue = null;
		return;
	}
	
	if( !isset( $value ) )
	{
		return "<br\>Missing 'value' argument in Smarty plugin 'pagesetter_listGrouper'<br\>";
	}
	
	if( $result = ($pgListGroupValue != $value) )
	{
		$pgListGroupValue = $value;		
	}
	
	if( isset($assign) )
	{
		$smarty->assign( $assign, $result );
	}
	else
	{
		return $result;
	}
}	
?>
