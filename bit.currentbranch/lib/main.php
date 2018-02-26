<?php
/**
 * Created by PhpStorm.
 * User: Fyodor V. verbovenko@inventive.ru
 * Date: 24.01.18
 * Time: 10:51
 */

namespace Bit\Currentbranch;


class Main
{

    const OPTION_NAME = 'git_domain_to_paths';
    const MODULE_ID = 'bit.currentbranch';

   public static function addCurrentBranchButton()
   {

        if ($arBranch = self::getCurrentBranch()) {

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
                    "TEXT" => $arBranch['branch'],
                    "TYPE" => "BIG", //BIG - большая кнопка, иначе маленькая
                    "MAIN_SORT" => 5000, //индекс сортировки для групп кнопок
                    "SORT" => 10, //сортировка внутри группы
                    "HREF" => "javascript:void(0)", //или javascript:MyJSFunction())
                    "ICON" => "icon-class", //название CSS-класса с иконкой кнопки
                    "SRC" => $imagePath,
                    "ALT" => "Текущая ветка ".$arBranch['branch'], //старый вариант
                    "HINT" => array( //тултип кнопки
                        "TITLE" => "Текущая ветка:",
                        "TEXT" => $arBranch['branch'] //HTML допускается
                    ),
                ),
                $bReplace = false //заменить существующую кнопку?
            );
        }

   }

    /**
     * Определяем текущую ветку в зависимости от домена
     *
     * @return mixed
     */
    public static function getCurrentBranch()
    {
        $sDomainsOptions = \COption::GetOptionString(self::MODULE_ID, Main::OPTION_NAME, '');

        $arDomainsOptions = array();
        if (!empty($sDomainsOptions)) $arDomainsOptions = unserialize($sDomainsOptions);

        $sPath = '';
        foreach ($arDomainsOptions as $arDomainGitPath){
            $domain = $path = '';
            extract($arDomainGitPath);
            /*var_dump($domain);
            var_dump($path);*/
            if (substr($path,0,-1)!=='/') $path .= '/';

            if ( strpos($_SERVER['HTTP_HOST'],$domain)!==false && file_exists($path.'.git/HEAD')){
                $sPath = $path;
                break;
            }
        }



        //если путь не определился, ищем в текущей папке
        if ( empty($sPath) &&  !file_exists($_SERVER['DOCUMENT_ROOT'].'/.git/HEAD') ){

            return false;

        }else {

            $sPath = $_SERVER['DOCUMENT_ROOT'].'/';

        }

        /*        var_dump($_SERVER['HTTP_HOST']);
        var_dump($sPath);
        var_dump( file_exists($sPath.'.git/HEAD') );
        var_dump($sPath.'.git/HEAD');*/

        //todo проверка наличия файла
        $stringfromfile = file($sPath.'.git/HEAD', FILE_USE_INCLUDE_PATH);
        $firstLine = $stringfromfile[0]; //get the string from the array
        $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
        $branchname = $explodedstring[2]; //get the one that is always the branch name
        return array('branch'=>$branchname,'path'=>$sPath);

    }

}
