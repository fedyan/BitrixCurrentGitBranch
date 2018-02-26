<?php
IncludeModuleLangFile(__FILE__);
Class bit_currentbranch extends CModule
{
    var $MODULE_ID = "bit.currentbranch";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        include("version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");
        $this->MODULE_NAME = GetMessage("CURRENT_BRANCH_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("CURRENT_BRANCH_MODULE_DESC");

    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, '\Bit\Currentbranch\Main', "addCurrentBranchButton");
        CAdminMessage::ShowNote("Модуль установлен");
    }

    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
        UnRegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, '\Bit\Currentbranch\Main', "addCurrentBranchButton");
        CAdminMessage::ShowNote("Модуль успешно удален из системы");
    }

}

?>