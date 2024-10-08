<a href="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>"><{$smarty.const._MD_A_MYBLOCKSADMIN_BLOCKADMIN}></a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;<{$form_title}><br><br>

<h3 style='text-align:<{$smarty.const._GLOBAL_LEFT}>;'><{$target_mname}></h3>

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

<{if $block.ctype != 'P' }>
<{if $block.ctype == 'H' || empty($block.ctype) }>
    <{if $common_fck_installed}>
    <script type="text/javascript" src="<{$xoops_url}>/common/fckeditor/fckeditor.js"></script>
    <script type="text/javascript"><!--
        function fckeditor_exec() {
            var oFCKeditor = new FCKeditor( "textarea_content" , "100%" , "500" , "Default" );
            oFCKeditor.BasePath = "<{$xoops_url}>/common/fckeditor/";
            oFCKeditor.ReplaceTextarea();
        }
    // --></script>
    <{else}>
    <{php}>
        $_makescript = xoops_getModuleHandler('makescript','myckeditor',true);
        $params = array('id'=>'textarea_content','editor'=>'html','myckeditor'=>true);
        if (@is_object( $_makescript )){
            $this->assign( 'common_myck_installed' , true ) ;
            $_makescript->makeheader($params);
        }
    <{/php}>
    <{/if}>
<{else}>
    <{php}>
        $_makescript = xoops_getModuleHandler('makescript','myckeditor',true);
        $params = array('id'=>'textarea_content','editor'=>'bbcode','myckeditor'=>true);
        if (@is_object( $_makescript )){
            $this->assign( 'common_myck_installed' , true ) ;
            $_makescript->makeheader($params);
        }
    <{/php}>
<{/if}>
<{/if}>
<form name="blockform" id="blockform" action="?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=<{$target_dirname}>&amp;bid=<{$block.bid}>" method="post">

<table class="outer">
    <tr>
        <th colspan="2"><{$form_title}></th>
    </tr>
    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_NAME}></td>
        <td class="even">
            <{$block.name_raw|escape}>
        </td>
    </tr>
    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_TITLE}></td>
        <td class="even">
            <input type="text" name="titles[<{$block.bid}>]" value="<{$block.title_raw|escape}>" size="40">
        </td>
    </tr>
    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_SIDE}></td>
        <td class="even">
            <{$block.cell_position}>
        </td>
    </tr>
    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_WEIGHT}></td>
        <td class="even">
            <input type="text" name="weights[<{$block.bid}>]" value="<{$block.weight}>" size="3" maxlength="5" style="text-align:<{$smarty.const._GLOBAL_RIGHT}>;">
        </td>
    </tr>
    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_VISIBLEIN}></td>
        <td class="even">
            <{$block.cell_module_link}>
            <{$block.cell_group_perm}>
        </td>
    </tr>


    <{if $block.is_custom}>

        <tr>
            <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_CONTENT}>
            <{if $common_myck_installed|default:''}>
                <{if $block.ctype == 'H' || empty($block.ctype) }>
                <br>
                <input type="button" class="formButton" value="CKEeditorON" name="myckeditorOn" id="myckeditorOn" onclick="if(this.value=='CKEeditorON'){this.value='CKEeditorOFF';textarea_content_myckeditor_exec();}else{this.value='CKEeditorON';textarea_content_myckeditor_remove();}">
                <{else}>
                <br>
                <input type="button" class="formButton" value="CKEeditorON" name="myckeditorOn" id="myckeditorOn" onclick="if(this.value=='CKEeditorON'){this.value='CKEeditorOFF';textarea_content_myckeditor_exec();document.getElementById('switch_bbcode').checked=false;document.getElementById('textarea_content_bbcode_buttons_pre').style.display='none';document.getElementById('textarea_content_bbcode_buttons_post').style.display='none';}else{this.value='CKEeditorON';textarea_content_myckeditor_remove();}">
                <{/if}>
            <{/if}>
            </td>
            <td class="even">
                <{if $block.ctype == 'P'}>
                    <textarea name="textarea_content" id="textarea_content" cols="80" rows="20"><{$block.content_raw|escape}></textarea>
                    <{if ! $common_fck_installed}><br><{$smarty.const._MD_A_MYBLOCKSADMIN_NOTICE4COMMONFCK}><{/if}>
                <{elseif  $block.ctype == 'H' && $common_fck_installed}>
                    <textarea name="textarea_content" id="textarea_content" cols="80" rows="20"><{$block.content}></textarea>
                    <script>fckeditor_exec();</script>
                <{elseif $block.ctype == 'H' || empty($block.ctype) }>
                    <{if $common_myck_installed|default:''}>
                    <textarea name="textarea_content" id="textarea_content" cols="80" rows="20"><{$block.content}></textarea>
                    <{else}>
                    <{$altsys_x25_dhtmltextarea}>
                    <{/if}>
                <{else}>
                    <div><input type="checkbox" id="switch_bbcode" checked=true onclick="if(this.checked){document.getElementById('textarea_content_bbcode_buttons_pre').style.display='block';document.getElementById('textarea_content_bbcode_buttons_post').style.display='block';document.getElementById('myckeditorOn').value='CKEeditorON';textarea_content_myckeditor_remove();}else{document.getElementById('textarea_content_bbcode_buttons_pre').style.display='none';document.getElementById('textarea_content_bbcode_buttons_post').style.display='none';}">
                    <label for="switch_bbcode">BBCode</label></div>
                    <{$altsys_x25_dhtmltextarea}>
                <{/if}>
            </td>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_CTYPE}></td>
            <td class="even">
                <select name="ctypes[<{$block.bid}>]" size="1">
                    <{html_options options=$ctype_options selected=$block.ctype}>
                </select>
            </td>
        </tr>

    <{else}>

        <{if $block.template_tplset}>
        <tr>
            <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_CONTENT}></td>
            <td class="even">
                <a href="?mode=admin&amp;lib=altsys&amp;page=mytplsform&amp;tpl_file=<{$block.template}>&amp;tpl_tplset=<{$block.template_tplset}>"><{$smarty.const._MD_A_MYBLOCKSADMIN_EDITTPL}></a>
            </td>
        </tr>
        <{/if}>

        <tr>
            <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_OPTIONS}></td>
            <td class="even">
                <{$block.cell_options}>
            </td>
        </tr>

    <{/if}>

    <tr>
        <td class="head"><{$smarty.const._MD_A_MYBLOCKSADMIN_BCACHETIME}></td>
        <td class="even">
            <select name="bcachetimes[<{$block.bid}>]" size="1">
                <{html_options options=$cachetime_options selected=$block.bcachetime}>
            </select>
        </td>
    </tr>
    <tr>
        <td class="head"></td>
        <td class="even">
            <{if $block.is_custom}>
                <input type="submit" class="formButton" name="preview"  id="preview" value="<{$smarty.const._PREVIEW}>">
            <{/if}>
            <input type="submit" class="formButton" name="submitblock"  id="submitblock" value="<{$submit_button}>">
        </td>
    </tr>
</table>
<{$gticket_hidden}>
<input type="hidden" name="op" value="<{$op}>">
</form>

<{if $block.content_preview}>
    <div class="myblocksadmin_preview">
        <{$block.content_preview}>
    </div>
<{/if}>

