<?php
//Examples
//  without filter:   <!--[pagesetter_CountPubs tid=4 assign=pubCount]-->
//                    <!--[if $pubCount > 0]-->
//                      your conditional statements
//                    <!--[/if]-->
//
//  with filter:      <!--[pagesetter_createFilter filter="category:eq:10" assign=listFilter]-->
//                    <!--[pagesetter_CountPubs tid=4 filter=$listFilter assign=pubCount]-->
//                      see previous example
function smarty_function_pagesetter_CountPubs($args, &$smarty)
{
    extract( $args );
    unset( $args );

    if( !isset( $tid ) )
    {
        $smarty->trigger_error( "*** CountPubs: missing parameter 'tid'", E_ERROR );
        return false;
    }
    pnModAPILoad('pagesetter', 'user' );
    //Filter added 2005-1-14 Claus Parkhoi
    $list = pnModAPIFunc( 'pagesetter', 'user', 'getPubList', array( 'tid' => $tid, 'noOfItems' => 999, 'filterSet'  => $filter ) );
    if (isset($args['assign'])) {
        $smarty->assign($args['assign'], count( $list['publications'] ));
    } else {
        return count( $list['publications'] );
    }
}


