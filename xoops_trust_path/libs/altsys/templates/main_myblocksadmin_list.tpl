<h3 style='text-align:<{$smarty.const._GLOBAL_LEFT}>;'><{$target_mname}></h3>

<h4 style="text-align:<{$smarty.const._GLOBAL_LEFT}>;"><{$smarty.const._MD_A_MYBLOCKSADMIN_BLOCKADMIN}></h4>
<style>
    td.blockposition    {width:135px;white-space:nowrap;}
    div.blockposition   {float:<{$smarty.const._GLOBAL_LEFT}>;border:solid 1px #333333;padding:1px;}
    div.unselected      {background-color:#FFFFFF;}
    div.selected        {background-color:#00FF00;}
    div.disabled        {background-color:#FF0000;}
    input[type='radio'] {margin:2px;}
    input[type='text'] {min-width:0%;}
    label               {display:inline;}
</style>

<form action="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>" name="blockadmin" method="post">
    <table width="95%" class="outer" cellpadding="4" cellspacing="1">
    <tr valign="middle">
        <th><{$smarty.const._MD_A_MYBLOCKSADMIN_TITLE}></th>
        <th align="center" nowrap="nowrap"><{$smarty.const._MD_A_MYBLOCKSADMIN_SIDE}></th>
        <th align="center"><{$smarty.const._MD_A_MYBLOCKSADMIN_WEIGHT}></th>
        <th align="center"><{$smarty.const._MD_A_MYBLOCKSADMIN_VISIBLEIN}></th>
        <th align="center"><{$smarty.const._MD_A_MYBLOCKSADMIN_BCACHETIME}></th>
        <th align="<{$smarty.const._GLOBAL_RIGHT}>"><{$smarty.const._MD_A_MYBLOCKSADMIN_ACTION}></th>
    </tr>

    <{foreach from=$blocks item="block"}>
        <tr valign="middle" class="<{cycle values="even,odd"}>">
            <td>
                <{$block.name_raw|escape}>
                <br>
                <input type="text" name="titles[<{$block.bid}>]" value="<{$block.title_raw|escape}>" size="20">
            </td>
            <td class="blockposition" align="center">
                <{$block.cell_position}>
            </td>
            <td align="center">
                <input type="text" name="weights[<{$block.bid}>]" value="<{$block.weight}>" size="3" maxlength="5" style="text-align:<{$smarty.const._GLOBAL_RIGHT}>;">
            </td>
            <td align="center">
                <{$block.cell_module_link}>
                <{$block.cell_group_perm}>
            </td>
            <td align="center">
                <select name="bcachetimes[<{$block.bid}>]" size="1">
                    <{html_options options=$cachetime_options selected=$block.bcachetime}>
                </select>
            </td>
            <td align="<{$smarty.const._GLOBAL_RIGHT}>">
                <{if $block.can_edit}>
                    <a href="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>&amp;op=edit&amp;bid=<{$block.bid}>"><{$smarty.const._EDIT}></a>
                <{/if}>
                <br>
                <{if $block.can_delete}>
                    <a href="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>&amp;op=delete&amp;bid=<{$block.bid}>"><{$smarty.const._DELETE}></a>
                <{/if}>
                <br>
                <{if $block.can_clone}>
                    <a href="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>&amp;op=clone&amp;bid=<{$block.bid}>"><{if $block.can_clone == 2}><{$smarty.const._CLONE}><{else}><{$smarty.const._MD_A_MYBLOCKSADMIN_LINK_FORCECLONE}><{/if}></a>
                <{/if}>
            </td>
        </tr>
    <{/foreach from=$blocks item="block"}>

    <tr>
        <td class="foot" align="center" colspan="6">
            <input type="hidden" name="fct" value="blocksadmin">
            <input type="hidden" name="op" value="order">
            <{$gticket_hidden}>
            <input type="submit" name="submit" value="<{$smarty.const._SUBMIT}>">
        </td>
    </tr>
    </table>
</form>
