<?php declare(strict_types=1);

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

/**
 * @param      $DB
 * @param      $gperm_modid
 * @param null $gperm_name
 * @param null $gperm_itemid
 * @return bool
 */
function myDeleteByModule($DB, $gperm_modid, $gperm_name = null, $gperm_itemid = null)
{
    $criteria = new CriteriaCompo(new Criteria('gperm_modid', (int) $gperm_modid));

    if (isset($gperm_name)) {
        $criteria->add(new Criteria('gperm_name', $gperm_name));

        if (isset($gperm_itemid)) {
            $criteria->add(new Criteria('gperm_itemid', (int) $gperm_itemid));
        }
    }

    $sql = 'DELETE FROM ' . $DB->prefix('group_permission') . ' ' . $criteria->renderWhere();

    if (!$result = $DB->query($sql)) {
        return false;
    }

    return true;
}

// require dirname(__DIR__, 3) . '/include/cp_header.php'; GIJ
$modid = \Xmf\Request::getInt('modid', 1, 'POST');

if (1 == $modid) {
    // check by the permission of eather 'altsys' or 'system'

    $moduleHandler = xoops_getHandler('module');

    $module = $moduleHandler->getByDirname('altsys');

    if (!is_object($module)) {
        $module = $moduleHandler->getByDirname('system');

        if (!is_object($module)) {
            die('there is no altsys nor system.');
        }
    }

    $grouppermHandler = xoops_getHandler('groupperm');

    if (!is_object(@$GLOBALS['xoopsUser']) || !$grouppermHandler->checkRight('module_admin', $module->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
        die('only admin of altsys can access this area');
    }
} else {
    // check the permission of 'module_admin' of the module

    if ($modid <= 0 || !is_object($GLOBALS['xoopsUser']) || !$GLOBALS['xoopsUser']->isAdmin($modid)) {
        die(_NOPERM);
    }

    $moduleHandler = xoops_getHandler('module');

    $module = $moduleHandler->get($modid);

    if (!is_object($module) || !$module->getVar('isactive')) {
        die(_MODULENOEXIST);
    }
}

$memberHandler = xoops_getHandler('member');
$group_list = $memberHandler->getGroupList();
if (!empty($_POST['perms']) && is_array($_POST['perms'])) {
    if (!isset($msg) || !is_array($msg)) {
        $msg = isset($msg) ? [$msg] : [];
    }
    $grouppermHandler = xoops_getHandler('groupperm');

    foreach ($_POST['perms'] as $perm_name => $perm_data) {
        foreach ($perm_data['itemname'] as $item_id => $item_name) {
            // checking code

            // echo "<pre>" ;

            // var_dump( $_POST['perms'] ) ;

            // exit ;

            if (false !== myDeleteByModule($grouppermHandler->db, $modid, $perm_name, $item_id)) {
                if (empty($perm_data['groups'])) {
                    continue;
                }

                foreach ($perm_data['groups'] as $group_id => $item_ids) {
                    //              foreach ($item_ids as $item_id => $selected) {

                    $selected = $item_ids[$item_id] ?? 0;

                    if (1 == $selected) {
                        // make sure that all parent ids are selected as well

                        if ('' != $perm_data['parents'][$item_id]) {
                            $parent_ids = explode(':', $perm_data['parents'][$item_id]);

                            foreach ($parent_ids as $pid) {
                                if (0 != $pid && !array_key_exists($pid, $item_ids)) {
                                    // one of the parent items were not selected, so skip this item

                                    $msg[] = sprintf(_MD_A_MYBLOCKSADMIN_PERMADDNG, '<b>' . $perm_name . '</b>', '<b>' . $perm_data['itemname'][$item_id] . '</b>', '<b>' . $group_list[$group_id] . '</b>') . ' (' . _MD_A_MYBLOCKSADMIN_PERMADDNGP . ')';

                                    continue 2;
                                }
                            }
                        }

                        $gperm = $grouppermHandler->create();

                        $gperm->setVar('gperm_groupid', $group_id);

                        $gperm->setVar('gperm_name', $perm_name);

                        $gperm->setVar('gperm_modid', $modid);

                        $gperm->setVar('gperm_itemid', $item_id);

                        if (!$grouppermHandler->insert($gperm)) {
                            $msg[] = sprintf(_MD_A_MYBLOCKSADMIN_PERMADDNG, '<b>' . $perm_name . '</b>', '<b>' . $perm_data['itemname'][$item_id] . '</b>', '<b>' . $group_list[$group_id] . '</b>');
                        } else {
                            $msg[] = sprintf(_MD_A_MYBLOCKSADMIN_PERMADDOK, '<b>' . $perm_name . '</b>', '<b>' . $perm_data['itemname'][$item_id] . '</b>', '<b>' . $group_list[$group_id] . '</b>');
                        }

                        unset($gperm);
                    }
                }
            } else {
                $msg[] = sprintf(_MD_A_MYBLOCKSADMIN_PERMRESETNG, $module->getVar('name'));
            }
        }
    }
}
/*
$backlink = XOOPS_URL.'/admin.php';
if ($module->getVar('hasadmin')) {
    $adminindex = $module->getInfo('adminindex');
    if ($adminindex) {
        $backlink = XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$adminindex;
    }
}

$msg[] = '<br><br><a href="'.$backlink.'">'._BACK.'</a>';
xoops_cp_header();
xoops_result($msg);
xoops_cp_footer();  GIJ */
