<?php declare(strict_types=1);

if (defined('FOR_XOOPS_LANG_CHECKER')) {
    $mydirname = 'd3forum';
}
$constpref = '_MB_' . mb_strtoupper($mydirname);

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined($constpref . '_LOADED')) {
    define($constpref . '_LOADED', 1);

    // definitions for displaying blocks
    // Since altsys is a singleton moudle, this file has non-sense.
}
