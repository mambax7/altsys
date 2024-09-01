<?php declare(strict_types=1);
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */

/**
 * Parent
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formcheckbox.php';

/**
 * A checkbox field with a choice of available groups
 *
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */
class formcheckboxgroup extends XoopsFormCheckBox
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param bool   $include_anon Include group "anonymous"?
     * @param mixed  $value        Pre-selected value (or array of them).
     */
    public function __construct($caption, $name, $include_anon = false, $value = null)
    {
        parent::__construct($caption, $name, $value);

        $memberHandler = xoops_getHandler('member');

        if (!$include_anon) {
            $options = $memberHandler->getGroupList(new \Criteria('groupid', XOOPS_GROUP_ANONYMOUS, '!='));
        } else {
            $options = $memberHandler->getGroupList();
        }

        foreach ($options as $k => $v) {
            $options[$k] = $v . '<br>';
        }

        $this->addOptionArray($options);
    }
}
