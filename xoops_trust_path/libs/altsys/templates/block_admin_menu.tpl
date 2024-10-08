<script type="text/javascript">
    function submenuToggle(mid) {
        el = xoopsGetElementById('adminmenu_block_sub_'+mid).style;
        im = xoopsGetElementById('adminmenu_block_dot_status_'+mid) ;
        if (el.display == 'block') {
            el.display = 'none';
            im.src = im.src.replace('opened','closed');
        } else {
            el.display = 'block';
            im.src = im.src.replace('closed','opened');
        }
    }
</script>
<div class="adminmenu_block">
    <{foreach item="module" from=$block.modules|default:null}>
        <div id="adminmenu_block_main_<{$module.mid}>" class="adminmenu_block_main_<{$module.dirname}> adminmenu_block_main" title="<{$module.dirname}>">
            <a id="adminmenu_block_openclose_<{$module.mid}>" href="javascript:void(0);" onclick="submenuToggle(<{$module.mid}>);"><img id="adminmenu_block_dot_status_<{$module.mid}>" src="<{$block.mod_imageurl}>/adminmenu_<{$module.dot_suffix}>.gif" alt="<{$smarty.const._MB_ALTSYS_OPENCLOSE}>"></a> <a href="<{if $module.adminindex_absolute}><{$module.adminindex}><{else}><{$xoops_url}>/modules/<{$module.dirname}>/<{$module.adminindex}><{/if}>" class="adminmenu_block_main_module_name"><{$module.name}></a>
        </div>
        <div id="adminmenu_block_sub_<{$module.mid}>" class="adminmenu_block_sub_<{$module.dirname}> adminmenu_block_sub" style="<{if $module.selected}>display:block<{else}>display:none<{/if}>;">
            <ul>
                <{foreach item="sub" from=$module.submenu|default:null}>
                    <li>
                        <a href="<{$sub.url}>"><{$sub.title}></a>
                    </li>
                <{/foreach}>
            </ul>
            <img src="<{$xoops_url}>/modules/<{$module.dirname}>/<{$module.image}>" alt="<{$module.name}>">
            <span class="version"><{if $module.version_in_db < $module.version_in_file}><strong class="module_versionup"><{$module.version_in_db}>-&gt;<{$module.version_in_file}></strong><{else}><{$module.version_in_db}><{/if}></span>
        </div>
    <{/foreach}>
</div>
