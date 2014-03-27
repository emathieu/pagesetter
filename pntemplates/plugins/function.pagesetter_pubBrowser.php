<?php
/** 
 * Function:
 *
 * reads all publications from the current pubtype list and creates an array with
 * previous and next links for browsing through the publications.
 * The first and the last link go the the list of the pubtype.
 * 
 * Usage:
 * Customize the settings below. There's a customization area below. Don't edit anything outside of this area.
 * Put <!--[pagesetter_pubBrowser]--> at the top of the pubtype-full.html template.
 * This function returns an array with 2 values:        
 * Place <!--[$nav.prev]-->, where you want the link to the previous pub be located
 * Place <!--[$nav.next]-->, where you want the link to the previous pub be located
 *
 * Author:  Thomas Smiatek
 * Email : thomas@smiatek.com
 * Revision: 1.1
 * Date:  13.07.2004
 * 
 *
 */
function smarty_function_pagesetter_pubBrowser($args, &$smarty)
{

	//*****************************************
	// Customization area
	//*****************************************
	// Define the text attributes for the links (size, color...)
	$style = "color: black; text-decoration: none;";
	// Define a symbol. It is displayed left of the previous link. You can leave it blank, if you don't need it.
	$prevSymbol = "&lt;&lt; ";
	// Define a symbol. It is displayed right of the next link. You can leave it blank, if you don't need it.
	$nextSymbol = " &gt;&gt;";
	// The previous link of the first publication links to the list of the current pub type. Define the text here.
	$back2overview_left = "back to overview";
	// The next link of the last publication links to the list of the current pub type. Define the text here.
	$back2overview_right = "back to overview";
	//*****************************************
	// End of customization area
	//*****************************************
	
	$core = $smarty->get_template_vars('core');
	$id = $core['id'];
	
	if (!isset($language))
	    $language = pnUserGetLang();
	
    // Get stuff that exists in core
    $page      = isset($args['page']) ? intval($args['page']) : intval($core['page']);
    $baseURL   = isset($args['baseURL']) ? $args['baseURL'] : $core['baseURL'];

    //Build filter array and filter string
    //this will get filter, filter1,...,filtern from the url
    //note: the filter string will use filters starting from filter1
    $filterStrSet = pagesetterGetFilters(array(), $dummyArgs);
    if(count($filterStrSet) != 0)
    {
      $temp = array();
      foreach( $filterStrSet as $key => $item )
      {
        $i = $key + 1;
        $temp[] = "filter$i=" . $item;
      }
      $filterStr = "&amp;" . implode("&amp;", $temp);
    } else $filterStr = "";
    $baseURL .= $filterStr;
    
	$pubList = pnModAPIFunc( 'pagesetter', 'user', 'getPubList',
	                         array( 'tid'       => $core['tid'],
	                                'language'  => $language,
                                    'filterSet' => $filterStrSet, 
                                    'noOfItems' => -1 ) );//EM: added 'noOfItems' => -1
	
    $pinfo = pnModAPIFunc( 'pagesetter', 'admin', 'getPubTypeInfo', array( 'tid' => $core['tid'], 'noOfItems' => 999 ) );    
    $perpage = $pinfo['publication']['listCount'];
    $perpage = isset($args['pageSize']) ? $args['pageSize'] : $perpage;

	$html = '';
	
	$counter = 0;
	$navhtml['prev'] = '<a href="index.php?module=pagesetter&amp;tid='.$core['tid'].'" style="'.$style.'">'.$prevSymbol.$back2overview_left.'</a>';
	$navhtml['next'] = '<a href="index.php?module=pagesetter&amp;tid='.$core['tid'].'" style="'.$style.'">'.$nextSymbol.$back2overview_right.'</a>';
	$foundprev = false;
	$foundnext = false;
	foreach ($pubList['publications'] as $pub)
  	{
		$counter++;
		if (($pub['id'] != $id) && ($foundprev == false))
		{
			$link = htmlspecialchars( pnModURL('pagesetter','user','viewpub',array('tid' => $core['tid'], 'pid' => $pub['pid'] )));
			$navhtml['prev'] = '<a href="'.$link.'" style="'.$style.'">'.$prevSymbol.$pub['title'].'</a>';
		}
		if ($pub['id'] == $id)
		{
			$foundprev = true;
            $page = ceil($counter / $perpage);
			$link = htmlspecialchars( pnModURL('pagesetter','user','view',array('tid' => $core['tid'], 'page' => $page )));
			$navhtml['list'] = '[&nbsp;<a href="'.$link.'" style="'.$style.'">Liste</a>&nbsp;]';
		}
		if (($pub['id'] != $id) && ($foundprev == true) && ($foundnext == false))
		{
			$foundnext = true;
			$link = htmlspecialchars( pnModURL( 'pagesetter', 'user', 'viewpub', array( 'tid' => $core['tid'], 'pid' => $pub['pid'] )));
			$navhtml['next'] = '<a href="'.$link.'" style="'.$style.'">'.$pub['title'].$nextSymbol.'</a>';
		}
	}
	
	$smarty->assign('nav', $navhtml );
	
}
?>
