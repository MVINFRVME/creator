<?php
/**
 * Задание 3.3: Модуль FAQ с email-уведомлениями
 * При добавлении ответа на вопрос отправляется письмо пользователю
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Mail\Internal\EventTypeTable;

Loc::loadMessages(__FILE__);

class task3_3_faq extends CModule
{
    public $MODULE_ID = 'task3_3_faq';
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
        $this->MODULE_NAME = 'FAQ с email-уведомлениями';
        $this->MODULE_DESCRIPTION = 'Отправка ответов на вопросы FAQ на email пользователя';
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
        $this->CreateMailTemplate();

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

        $eventManager->registerEventHandler(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            '\Task3_3\EventHandlers',
            'OnBeforeIBlockElementUpdateHandler'
        );

        $eventManager->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            '\Task3_3\EventHandlers',
            'OnAfterIBlockElementUpdateHandler'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            '\Task3_3\EventHandlers',
            'OnBeforeIBlockElementUpdateHandler'
        );

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            '\Task3_3\EventHandlers',
            'OnAfterIBlockElementUpdateHandler'
        );
    }

    /**
     * Создание типа почтового события и шаблона
     */
    private function CreateMailTemplate()
    {
        // Создаём тип почтового события
        $et = new CEventType();
        $et->Add([
            'LID' => 'ru',
            'EVENT_NAME' => 'FAQ_ANSWER',
            'NAME' => 'Ответ на вопрос FAQ',
            'DESCRIPTION' => 
                "#USER_EMAIL# - Email пользователя\n" .
                "#QUESTION# - Текст вопроса\n" .
                "#ANSWER# - Текст ответа\n" .
                "#SITE_NAME# - Название сайта"
        ]);

        // Создаём почтовый шаблон
        $emess = new CEventMessage();
        $emess->Add([
            'ACTIVE' => 'Y',
            'EVENT_NAME' => 'FAQ_ANSWER',
            'LID' => ['s1'], // ID сайта, можно настроить
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO' => '#USER_EMAIL#',
            'SUBJECT' => 'Ответ на ваш вопрос - #SITE_NAME#',
            'BODY_TYPE' => 'html',
            'MESSAGE' => 
                '<h2>Ваш вопрос получил ответ!</h2>' .
                '<p><strong>Вопрос:</strong><br>#QUESTION#</p>' .
                '<hr>' .
                '<p><strong>Ответ:</strong><br>#ANSWER#</p>' .
                '<hr>' .
                '<p>С уважением,<br>Команда #SITE_NAME#</p>'
        ]);
    }
}
