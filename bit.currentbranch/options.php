<?
use Bitrix\Main\Localization\Loc,Bitrix\Main\Loader;
use Bit\Currentbranch\Main;


$module_id = "bit.currentbranch";
Loader::includeSharewareModule($module_id);

$RIGHT = $GLOBALS['APPLICATION']->GetGroupRight($module_id);
if ($RIGHT >= "R") :

    $arAllOptions = array();


    $aTabs = array(
        array("DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_TAB_SET"), "ICON" => "ib_settings", "TITLE" => 'Git path'),
    );
    $tabControl = new CAdminTabControl("tabControl", $aTabs);

    if($GLOBALS['REQUEST_METHOD']=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
    {

        $arDomainToPath = array();
        foreach ($_REQUEST['domains'] as $k=>$sDomain ){
            $sPath = $_REQUEST['paths'][$k];

            if ( !empty($sDomain) || !empty($sPath) )
                $arDomainToPath[] = array('domain'=>$sDomain, 'path'=> $sPath );

            $sDomain = $sPath = '';
        }


        COption::SetOptionString($module_id, Main::OPTION_NAME, serialize($arDomainToPath));



        if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
            LocalRedirect($_REQUEST["back_url_settings"], false, '301 Moved permanently');
        else
            LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam(), false, '301 Moved permanently');
    }


    $sVals = COption::GetOptionString($module_id, Main::OPTION_NAME, '');

    $arVals = array();
    if (!empty($sVals)) $arVals = unserialize($sVals);

    $tabControl->Begin();
    ?>

    <form method="post" id="git-domain-paths" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
        <?$tabControl->BeginNextTab();?>


        <tr>
            <td width="50%" style="text-align: left">
                <b>Домен</b>
            </td>
            <td width="50%" style="text-align: left">
                <b>Путь к git</b>
            </td>
        </tr>

        <?

        for($i=0;$i<=count($arVals)+3;$i++):
            $arRowVals = array('domain'=>'','path'=>'');
            if ( isset($arVals[$i]['domain']) )
                $arRowVals = $arVals[$i]; //['domain'=>$sDomain, 'path'=> $sPath ];


            ?><tr>
                <td width="50%" style="text-align: left" >
                    <input type="text" placeholder="example.com (без http)" style="width: 90%" value="<?echo htmlspecialcharsbx($arRowVals['domain'])?>" name="domains[]">
                </td>
                <td width="50%" style="text-align: left">
                        <input type="text"  placeholder="Например, /var/www/bitrix/" style="width: 90%" value="<?echo htmlspecialcharsbx($arRowVals['path'])?>" name="paths[]">
                </td>
            </tr>
        <?endfor?>



        <tr id="buttontr">
            <td width="50%" style="text-align: left">
                <button id="add-more" class="adm-btn-save">Добавить ещё</button>

            </td>

        </tr>

        <tr>
            <td width="50%" style="text-align: right">
                Убедитеь в том, что ветка определяется правильно:
            </td>
            <td width="50%" style="text-align: left">
                <?
                $branch = Main::getCurrentBranch();
                echo $branch?'<p style="color:darkgreen; font-weight:bold;">'.$branch['branch'].'</p>':'<p style="color:darkred; font-weight:bold;">Git не найден.</p>';
                ?>
            </td>
        </tr>

        <tr>
            <td width="50%" style="text-align: right">
                Путь:
            </td>
            <td width="50%" style="text-align: left">
                <?
                echo '<p>'.$branch['path'].'</p>';
                ?>
            </td>
        </tr>

        <?$tabControl->Buttons();?>

        <input type="submit" name="Update" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_SAVE_TITLE")?>" class="adm-btn-save">

        <?$tabControl->End();?>
        <?=bitrix_sessid_post();?>
    </form>
    <script>

        window.onload=function() {

            document.getElementById('add-more').onclick = function () {

                //var parentElement = document.getElementById('git-domain-paths');

                var newRow = '<td width="50%" style="text-align: left"><input type="text"  style="width: 90%" value="" name="domains[]"></td><td width="50%" style="text-align: left"><input type="text"  style="width: 90%" value="" name="paths[]"></td>';
                var div = document.createElement('tr');
                div.innerHTML = newRow.trim();
                //console.log(div);

                //newRow = div.firstChild;

                //document.body.insertBefore(p, document.body.firstChild);

                document.getElementById('buttontr').parentNode.insertBefore(div, document.getElementById('buttontr'));
                return false;

            }
        }



    </script>
<?
endif;
?>