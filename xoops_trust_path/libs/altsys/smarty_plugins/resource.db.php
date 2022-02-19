<?php declare(strict_types=1);
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */
/**
 * @param $tpl_name
 * @param mixed $tpl_source
 * @param mixed $smarty
 * @return bool
 */
function smarty_resource_db_source($tpl_name, &$tpl_source, &$smarty)
{
    if (!$tpl = smarty_resource_db_tplinfo($tpl_name)) {
        return false;
    }

    if (is_object($tpl)) {
        $tpl_source = $tpl->getVar('tpl_source', 'n');
    } else {
        $fp = fopen($tpl, 'rb');

        $tpl_source = fread($fp, filesize($tpl));

        fclose($fp);
    }

    return true;
}

/**
 * @param $tpl_name
 * @param mixed $tpl_timestamp
 * @param mixed $smarty
 * @return bool
 */
function smarty_resource_db_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
{
    if (!$tpl = smarty_resource_db_tplinfo($tpl_name)) {
        return false;
    }

    if (is_object($tpl)) {
        $tpl_timestamp = $tpl->getVar('tpl_lastmodified', 'n');
    } else {
        $tpl_timestamp = filemtime($tpl);
    }

    return true;
}

/**
 * @param $tpl_name
 * @param mixed $smarty
 * @return bool
 */
function smarty_resource_db_secure($tpl_name, &$smarty)
{
    // assume all templates are secure

    return true;
}

/**
 * @param $tpl_name
 * @param mixed $smarty
 */
function smarty_resource_db_trusted($tpl_name, &$smarty): void
{
    // not used for templates
}

/**
 * @param $tpl_name
 * @return bool|mixed|string
 */
function smarty_resource_db_tplinfo($tpl_name)
{
    static $cache = [];

    global $xoopsConfig;

    if (isset($cache[$tpl_name])) {
        return $cache[$tpl_name];
    }

    $tplset = $xoopsConfig['template_set'];

    $theme = $xoopsConfig['theme_set'] ?? 'default';

    $tplfileHandler = xoops_getHandler('tplfile');

    // If we're not using the "default" template set, then get the templates from the DB

    if ('default' != $tplset) {
        $tplobj = $tplfileHandler->find($tplset, null, null, null, $tpl_name, true);

        if (count($tplobj)) {
            return $cache[$tpl_name] = $tplobj[0];
        }
    }

    // If we'using the default tplset, get the template from the filesystem

    $tplobj = $tplfileHandler->find('default', null, null, null, $tpl_name, true);

    if (!count($tplobj)) {
        return $cache[$tpl_name] = false;
    }

    $tplobj = $tplobj[0];

    $module = $tplobj->getVar('tpl_module', 'n');

    $type = $tplobj->getVar('tpl_type', 'n');

    $blockpath = ('block' == $type) ? 'blocks/' : '';

    // First, check for an overloaded version within the theme folder

    $filepath = XOOPS_THEME_PATH . "/$theme/modules/$module/$blockpath$tpl_name";

    if (!file_exists($filepath)) {
        // If no custom version exists, get the tpl from its default location

        $filepath = XOOPS_ROOT_PATH . "/modules/$module/templates/$blockpath$tpl_name";

        if (!file_exists($filepath)) {
            return $cache[$tpl_name] = $tplobj;
        }
    }

    return $cache[$tpl_name] = $filepath;
}
