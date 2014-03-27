<?php
/**
 * decode the custom url string
 *
 * @author Philipp Niethammer
 * @return bool true if successful, false otherwise
 */
function pagesetter_userapi_decodeurl($args)
{
    // check we actually have some vars to work with...
    if (!isset($args['vars'])) {
        return LogUtil::registerError(_MODARGSERROR." pagesetter_userapi_decodeurl");
    }
    // define the available user functions
    $funcs = array('main', 'view', 'printlist', 'dumplist', 'xmllist', 'publist', 'pubfind', 'pubedit', 'viewpub', 'printpub', 'dumppub', 'xmlpub', 'preview', 'sendpub', 'history');
    if (!in_array($args['vars'][2], $funcs)) {
        $pubTypes = pnModAPIFunc('pagesetter', 'admin', 'getPublicationTypes');
        foreach ($pubTypes as $type) {
            if ($args['vars'][2] == DataUtil::formatForURL($type['title']) || $args['vars'][2] == $type['title']) {
                $tid = $type['id'];
                pnQueryStringSetVar('tid', $type['id']);
                $nextvar = 3;
                break;
            }
        }
    } else {
        return false;
    }
    pnQueryStringSetVar('func', 'main');
    if (!isset($args['vars'][3]) || empty($args['vars'][3])) {
        return true;
    } elseif (substr($args['vars'][3], -5) != '.html') {
        Loader::LoadClass('CategoryUtil');

        $pubTypeInfo = pnModAPIFunc('pagesetter', 'admin', 'getPubTypeInfo', compact('tid'));
        if (isset($pubTypeInfo['fields'][$pubTypeInfo['fieldIndex']['category']])) {
            $field = $pubTypeInfo['fields'][$pubTypeInfo['fieldIndex']['category']];
            if ($field['type'] == 'categorylist') {
                $typeData = explode(':', $field['typeData']);
                $pcat = $typeData[0];
                $cats = CategoryUtil::getSubCategories($pcat);
                foreach ($cats as $cat) {
                    if ($args['vars'][3] == DataUtil::formatForURL($cat['name']) || $args['vars'][3] == $cat['name']) {
                        $catid = $cat['id'];
                        break;
                    }
                }
            } elseif ($field['type'] > 100) {
                $lid = $field['type'] - 100;
                $list = pnModAPIFunc('pagesetter', 'admin', 'getList', compact('lid'));
                foreach ($list['items'] as $item) {
                    if ($args['vars'][3] == DataUtil::formatForURL($item['title']) || $args['vars'][3] == $item['title']) {
                        $catid = $item['id'];
                        break;
                    }
                }
            }
        }

        if ($catid) {
            pnQueryStringSetVar('cat', $catid);
            if (!isset($args['vars'][4]) || empty($args['vars'][4]) || substr($args['vars'][4], -5) != '.html') {
                pnQueryStringSetVar('func', 'main');
                $actfilter = FormUtil::getPassedValue('filter');
                pnQueryStringSetVar('filter', (!empty($actfilter) ? ($actfilter . ',') : '') . 'category:eq:' . $catid);
                $nextvar = 4;
            } else {
                $nextvar = 4;
                $filter = 'category:eq:' . $catid;
            }
        }
    }

    if (substr($args['vars'][$nextvar], -5) == '.html') {
        if ($tid == 3) {
            $pid = substr($args['vars'][$nextvar], 8, -5);
            pnQueryStringSetVar('func', 'viewpub');
            pnQueryStringSetVar('pid', $pid);
            $nextvar++;
        } else {
            //            Loader::LoadClass('SiteUtil');
            //            $filter .= (!empty($filter)?",":'').SiteUtil::getSiteFilter();
            if ($filter) {
                $pubs = pnModAPIFunc('pagesetter', 'user', 'getPubList', array('tid' => $tid, 'filterSet' => array($filter), 'noOfItems' => 0));
            } else {
                $pubs = pnModAPIFunc('pagesetter', 'user', 'getPubList', array('tid' => $tid, 'noOfItems' => 0));
            }
            $title = substr($args['vars'][$nextvar], 0, -5);
            foreach ($pubs['publications'] as $pub) {
                if ($title == DataUtil::formatForURL($pub['title']) || $title == $pub['title']) {
                    pnQueryStringSetVar('func', 'viewpub');
                    pnQueryStringSetVar('pid', $pub['pid']);
                    $nextvar++;
                    break;
                }
            }
        }
    }
    $extra = explode(';', $args['vars'][$nextvar]);
    $filter = FormUtil::getPassedValue('filter', '', 'GETPOST');
    foreach ($extra as $param) {
        $var = explode('-', $param, 2);
        if ($var[0] == 'filter' && !empty($filter)) {
            $var[1] = FormUtil::getPassedValue('filter', '', 'GETPOST') . ',' . $var[1];
        }
        pnQueryStringSetVar($var[0], $var[1]);
    }
    return true;
}