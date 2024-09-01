<?php declare(strict_types=1);

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$core_type = altsys_get_core_type();
$db = XoopsDatabaseFactory::getDatabaseConnection();

$current_dirname = preg_replace('/[^0-9a-zA-Z_-]/', '', @$_GET['dirname']);
if ('__CustomBlocks__' == $current_dirname) {
    return;
}

$moduleHandler4menu = xoops_getHandler('module');
$criteria4menu = new \CriteriaCompo(new \Criteria('isactive', 1));
//$criteria4menu->add(new \Criteria('hasmain', 1));
$criteria4menu->add(new \Criteria('mid', '1', '>'));
$modules4menu = $moduleHandler4menu->getObjects($criteria4menu, true);
$system_module = $moduleHandler4menu->get(1);
if (is_object($system_module)) {
    array_unshift($modules4menu, $system_module);
}

$adminmenu = [];
foreach ($modules4menu as $m4menu) {
    // get block info

    if (ALTSYS_CORE_TYPE_X22 != $core_type) {
        [$block_count_all] = $db->fetchRow($db->query('SELECT COUNT(*) FROM ' . $db->prefix('newblocks') . ' WHERE mid=' . $m4menu->getVar('mid')));

        [$block_count_visible] = $db->fetchRow($db->query('SELECT COUNT(*) FROM ' . $db->prefix('newblocks') . ' WHERE mid=' . $m4menu->getVar('mid') . ' AND visible>0'));

        // $block_desc = " $block_count_all($block_count_visible)" ;

        $block_desc = " ($block_count_visible/$block_count_all)";
    } else {
        $block_desc = '';
    }

    if ($m4menu->getVar('dirname') == $current_dirname) {
        $adminmenu[] = [
            'selected' => true,
            'title' => $m4menu->getVar('name', 'n') . $block_desc,
            'link' => '?mode=admin&lib=altsys&page=myblocksadmin&dirname=' . $m4menu->getVar('dirname', 'n'),
        ];

    //$GLOBALS['altsysXoopsBreadcrumbs'][] = array( 'name' => $m4menu->getVar('name') ) ;
    } else {
        $adminmenu[] = [
            'selected' => false,
            'title' => $m4menu->getVar('name', 'n') . $block_desc,
            'link' => '?mode=admin&lib=altsys&page=myblocksadmin&dirname=' . $m4menu->getVar('dirname', 'n'),
        ];
    }
}

// display
require_once XOOPS_TRUST_PATH . '/libs/altsys/class/D3Tpl.class.php';
$tpl = new D3Tpl();
$tpl->assign([
                 'adminmenu' => $adminmenu,
                 'mypage' => 'myblocksadmin',
             ]);
$tpl->display('db:altsys_inc_mymenusub.tpl');
