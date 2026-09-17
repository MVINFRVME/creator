<?php
/**
 * Регистрация свойства 3.5 без модуля
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/CustomSortableProperty.php';

AddEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    ['\\Task3_5\\CustomSortableProperty', 'GetUserTypeDescription']
);

AddEventHandler(
    'iblock',
    'OnBeforeIBlockElementAdd',
    ['\\Task3_5\\CustomSortableProperty', 'onBeforeElementSave']
);

AddEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    ['\\Task3_5\\CustomSortableProperty', 'onBeforeElementSave']
);
