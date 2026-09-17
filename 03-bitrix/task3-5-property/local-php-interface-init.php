<?php
/**
 * Принудительная регистрация типа свойства 3.5
 * (на случай если зависимости модуля не подхватились)
 */

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

if (Loader::includeModule('task3_5_property')) {
    AddEventHandler(
        'iblock',
        'OnIBlockPropertyBuildList',
        ['\\Task3_5\\CustomSortableProperty', 'GetUserTypeDescription']
    );

    $em = EventManager::getInstance();
    $em->addEventHandler(
        'iblock',
        'OnBeforeIBlockElementAdd',
        ['\\Task3_5\\CustomSortableProperty', 'onBeforeElementSave']
    );
    $em->addEventHandler(
        'iblock',
        'OnBeforeIBlockElementUpdate',
        ['\\Task3_5\\CustomSortableProperty', 'onBeforeElementSave']
    );
}
