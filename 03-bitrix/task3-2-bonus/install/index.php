<?php
/**
 * Задание 3.2: Модуль бонусной системы
 * При завершении заказа (сумма > 5000 руб) начисляется 5% бонусов
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class task3_2_bonus extends CModule
{
    public $MODULE_ID = 'task3_2_bonus';
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
        $this->MODULE_NAME = 'Бонусная система';
        $this->MODULE_DESCRIPTION = 'Начисление бонусов за завершённые заказы';
    }

    public function DoInstall()
    {
        global $APPLICATION;

        // Проверка версии PHP
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            $APPLICATION->ThrowException('Требуется PHP 7.0 или выше');
            return false;
        }

        // Проверка наличия модуля Sale
        if (!ModuleManager::isModuleInstalled('sale')) {
            $APPLICATION->ThrowException('Требуется модуль "Интернет-магазин" (sale)');
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
        $this->CreateUserField();

        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();
        
        // Регистрируем обработчик события изменения статуса заказа
        $eventManager->registerEventHandler(
            'sale',
            'OnSaleStatusOrder',
            $this->MODULE_ID,
            '\Task3_2\EventHandlers',
            'OnSaleStatusOrderHandler'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        
        $eventManager->unRegisterEventHandler(
            'sale',
            'OnSaleStatusOrder',
            $this->MODULE_ID,
            '\Task3_2\EventHandlers',
            'OnSaleStatusOrderHandler'
        );
    }

    /**
     * Создание пользовательского поля для хранения бонусов
     */
    private function CreateUserField()
    {
        $oUserTypeEntity = new CUserTypeEntity();
        
        $aUserField = [
            'ENTITY_ID' => 'USER',
            'FIELD_NAME' => 'UF_BONUS_BALANCE',
            'USER_TYPE_ID' => 'double',
            'SORT' => 500,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => [
                'PRECISION' => 2,
                'SIZE' => 20,
                'MIN_VALUE' => 0.0,
                'MAX_VALUE' => 0.0,
                'DEFAULT_VALUE' => 0.0,
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => 'Бонусный счёт',
                'en' => 'Bonus Balance',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Бонусы',
                'en' => 'Bonus',
            ],
        ];
        
        // Проверяем, не существует ли уже поле
        $rsUserField = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_BONUS_BALANCE'
            ]
        );
        
        if (!$rsUserField->Fetch()) {
            $oUserTypeEntity->Add($aUserField);
        }
    }
}
