<?php declare(strict_types=1);
// ------------------------------------------------------------------------- //
//                       myblocksadmin.php (altsys)                          //
//                - XOOPS block admin for each modules -                     //
//                       GIJOE <https://www.peak.ne.jp>                      //
// ------------------------------------------------------------------------- //

require_once __DIR__ . '/class/AltsysBreadcrumbs.class.php';
require_once __DIR__ . '/include/gtickets.php';
require_once __DIR__ . '/include/altsys_functions.php';
require_once __DIR__ . '/include/mygrouppermform.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

// language file
altsys_include_language_file('myblocksadmin');

// fork by core types
switch (altsys_get_core_type()) {
    case ALTSYS_CORE_TYPE_X22:
        require_once __DIR__ . '/class/MyBlocksAdminForX22.class.php';
        $myba = MyBlocksAdminForX22::getInstance();
        break;
    case ALTSYS_CORE_TYPE_X25:
        require_once __DIR__ . '/class/MyBlocksAdminForX25.class.php';
        $myba = MyBlocksAdminForX25::getInstance();
        break;
    case ALTSYS_CORE_TYPE_XCL21:
        require_once __DIR__ . '/class/MyBlocksAdminForXCL21.class.php';
        $myba = MyBlocksAdminForXCL21::getInstance();
        break;
    case ALTSYS_CORE_TYPE_ICMS:
        require_once __DIR__ . '/class/MyBlocksAdminForICMS.class.php';
        $myba = MyBlocksAdminForICMS::getInstance();
        break;
    case ALTSYS_CORE_TYPE_X20S:
    case ALTSYS_CORE_TYPE_X23P:
        require_once __DIR__ . '/class/MyBlocksAdminForX20S.class.php';
        $myba = MyBlocksAdminForX20S::getInstance();
        break;
    default:
        require_once __DIR__ . '/class/MyBlocksAdmin.class.php';
        $myba = MyBlocksAdmin::getInstance();
        break;
}
// permission
$myba->checkPermission();

// set parameters target_mid , target_dirname etc.
$myba->init($xoopsModule);

//
// transaction stage
//

if (!empty($_POST)) {
    $myba->processPost();
}

//
// form stage
//

// header
xoops_cp_header();

// mymenu
altsys_include_mymenu();

$myba->processGet();

// footer
xoops_cp_footer();
