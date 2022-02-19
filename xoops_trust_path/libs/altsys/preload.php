<?php declare(strict_types=1);
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
$root = XCube_Root::getSingleton();
//admin page
if ($root->mController->_mStrategy) {
    if (mb_strtolower(get_class($root->mController->_mStrategy)) == mb_strtolower('Legacy_AdminControllerStrategy')) {
        require_once __DIR__ . '/include/altsys_functions.php';

        // language file (modinfo.php)

        altsys_include_language_file('modinfo');
    }
}
// load altsys newly gticket class for other modules
require_once XOOPS_TRUST_PATH . '/libs/altsys/include/gtickets.php';
