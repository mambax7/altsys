<?php declare(strict_types=1);

if (!defined('XOOPS_TRUST_PATH')) {
    exit('set XOOPS_TRUST_PATH into mainfile.php');
}

$mydirname = \basename(\dirname(__DIR__));
$mydirpath = \dirname(__DIR__);
// require $mydirpath.'/mytrustdirname.php' ; // set $mytrustdirname
$mytrustdirname = 'altsys';

require XOOPS_TRUST_PATH . '/libs/' . $mytrustdirname . '/blocks.php';
