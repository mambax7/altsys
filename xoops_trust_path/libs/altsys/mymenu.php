<?php declare(strict_types=1);

/********* mymenu only for ALTSYS ********/

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

// Skip for ORETEKI XOOPS
if (defined('XOOPS_ORETEKI')) {
    return;
}

if (!isset($module) || !is_object($module)) {
    $module = $xoopsModule;
} elseif (!is_object($xoopsModule)) {
    die('$xoopsModule is not set');
}

// language files (modinfo.php)
altsys_include_language_file('modinfo');

require __DIR__ . '/admin_menu.php';

$adminmenu = array_merge($adminmenu, $adminmenu4altsys);

$mymenu_uri = empty($mymenu_fake_uri) ? $_SERVER['REQUEST_URI'] : $mymenu_fake_uri;
$mymenu_link = mb_substr(mb_strstr($mymenu_uri, '/admin/'), 1);

// xoops_breadcrumbs
// $GLOBALS['altsysXoopsBreadcrumbs'] = array( array( 'url' => XOOPS_URL.'/modules/altsys/admin/index.php' , 'name' => $xoopsModule->getVar('name') ) ) ;

// highlight
foreach (array_keys($adminmenu) as $i) {
    if ($mymenu_link == $adminmenu[$i]['link']) {
        $adminmenu[$i]['selected'] = true;

        $adminmenu_hilighted = true;

    //      $GLOBALS['altsysXoopsBreadcrumbs'][] = array( 'url' => XOOPS_URL."/modules/altsys/".htmlspecialchars($adminmenu[$i]['link'],ENT_QUOTES | ENT_HTML5) , 'name' => htmlspecialchars( $adminmenu[$i]['title'] , ENT_QUOTES ) ) ;
    } else {
        $adminmenu[$i]['selected'] = false;
    }
}
if (empty($adminmenu_hilighted)) {
    foreach (array_keys($adminmenu) as $i) {
        if (mb_stristr($mymenu_uri, $adminmenu[$i]['link'])) {
            $adminmenu[$i]['selected'] = true;

            //          $GLOBALS['altsysXoopsBreadcrumbs'][] = array( 'url' => XOOPS_URL."/modules/altsys/".htmlspecialchars($adminmenu[$i]['link'],ENT_QUOTES | ENT_HTML5) , 'name' => htmlspecialchars( $adminmenu[$i]['title'] , ENT_QUOTES ) ) ;

            break;
        }
    }
}

// link conversion from relative to absolute
foreach (array_keys($adminmenu) as $i) {
    if (false === mb_stristr($adminmenu[$i]['link'], XOOPS_URL)) {
        $adminmenu[$i]['link'] = XOOPS_URL . "/modules/$mydirname/" . $adminmenu[$i]['link'];
    }
}

// display
require_once XOOPS_TRUST_PATH . '/libs/altsys/class/D3Tpl.class.php';
$tpl = new D3Tpl();
$tpl->assign([
                 'adminmenu' => $adminmenu,
             ]);
$tpl->display('db:altsys_inc_mymenu.tpl');
//$tpl->display('db:inc_mymenu.tpl');

// submenu
$page = preg_replace('/[^0-9a-zA-Z_-]/', '', @$_GET['page']);
if (file_exists(__DIR__ . '/mymenusub/' . $page . '.php')) {
    require __DIR__ . '/mymenusub/' . $page . '.php';
}
