<div class="altsys_mymenu altsys_mymenusub altsys_mymenusub_<{$mypage}>">
    <{foreach  item="menuitem" from=$adminmenu|default:null}>
        <div>
            <nobr><a href="<{$menuitem.link|escape}>" class="<{if $menuitem.selected}>selected<{else}>unselected<{/if}>"><{$menuitem.title|escape}></a> | </nobr>
        </div>
    <{/foreach}>
</div>
<hr class="altsys_mymenu_separator altsys_mymenusub_separator">
