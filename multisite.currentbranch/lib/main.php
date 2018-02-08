<?php
/**
 * Created by PhpStorm.
 * User: Fyodor V. verbovenko@inventive.ru
 * Date: 24.01.18
 * Time: 10:51
 */

namespace Multisite\Currentbranch;


class Main
{


   public static function addCurrentBranchButton()
   {
       //todo выбор пути к гиту в зависимости от домена из настроек модуля
       $sPath = '';
        if ($sBranch = self::getCurrentBranch($sPath)) {

            //core/local/modules/multisite.currentbranch/lib/main.php
            $imagePath = str_replace('/lib/main.php','',__FILE__).'/files/git-branch.png';

            $localPos = strpos($imagePath,'/local/modules');
            $bitrixPos = strpos($imagePath,'/bitrix/modules');

            if ( $localPos!==false ){

                $imagePath = substr($imagePath,$localPos);

            } elseif ( $localPos!==false ) {
                $imagePath = substr($imagePath,$bitrixPos);
            }

            $GLOBALS['APPLICATION']->AddPanelButton(
                Array(
                    "ID" => "current_branch_button", //определяет уникальность кнопки
                    "TEXT" => $sBranch,
                    "TYPE" => "BIG", //BIG - большая кнопка, иначе маленькая
                    "MAIN_SORT" => 5000, //индекс сортировки для групп кнопок
                    "SORT" => 10, //сортировка внутри группы
                    "HREF" => "javascript:void(0)", //или javascript:MyJSFunction())
                    "ICON" => "icon-class", //название CSS-класса с иконкой кнопки
                    "SRC" => $imagePath,
                    "ALT" => "Текущая ветка ".$sBranch, //старый вариант
                    "HINT" => array( //тултип кнопки
                        "TITLE" => "Текущая ветка:",
                        "TEXT" => $sBranch //HTML допускается
                    ),
                ),
                $bReplace = false //заменить существующую кнопку?
            );
        }

   }

    /**
     * Определяем текущую ветку
     * @param string $path
     * @return mixed
     */
    public static function getCurrentBranch($path='')
    {
        //todo проверка наличия файла
        $stringfromfile = file($path.'.git/HEAD', FILE_USE_INCLUDE_PATH);
        $firstLine = $stringfromfile[0]; //get the string from the array
        $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
        $branchname = $explodedstring[2]; //get the one that is always the branch name
        return $branchname;

    }

}
