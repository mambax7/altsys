<?php declare(strict_types=1);

if (!$xoopsConfig['theme_fromfile']) {
    return;
}

// templates/ under the theme
$tplsadmin_autoupdate_path = XOOPS_THEME_PATH . '/' . $xoopsConfig['theme_set'] . '/templates';

if ($handler = @opendir($tplsadmin_autoupdate_path . '/')) {
    while (false !== ($file = readdir($handler))) {
        $file_path = $tplsadmin_autoupdate_path . '/' . $file;

        if (is_file($file_path) && '.tpl' == mb_substr($file, -5)) {
            $mtime = (int) (@filemtime($file_path));

            [$count] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_tplset='" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file='" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));

            if ($count <= 0) {
                require_once XOOPS_TRUST_PATH . '/libs/altsys/include/tpls_functions.php';

                tplsadmin_import_data($xoopsConfig['template_set'], $file, implode('', file($file_path)), $mtime);
            }
        }
    }
}
