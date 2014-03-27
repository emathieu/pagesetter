<?php

/**
 * form custom url string
 *
 * @author Philipp Niethammer
 * @return string custom url string
 */
function pagesetter_userapi_encodeurl($args)
{
    // check we have the required input
    if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
        return LogUtil::registerError(_MODARGSERROR);
    }

    $supportedfuncs = array('main', 'view', 'viewpub');
    if (!in_array($args['func'], $supportedfuncs)) {
        return '';
    }

    // create an empty string ready for population
    $vars = '';

    // view function
    if (!isset($args['args']['tid'])) {
        return LogUtil::registerError(_MODARGSERROR);
    }

    $pubInfo = pnModAPIFunc('pagesetter', 'admin', 'getPubTypeInfo', array('tid' => $args['args']['tid']));
    $type = DataUtil::formatForURL($pubInfo['publication']['title']);

    if (isset($args['args']['cat']) && !empty($args['args']['cat'])) {
        if ($pubInfo['fields'][$pubInfo['fieldIndex']['category']]['type'] == 'categorylist') {
            Loader::LoadClass('CategoryUtil');
            $catInfo = CategoryUtil::getCategoryByID($args['args']['cat']);
            $cat = "/" . DataUtil::formatForURL($catInfo['name']);
        } elseif ($pubInfo['fields'][$pubInfo['fieldIndex']['category']]['type'] > 100) {
            $lid = $pubInfo['fields'][$pubInfo['fieldIndex']['category']]['type'] - 100;
            $list = pnModAPIFunc('pagesetter', 'admin', 'getList', compact('lid'));
            foreach ($list['items'] as $item) {
                if ($item['id'] == $args['args']['cat']) {
                    $cat = "/" . DataUtil::formatForURL($item['title']);
                    break;
                }
            }
        }
    }

    if ($args['func'] == 'viewpub') {
        if (!isset($args['args']['pid'])) {
            return LogUtil::registerError(_MODARGSERROR);
        }
        if ($args['args']['tid'] == 3) {
            $title = "/Adresse-" . $args['args']['pid'] . ".html";
        } else {
            $pub = pnModAPIFunc('pagesetter', 'user', 'getPub', array('tid' => $args['args']['tid'], 'pid' => $args['args']['pid']));
            $title = "/" . DataUtil::formatForURL($pub['core_title']) . ".html";
        }
    }

    unset($args['args']['tid'], $args['args']['pid'], $args['args']['cat']);
    if (is_array($args['args']) && count($args['args']) > 0) {
        $first = true;
        $extra = '/';
        foreach ($args['args'] as $k => $v) {
            if (!$first) {
                $extra .= ';';
            }
            $first = false;
            $extra .= $k . "-" . DataUtil::formatForStore($v);
        }
    }

    return $args['modname'] . "/" . $type . $cat . $title . $extra;
}