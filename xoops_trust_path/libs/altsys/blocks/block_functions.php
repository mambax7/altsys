<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/include/altsys_functions.php';

/**
 * @param $options
 * @return array
 */
function b_altsys_admin_menu_show($options)
{
    global $xoopsUser;

    $mydirname = empty($options[0]) ? 'altsys' : $options[0];

    $this_template = empty($options[1]) ? 'db:' . $mydirname . '_block_admin_menu.tpl' : trim($options[1]);

    if (preg_match('/[^0-9a-zA-Z_-]/', $mydirname)) {
       exit('Invalid mydirname');
    }

    if (!is_object(@$xoopsUser)) {
        return [];
    }

    // coretype

    $coretype = altsys_get_core_type();

    // mid_selected

    if (is_object(@$GLOBALS['xoopsModule'])) {
        $mid_selected = $GLOBALS['xoopsModule']->getVar('mid');

        // for system->preferences

        if (1 == $mid_selected && 'preferences' == @$_GET['fct'] && 'showmod' == @$_GET['op'] && !empty($_GET['mod'])) {
            $mid_selected = \Xmf\Request::getInt('mod', 0, 'GET');
        }
    } else {
        $mid_selected = 0;
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    (method_exists('MyTextSanitizer', 'sGetInstance') and $myts = MyTextSanitizer::sGetInstance()) || $myts = MyTextSanitizer::getInstance();

    $moduleHandler = xoops_getHandler('module');

    $current_module = $moduleHandler->getByDirname($mydirname);

    $configHandler = xoops_getHandler('config');

    $current_configs = $configHandler->getConfigList($current_module->mid());

    $grouppermHandler = xoops_getHandler('groupperm');

    $admin_mids = $grouppermHandler->getItemIds('module_admin', $xoopsUser->getGroups());

    $modules = $moduleHandler->getObjects(new \Criteria('mid', '(' . implode(',', $admin_mids) . ')', 'IN'), true);

    $block = [
        'mydirname' => $mydirname,
        'mod_url' => XOOPS_URL . '/modules/' . $mydirname,
        'mod_imageurl' => XOOPS_URL . '/modules/' . $mydirname . '/' . $current_configs['images_dir'],
        'mod_config' => $current_configs,
    ];

    foreach ($modules as $mod) {
        $mid = (int) $mod->getVar('mid');

        $dirname = $mod->getVar('dirname');

        $modinfo = $mod->getInfo();

        $submenus4assign = [];

        $adminmenu = [];

        $adminmenu4altsys = [];

        unset($adminmenu_use_altsys);

        @require XOOPS_ROOT_PATH . '/modules/' . $dirname . '/' . @$modinfo['adminmenu'];

        // from admin_menu.php etc.

        $adminmenu = array_merge($adminmenu, $adminmenu4altsys);

        foreach ($adminmenu as $sub) {
            $link = empty($sub['altsys_link']) ? $sub['link'] : $sub['altsys_link'];

            if (isset($sub['show']) && false === $sub['show']) {
                continue;
            }

            $submenus4assign[] = [
                'title' => htmlspecialchars($sub['title']),
                'url' => XOOPS_URL . '/modules/' . $dirname . '/' . htmlspecialchars($link, ENT_QUOTES | ENT_HTML5),
            ];
        }

        // for modules overriding Module.class.php (eg. Analyzer for XC)

        if (empty($submenus4assign) && defined('XOOPS_CUBE_LEGACY') && !empty($modinfo['cube_style'])) {
            $moduleHandler = xoops_getHandler('module');

            $module = $moduleHandler->get($mid);

            $moduleObj = Legacy_Utils::createModule($module);

            $modinfo['adminindex'] = $moduleObj->getAdminIndex();

            $modinfo['adminindex_absolute'] = true;

            foreach ($moduleObj->getAdminMenu() as $sub) {
                if (false === @$sub['show']) {
                    continue;
                }

                $submenus4assign[] = [
                    'title' => htmlspecialchars($sub['title']),
                    'url' => 0 === strncmp((string) $sub['link'], 'http', 4) ? htmlspecialchars((string) $sub['link'], ENT_QUOTES | ENT_HTML5) : XOOPS_URL . '/modules/' . $dirname . '/' . htmlspecialchars((string) $sub['link'], ENT_QUOTES | ENT_HTML5),
                ];
            }
        } elseif (empty($adminmenu4altsys)) {
            // add preferences

            if ($mod->getVar('hasconfig') && !in_array($mod->getVar('dirname'), ['system', 'legacy'], true)) {
                $submenus4assign[] = [
                    'title' => _PREFERENCES,
                    'url' => htmlspecialchars(altsys_get_link2modpreferences($mid, $coretype), ENT_QUOTES | ENT_HTML5),
                ];
            }

            // add help

            if (defined('XOOPS_CUBE_LEGACY') && !empty($modinfo['help'])) {
                $submenus4assign[] = [
                    'title' => _HELP,
                    'url' => XOOPS_URL . '/modules/legacy/admin/index.php?action=Help&amp;dirname=' . $dirname,
                ];
            }
        }

        $module4assign = [
            'mid' => $mid,
            'dirname' => $dirname,
            'name' => $mod->getVar('name'),
            'version_in_db' => sprintf('%.2f', $mod->getVar('version') / 100.0),
            'version_in_file' => sprintf('%.2f', $modinfo['version']),
            'description' => htmlspecialchars((string) @$modinfo['description'], ENT_QUOTES | ENT_HTML5),
            'image' => htmlspecialchars((string) $modinfo['image'], ENT_QUOTES | ENT_HTML5),
            'isactive' => $mod->getVar('isactive'),
            'hasmain' => $mod->getVar('hasmain'),
            'hasadmin' => $mod->getVar('hasadmin'),
            'hasconfig' => $mod->getVar('hasconfig'),
            'weight' => $mod->getVar('weight'),
            'adminindex' => htmlspecialchars((string) @$modinfo['adminindex'], ENT_QUOTES | ENT_HTML5),
            'adminindex_absolute' => @$modinfo['adminindex_absolute'],
            'submenu' => $submenus4assign,
            'selected' => $mid == $mid_selected ? true : false,
            'dot_suffix' => $mid == $mid_selected ? 'selected_opened' : 'closed',
        ];

        $block['modules'][] = $module4assign;
    }

    require_once XOOPS_TRUST_PATH . '/libs/altsys/class/D3Tpl.class.php';

    $tpl = new D3Tpl();

    $tpl->assign('block', $block);

    $ret['content'] = $tpl->fetch($this_template);

    return $ret;
}

/**
 * @param $options
 * @return string
 */
function b_altsys_admin_menu_edit($options)
{
    $mydirname = empty($options[0]) ? 'd3forum' : $options[0];

    $this_template = empty($options[1]) ? 'db:' . $mydirname . '_block_admin_menu.tpl' : trim((string) $options[1]);

    if (preg_match('/[^0-9a-zA-Z_-]/', (string) $mydirname)) {
       exit('Invalid mydirname');
    }

    $form = "
        <input type='hidden' name='options[0]' value='$mydirname'>
        <label for='this_template'>" . _MB_ALTSYS_THISTEMPLATE . "</label>&nbsp;:
        <input type='text' size='60' name='options[1]' id='this_template' value='" . htmlspecialchars($this_template, ENT_QUOTES | ENT_HTML5) . "'>
        <br>
    \n";

    return $form;
}
