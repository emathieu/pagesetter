<?php
// Dieses Plugin decodiert das Ergebnis des Guppy Plugins input.pubaslist.php
// Speichern unter function_pagesetter_pubaslist.php
// Es wird ein Link zur Publicaton erstellt.
// Aufruf im Template :
// <!--[pagesetter_pubaslist item=$Feldname]-->
//

function smarty_function_pagesetter_pubaslist($args, &$smarty)
{
    if (!isset($args['item'])) {
        return 'Fehler....';
    }

    $core = $smarty->get_template_vars('core');
    $baseURL   = isset($args['baseURL']) ? $args['baseURL'] : $core['baseURL'];
    $items = explode(';', $args['item']);

    // Pubtype am Feldnamen erkennen
    $pubtypes = pnModAPIFunc('pagesetter', 'admin', 'getPublicationTypes',$args );
    foreach ($pubtypes as $pubtype)
    {
        if( $pubtype[title] == $items[0]){
            $args['tid']= $pubtype[id];
            break;
        }
    }
    $args['pid'] = $items[1];

    // Pubtitel holen
    $pub = pnModAPIFunc('pagesetter', 'user', 'getPub',$args );
    $html .= "<a href=\"index.php?module=Pagesetter&amp;func=viewpub&amp;tid=".$args['tid']."&amp;pid=".$args['pid']."\">".$pub['core_title']."</a>&nbsp;";

    if (isset($args['assign'])) {
        $smarty->assign($args['assign'], $html);
    } else {
        return  $html;
    }
}


