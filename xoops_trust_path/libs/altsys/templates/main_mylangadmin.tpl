<form name="SelectLangFile" action="index.php" method="get">
    <input type="hidden" name="mode" value="admin">
    <input type="hidden" name="lib" value="altsys">
    <input type="hidden" name="page" value="mylangadmin">
    <input type="hidden" name="dirname" value="<{$target_dirname}>">
    <select name="target_lang" onchange="submit();">
        <{html_options values=$languages output=$languages4disp selected=$target_lang}>
    </select>
    <select name="target_file" onchange="submit();">
        <{html_options values=$lang_files output=$lang_files selected=$target_file}>
    </select>
    <input type="submit" value="<{$smarty.const._SUBMIT}>">
</form>

<h3 style="text-align:<{$smarty.const._GLOBAL_LEFT}>;"><{$smarty.const._MYLANGADMIN_H3_MODULE}> : <{$target_mname}> : <{$target_lang|escape}> : <{$target_file|escape}></h3>

<dl class="altsys_langman_information">
    <{if $use_my_language}>
    <dt><{$smarty.const._MYLANGADMIN_DT_MYLANGFILENAME}></dt>
    <dd><{$mylang_file_name}></dd>
    <{/if}>
    <dt><{$smarty.const._MYLANGADMIN_DT_CACHEFILENAME}></dt>
    <dd><{$cache_file_name}></dd>
    <dt><{$smarty.const._MYLANGADMIN_DT_CACHESTATUS}></dt>
    <dd><{if $cache_file_mtime}><{$smarty.const._MYLANGADMIN_CREATED}> (<{"Y-m-d H:i:s"|date:$cache_file_mtime+$timezone_offset}>)<{else}><{$smarty.const._MYLANGADMIN_NOTCREATED}><{/if}></dd>
</dl>

<form name="MainForm" action="index.php?mode=admin&amp;lib=altsys&amp;page=mylangadmin&amp;dirname=<{$target_dirname}>&amp;target_lang=<{$target_lang}>&amp;target_file=<{$target_file}>" method="post">
    <table class="outer">
        <tr>
            <th><{$smarty.const._MYLANGADMIN_TH_CONSTANTNAME}></th>
            <th>
                <{$smarty.const._MYLANGADMIN_TH_DEFAULTVALUE}>
                <{if $already_read}><br>(<{$smarty.const._MYLANGADMIN_MSG_NOTICE4ALREADYREAD}>)<{/if}>
            </th>
            <th><{$smarty.const._MYLANGADMIN_TH_USERVALUE}></th>
        </tr>

        <{foreach item="langfile_uservalue" from=$langfile_constants|default:null key="langfile_name" }>
        <tr class="<{cycle values="even,odd"}>" title="<{$langfile_name|escape}>">
            <td><label for="input_<{$langfile_name|escape}>"><{$langfile_name|truncate:30|escape}></label></td>
            <td>
                <div style="width:240px; overflow: auto;">
                    <{$langfile_name|constant|escape}>
                </div>
            </td>
            <td>
                <{if empty($mylang_constants.$langfile_name)}>
                    <{if strlen(constant($langfile_name)) < 32}>
                        <input type="text" name="<{$langfile_name|escape}>" id="input_<{$langfile_name|escape}>" value="<{$langfile_uservalue|escape}>" style="width:240px;<{if $langfile_uservalue}>background-color:#ffffcc;<{/if}>">
                    <{else}>
                        <textarea name="<{$langfile_name|escape}>" id="input_<{$langfile_name|escape}>" style="width:240px;<{if $langfile_uservalue}>background-color:#ffffcc;<{/if}>"><{$langfile_uservalue|escape}></textarea>
                    <{/if}>
                <{else}>
                    <span class="my_language"><{$mylang_constants.$langfile_name}></span>
                <{/if}>
            </td>
        </tr>
        <{/foreach}>

    </table>
    <div align="center">
        <input type="submit" name="do_update" value="<{$smarty.const._MYLANGADMIN_BTN_UPDATE}>">
        <input type="reset" value="<{$smarty.const._MYLANGADMIN_BTN_RESET}>">
        <{$gticket_hidden}>
    </div>
</form>

<div class="altsys_mylangadmin_notice">
    <{$notice}>
</div>
