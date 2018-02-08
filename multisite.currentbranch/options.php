<?
use Bitrix\Main\Localization\Loc,Bitrix\Main\Loader;

$module_id = "multisite.currentbranch";
Loader::includeSharewareModule($module_id);

$RIGHT = $GLOBALS['APPLICATION']->GetGroupRight($module_id);
if ($RIGHT >= "R") :

    $arAllOptions = [];//\Multisite\Сurrentbranch\Main::getOptions();


    $aTabs = array(
        array("DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_TAB_SET"), "ICON" => "ib_settings", "TITLE" => 'Включение рекомендаций мультисайта'),
    );
    $tabControl = new CAdminTabControl("tabControl", $aTabs);

    if($GLOBALS['REQUEST_METHOD']=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
    {
        if(strlen($RestoreDefaults)>0)
        {
            COption::RemoveOption($module_id);
        }
        else
        {
            foreach($arAllOptions as $arOption)
            {
                $name=$arOption[0];
                $val=$_REQUEST[$name];
                if($arOption[2][0]=="checkbox" && $val!="Y")
                    $val="N";
                COption::SetOptionString($module_id, $name, $val, $arOption[1]);
            }
        }
        if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
            LocalRedirect($_REQUEST["back_url_settings"], false, '301 Moved permanently');
        else
            LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam(), false, '301 Moved permanently');
    }
    $tabControl->Begin();
    ?>
    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
        <?$tabControl->BeginNextTab();?>

        <?
        foreach($arAllOptions as $arOption):
            $val = COption::GetOptionString($module_id, $arOption[0], $arOption[2]);
            $type = $arOption[3];

            if (isset($arOption[4]))
                $arNotes[] = $arOption[4];
            ?>
            <tr>
                <td width="12%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
                    <? if (isset($arOption[4])): ?>
                        <span class="required"><sup><? echo count($arNotes) ?></sup></span>
                    <? endif; ?>
                    <label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
                <td width="88%">
                    <?if($type[0]=="checkbox"):?>
                        <input type="checkbox" id="<?echo htmlspecialcharsbx($arOption[0])?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
                    <?elseif($type[0]=="text"):?>
                        <input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>">
                    <?elseif($type[0]=="textarea"):?>
                        <textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
                    <?endif?>
                </td>
            </tr>
        <?endforeach?>

        <?$tabControl->Buttons();?>

        <input type="submit" name="Update" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_SAVE_TITLE")?>" class="adm-btn-save">
        <?/*
        <input type="submit" name="Apply" value="<?=Loc::getMessage("MAIN_APPLY")?>" title="<?=Loc::getMessage("MAIN_APPLY_TITLE")?>">
        <?if(strlen($_REQUEST["back_url_settings"])>0):?>
            <input type="button" name="Cancel" value="<?=Loc::getMessage("MAIN_CANCEL")?>" title="<?=Loc::getMessage("MAIN_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
            <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
        <?endif?>
        <input type="submit" name="RestoreDefaults" title="<?echo Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">*/?>
        <?=bitrix_sessid_post();?>
        <?$tabControl->End();?>
    </form>
<?
endif;
?>