<?php declare(strict_types=1);

require_once XOOPS_ROOT_PATH . '/modules/legacyRender/kernel/Legacy_AdminRenderSystem.class.php';
require_once XOOPS_TRUST_PATH . '/libs/altsys/include/altsys_functions.php';
require_once XOOPS_TRUST_PATH . '/libs/altsys/include/admin_in_theme_functions.php';

/**
 * Class Legacy_AltsysAdminRenderSystem
 */
class Legacy_AltsysAdminRenderSystem extends Legacy_AdminRenderSystem
{
    /**
     * @param $target
     */
    public function renderTheme(&$target): void
    {
        global $altsysModuleConfig;

        if (empty($altsysModuleConfig['admin_in_theme'])) {
            parent::renderTheme($target);
        } else {
            $attributes = $target->getAttributes();

            altsys_admin_in_theme_in_last($attributes['xoops_contents']);

            exit;
        }
    }
}
