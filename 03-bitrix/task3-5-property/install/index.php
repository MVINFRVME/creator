<?php
/**
 * Задание 3.5: Кастомное множественное свойство с настройками
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class task3_5_property extends CModule
{
    public $MODULE_ID = 'task3_5_property';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = 'Кастомное свойство с сортировкой';
        $this->MODULE_DESCRIPTION = 'Множественное свойство строка с индексом сортировки';
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            $APPLICATION->ThrowException('Требуется PHP 7.0 или выше');
            return false;
        }

        if (!ModuleManager::isModuleInstalled('iblock')) {
            $APPLICATION->ThrowException('Требуется модуль "Информационные блоки" (iblock)');
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();

        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        // Старый API — надёжнее подхватывается админкой свойств
        RegisterModuleDependences(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'GetUserTypeDescription'
        );
        RegisterModuleDependences(
            'iblock',
            'OnBeforeIBlockElementAdd',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'onBeforeElementSave'
        );
        RegisterModuleDependences(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'onBeforeElementSave'
        );
    }

    public function UnInstallEvents()
    {
        UnRegisterModuleDependences(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'GetUserTypeDescription'
        );
        UnRegisterModuleDependences(
            'iblock',
            'OnBeforeIBlockElementAdd',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'onBeforeElementSave'
        );
        UnRegisterModuleDependences(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            '\\Task3_5\\CustomSortableProperty',
            'onBeforeElementSave'
        );
    }
}
