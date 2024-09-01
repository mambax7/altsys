<?php declare(strict_types=1);

if (!defined('XOOPS_MODULE_PATH')) {
    define('XOOPS_MODULE_PATH', XOOPS_ROOT_PATH . '/modules');
}
if (!defined('XOOPS_MODULE_URL')) {
    define('XOOPS_MODULE_URL', XOOPS_URL . '/modules');
}

require_once __DIR__ . '/class/AltsysBreadcrumbs.class.php';
require_once __DIR__ . '/include/altsys_functions.php';

if (empty($xoopsModule)) {
    $grouppermHandler = xoops_getHandler('module');

    $xoopsModule = $grouppermHandler->getByDirname('altsys');
}

require XOOPS_ROOT_PATH . '/include/cp_functions.php';

// breadcrumbs
$breadcrumbsObj = AltsysBreadcrumbs::getInstance();
$breadcrumbsObj->appendPath(XOOPS_URL . '/modules/altsys/admin/index.php', $GLOBALS['xoopsModule']->getVar('name'));

// get page
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', @$_GET['page']??'');
require __DIR__ . '/controllers.php';
if (!in_array($page, $controllers, true)) {
    $_GET['page'] = $page = 'myblocksadmin';

    $_SERVER['REQUEST_URI'] = '/admin/index.php?mode=admin&lib=altsys&page=myblocksadmin';
}

// half measure ... (TODO)
if (empty($_GET['dirname'])) {
    $moduleHandler = xoops_getHandler('module');

    [$top_module] = $moduleHandler->getObjects(new \Criteria('isactive', 1));

    $_GET['dirname'] = $top_module->getVar('dirname');
}

// language file
altsys_include_language_file($page);
xoops_loadLanguage('admin', 'system');

// branch to each pages
$mytrustdirpath = __DIR__;
if (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/' . $page . '.php')) {
    include XOOPS_TRUST_PATH . '/libs/altsys/' . $page . '.php';
} else {
    die('wrong request');
}
